# Mobile Payment Implementation — Full Analysis & Technical Plan

> **Project**: SIG-RECETTE v0.9.2 | **Date**: March 28, 2026
> **Scope**: Add verification-based mobile money payments (QOSIC, FedaPay, PayGate) to existing cash/cheque system

---

## Table of Contents

1. [Existing System Analysis](#1-existing-system-analysis)
2. [Impact Assessment — What Gets Modified](#2-impact-assessment--what-gets-modified)
3. [Architecture Decision: Extend vs. Parallel](#3-architecture-decision-extend-vs-parallel)
4. [Database Changes](#4-database-changes)
5. [Backend Implementation Plan](#5-backend-implementation-plan)
6. [Provider Integration Layer](#6-provider-integration-layer)
7. [Verification Job System](#7-verification-job-system)
8. [UI Changes](#8-ui-changes)
9. [SMS Module](#9-sms-module)
10. [Sync Impact](#10-sync-impact)
11. [Offline / Low-Connectivity Strategy](#11-offline--low-connectivity-strategy)
12. [File-by-File Change Manifest](#12-file-by-file-change-manifest)
13. [Risk Analysis](#13-risk-analysis)
14. [Implementation Order](#14-implementation-order)

---

## 1. Existing System Analysis

### 1.1 Current Payment Architecture

The existing payment system is a **multi-code, split-payment architecture** with observer-driven balance tracking. Understanding this is critical — mobile payments must integrate into this exact flow or the accounting breaks.

#### Current Payment Flow (Cash / Cheque)

```
User clicks "Create Payment" on an invoice
       ↓
AddPaymentModal.php (Livewire) validates input
       ↓
Invoice::getCode() splits payment across tax codes   ← CRITICAL
       ↓
Payment::create() for EACH tax code portion           ← Creates 1-N records
       ↓
PaymentObserver::created() fires                      ← Auto-trigger
       ↓
InvoiceCodeBalanceService::syncForInvoice()           ← Updates balance per code
       ↓
Invoice.pay_status updated (OWING → PART PAID → PAID)
       ↓
InvoicePaid notification sent to regisseur users
```

**Key insight**: A single user payment can create **multiple `Payment` records** — one per tax code on the invoice. Mobile payments must not break this split.

#### Current Payment Types

**Reference**: `app/Enums/PaymentTypeEnums.php`
```php
public const CASH = 'CASH';
public const CHEQUE = 'CHEQUE';
public const DIGI = 'DIGI';      // ← Already exists but NOT implemented
```

**`DIGI` already exists as a payment type.** This is significant — the enum doesn't need changing, and the system already recognizes digital payments as a category. What's missing is the actual provider integration and verification flow.

#### Current Payment Statuses

**Reference**: `app/Enums/PaymentStatusEnums.php`
```php
public const PENDING = 'PENDING';       // Created, awaiting regisseur validation
public const ACCOUNTED = 'ACCOUNTED';   // Validated by regisseur
public const CANCELED = 'CANCELED';     // Annulled
public const DONE = 'DONE';            // Final
```

**Problem**: These statuses don't cover the mobile payment lifecycle (`VERIFYING`, `FAILED`, `EXPIRED`). We need additional statuses — but must not break the existing status logic.

#### Current Key Files

| File | Purpose | Relevance |
|------|---------|-----------|
| `app/Livewire/Payment/AddPaymentModal.php` | Main payment creation UI | **MODIFY** — add DIGI flow |
| `app/Traits/InvoiceTrait.php` | `getCode()` splits payments by tax code | **DO NOT TOUCH** — mobile payments feed into this |
| `app/Observers/PaymentObserver.php` | Triggers balance sync on Payment create/update/delete | **DO NOT TOUCH** — already handles all Payment events |
| `app/Services/InvoiceCodeBalanceService.php` | Recalculates per-code balances | **DO NOT TOUCH** — works via observer |
| `app/Models/Payment.php` | Payment model with fillable, relationships | **MODIFY** — add new fields |
| `app/Enums/PaymentStatusEnums.php` | Payment status constants | **MODIFY** — add VERIFYING, FAILED, EXPIRED |
| `app/Enums/PaymentTypeEnums.php` | Payment type constants | **NO CHANGE** — DIGI already exists |
| `app/Traits/PaymentTrait.php` | `getPaid()`, `getRestToPaid()` calculations | **MODIFY** — exclude VERIFYING/FAILED/EXPIRED from paid amounts |
| `app/Helpers/Constants.php` | App-wide constants | **MODIFY** — add provider constants |
| `app/DataTables/RecoveriesDataTable.php` | Payment listing table | **MODIFY** — show provider, phone, mobile status |
| `config/workflow.php` | Invoice state machine | **NO CHANGE** |

---

### 1.2 Critical Accounting Rules

These rules are **non-negotiable** — mobile payments must respect them:

1. **Split by tax code**: `Invoice::getCode()` distributes payment amount across codes. Mobile payments MUST flow through this same function.
2. **Balance tracking**: `PaymentObserver` → `InvoiceCodeBalanceService` recalculates on every `Payment` create/update/delete. Mobile payments get this for free IF they create `Payment` records normally.
3. **Annulation/Reduction exclusion**: `PaymentTrait::getPaid()` excludes records with `description = 'Annulation'` or `'Réduction'`. Mobile payments must not use these description values.
4. **Regisseur accounting**: Only users with `regisseur` role auto-set `status = ACCOUNTED`. Mobile payments bypass this — they should be `ACCOUNTED` upon provider verification.
5. **COMPTANT invoices**: Auto-fill full amount, non-editable. Mobile payment for COMPTANT must pay the full amount.
6. **InvoiceCodeBalance statuses filter**: `sumPaymentsByCode()` only counts `PENDING`, `ACCOUNTED`, `DONE` statuses. New statuses (`VERIFYING`, `FAILED`, `EXPIRED`) are automatically excluded — this is **correct behavior** (unverified payments shouldn't count toward balance).

---

### 1.3 Existing Sync System

**References**:
- `docs/sync-implementation.md`
- `docs/sync-remote-backend-guide.md`
- `app/Http/Controllers/Api/SyncV1PaymentsController.php`
- `app/Jobs/SyncExportInvoicesJob.php`, `app/Jobs/SyncImportPaymentsJob.php`

The sync system exports invoices and imports/exports payments via a remote backend. Mobile payments must sync correctly:

- `SyncPaymentResource` already transforms `payment_type` — DIGI payments will export as-is
- `PaymentImportService` creates payments by UUID — mobile payments already have UUIDs
- The remote backend accepts types: `CASH`, `CHEQUE`, `DIGI` (per `docs/sync-remote-backend-guide.md`)

**Conclusion**: Sync requires **minimal changes** — just include new fields in the resource transformations.

---

## 2. Impact Assessment — What Gets Modified

### Files That MUST Change

| # | File | Change Type | Risk |
|---|------|-------------|------|
| 1 | `app/Enums/PaymentStatusEnums.php` | Add 3 new statuses | Low |
| 2 | `app/Models/Payment.php` | Add new fillable fields | Low |
| 3 | `app/Traits/PaymentTrait.php` | Exclude new statuses from paid calculations | **High** |
| 4 | `app/Livewire/Payment/AddPaymentModal.php` | Add DIGI flow with provider/phone fields | Medium |
| 5 | `resources/views/livewire/payment/add-payment-modal.blade.php` | Add provider/phone UI | Medium |
| 6 | `app/Helpers/Constants.php` | Add provider constants | Low |
| 7 | `app/DataTables/RecoveriesDataTable.php` | Show mobile payment columns | Low |
| 8 | `app/Http/Resources/SyncPaymentResource.php` | Include new fields | Low |
| 9 | `app/Providers/AppServiceProvider.php` | Register new services | Low |
| 10 | `routes/api.php` | Add mobile payment API endpoint (optional) | Low |

### New Files To Create

| # | File | Purpose |
|---|------|---------|
| 1 | `database/migrations/xxxx_add_mobile_payment_fields_to_payments_table.php` | Add provider, phone_number, external_id, verification_attempts, last_checked_at, expires_at |
| 2 | `database/migrations/xxxx_create_mobile_payment_transactions_table.php` | Dedicated transaction log table |
| 3 | `database/migrations/xxxx_create_sms_logs_table.php` | SMS sending log |
| 4 | `app/Contracts/MobilePaymentProviderInterface.php` | Provider contract |
| 5 | `app/Services/MobilePayment/MobilePaymentService.php` | Orchestration service |
| 6 | `app/Services/MobilePayment/Providers/QosicProvider.php` | QOSIC implementation |
| 7 | `app/Services/MobilePayment/Providers/FedapayProvider.php` | FedaPay implementation |
| 8 | `app/Services/MobilePayment/Providers/PaygateProvider.php` | PayGate implementation |
| 9 | `app/Jobs/VerifyMobilePaymentJob.php` | Retry verification job |
| 10 | `app/Jobs/ExpireStaleMobilePaymentsJob.php` | Cleanup expired transactions |
| 11 | `app/Services/SmsService.php` | SMS sending abstraction |
| 12 | `app/Models/MobilePaymentTransaction.php` | Transaction log model |
| 13 | `app/Models/SmsLog.php` | SMS log model |
| 14 | `app/Events/MobilePaymentVerified.php` | Event for successful verification |
| 15 | `app/Listeners/HandleMobilePaymentVerified.php` | Creates Payment records on success |
| 16 | `app/Http/Requests/InitiateMobilePaymentRequest.php` | Form validation |
| 17 | `config/mobile-payment.php` | Provider credentials & settings |

### Files That MUST NOT Change

| File | Reason |
|------|--------|
| `app/Traits/InvoiceTrait.php` → `getCode()` | Core split logic — mobile payments call this AFTER verification |
| `app/Observers/PaymentObserver.php` | Already handles all Payment events automatically |
| `app/Services/InvoiceCodeBalanceService.php` | Triggered by observer — works out of the box |
| `config/workflow.php` | Invoice state machine unrelated to payment method |
| `app/Guards/InvoiceGuard.php` | Invoice transitions unrelated |

---

## 3. Architecture Decision: Extend vs. Parallel

### Option A: Extend `AddPaymentModal` (Recommended ✅)

Add a "Digital" tab/section to the existing payment modal. When user selects `DIGI`, show provider/phone fields. On submit, initiate mobile payment flow instead of direct `Payment::create()`.

**Pros**:
- Single entry point for all payment methods
- Reuses existing invoice selection, code splitting, validation
- Users don't learn a new workflow
- Existing `canGetPayment()`, permission checks, COMPTANT logic all apply

**Cons**:
- More complex modal logic
- Must handle async state (user waits for verification)

### Option B: Separate Livewire Component

Create `AddMobilePaymentModal.php` as a standalone component.

**Pros**:
- Clean separation
- No risk to existing payment flow

**Cons**:
- Duplicates invoice lookup, validation, permission checks
- Two different UI entry points for payments
- Must sync with existing pay_status logic separately

### Decision: **Option A with a dedicated Service layer**

The modal handles UI concerns. A new `MobilePaymentService` handles all provider logic, verification scheduling, and Payment record creation. This keeps the modal thin and the business logic testable.

---

## 4. Database Changes

### 4.1 Migration: Add Mobile Fields to `payments` Table

```php
// database/migrations/2026_03_28_000001_add_mobile_payment_fields_to_payments_table.php

Schema::table('payments', function (Blueprint $table) {
    $table->string('provider')->nullable()->after('payment_type');
    // e.g., 'qosic', 'fedapay', 'paygate'

    $table->string('phone_number')->nullable()->after('provider');
    // Payer's phone number

    $table->string('external_id')->nullable()->after('phone_number');
    // Provider's transaction reference

    $table->unsignedSmallInteger('verification_attempts')->default(0)->after('external_id');

    $table->timestamp('last_checked_at')->nullable()->after('verification_attempts');

    $table->timestamp('expires_at')->nullable()->after('last_checked_at');
    // Auto-expire if not verified within window
});
```

**Why on `payments` table?** Because the existing `PaymentObserver`, `PaymentTrait::getPaid()`, `InvoiceCodeBalanceService`, DataTables, and sync resources all query the `payments` table. A separate table would require rewriting all of these.

### 4.2 Migration: `mobile_payment_transactions` (Audit Log)

```php
// database/migrations/2026_03_28_000002_create_mobile_payment_transactions_table.php

Schema::create('mobile_payment_transactions', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique();         // Our UUID reference
    $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
    $table->foreignId('taxpayer_id')->nullable()->constrained();
    $table->decimal('amount', 15, 2);
    $table->string('phone_number');
    $table->string('provider');                     // qosic, fedapay, paygate
    $table->string('external_id')->nullable();      // Provider's ID
    $table->string('status')->default('pending');
    // pending → verifying → success → [creates Payment records]
    // pending → verifying → failed
    // pending → expired
    $table->unsignedSmallInteger('verification_attempts')->default(0);
    $table->timestamp('last_checked_at')->nullable();
    $table->timestamp('verified_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->json('provider_response')->nullable();  // Raw API responses
    $table->json('meta')->nullable();               // code, payment splits, etc.
    $table->foreignId('user_id')->nullable()->constrained();
    $table->timestamps();

    $table->index(['invoice_id', 'status']);
    $table->index(['status', 'expires_at']);
    $table->index('provider');
});
```

**Why a separate table?** The `payments` table drives accounting. We don't want partially-verified mobile transactions polluting it. The flow is:

```
MobilePaymentTransaction (pending/verifying)
       ↓  on SUCCESS
Payment::create() via Invoice::getCode()    ← enters accounting pipeline
       ↓
PaymentObserver → InvoiceCodeBalanceService  ← automatic
```

### 4.3 Migration: `sms_logs` Table

```php
Schema::create('sms_logs', function (Blueprint $table) {
    $table->id();
    $table->string('phone_number');
    $table->text('message');
    $table->string('provider')->nullable();
    $table->string('status')->default('pending');  // pending, sent, failed
    $table->string('external_id')->nullable();
    $table->json('meta')->nullable();
    $table->morphs('loggable');   // polymorphic: can relate to Payment, Invoice, etc.
    $table->timestamps();
});
```

### 4.4 Add to `modules` Table (if exists) or Feature Config

Toggle SMS via `config/features.php` (already has feature flags pattern):

```php
// config/features.php — add:
'sms_notifications' => env('FEATURE_SMS_NOTIFICATIONS', false),
'mobile_payment' => env('FEATURE_MOBILE_PAYMENT', false),
```

---

## 5. Backend Implementation Plan

### 5.1 Provider Interface

**File**: `app/Contracts/MobilePaymentProviderInterface.php`

```php
<?php

namespace App\Contracts;

interface MobilePaymentProviderInterface
{
    /**
     * Initiate a payment request to the provider.
     *
     * @param array{
     *   reference: string,
     *   amount: float,
     *   phone_number: string,
     *   description: string
     * } $data
     *
     * @return array{success: bool, external_id: ?string, message: ?string, raw: array}
     */
    public function initiate(array $data): array;

    /**
     * Verify transaction status with the provider.
     *
     * @return array{
     *   status: string,         // 'pending'|'success'|'failed'
     *   external_id: ?string,
     *   amount: ?float,
     *   raw: array
     * }
     */
    public function verify(string $reference): array;

    /**
     * Get the provider identifier.
     */
    public function getName(): string;
}
```

### 5.2 MobilePaymentService (Orchestrator)

**File**: `app/Services/MobilePayment/MobilePaymentService.php`

This is the central service that:
1. Creates a `MobilePaymentTransaction` record
2. Calls the provider's `initiate()` method
3. Dispatches `VerifyMobilePaymentJob`
4. On verified success: calls `Invoice::getCode()` → `Payment::create()` (entering the existing accounting pipeline)

```
MobilePaymentService::initiate($invoiceId, $amount, $phone, $provider, $userId)
  → Create MobilePaymentTransaction (status: pending)
  → Call provider->initiate()
  → DO NOT trust response
  → Update transaction (status: verifying, external_id from response)
  → Dispatch VerifyMobilePaymentJob with backoff [10, 30, 60, 120, 300]
  → Return transaction reference to UI

MobilePaymentService::handleVerificationSuccess($transaction)
  → Load invoice
  → Build paymentData array (same structure as AddPaymentModal)
  → Call Invoice::getCode() to split by tax code     ← REUSES existing split logic
  → Payment::create() for each split                  ← Triggers PaymentObserver automatically
  → Update invoice.pay_status
  → Fire MobilePaymentVerified event
  → Send notifications
```

**Critical**: The `handleVerificationSuccess()` method MUST replicate the exact logic from `AddPaymentModal::submit()` lines 99-139. This includes:
- Calling `Invoice::getCode($invoice_no, $amount, $paymentData)`
- Setting `status = ACCOUNTED` (mobile payments are pre-verified)
- Updating `invoice.pay_status` based on `paid + amount >= bill`
- NOT sending `InvoicePaid` notification to regisseur (mobile payments are self-verified)

### 5.3 Provider Implementations

Each provider follows the same interface:

**File**: `app/Services/MobilePayment/Providers/QosicProvider.php`

```php
class QosicProvider implements MobilePaymentProviderInterface
{
    public function __construct(
        private readonly string $apiUrl,
        private readonly string $apiKey,
        private readonly string $clientId,
    ) {}

    public function initiate(array $data): array
    {
        // POST to QOSIC API
        // Return {success, external_id, message, raw}
    }

    public function verify(string $reference): array
    {
        // GET transaction status from QOSIC
        // Return {status: pending|success|failed, external_id, amount, raw}
    }

    public function getName(): string
    {
        return 'qosic';
    }
}
```

Repeat for `FedapayProvider.php` and `PaygateProvider.php`.

### 5.4 Provider Factory

**File**: `app/Services/MobilePayment/MobilePaymentProviderFactory.php`

```php
class MobilePaymentProviderFactory
{
    public function make(string $provider): MobilePaymentProviderInterface
    {
        return match ($provider) {
            'qosic'   => app(QosicProvider::class),
            'fedapay'  => app(FedapayProvider::class),
            'paygate'  => app(PaygateProvider::class),
            default    => throw new \InvalidArgumentException("Unknown provider: {$provider}"),
        };
    }
}
```

---

## 6. Provider Integration Layer

### 6.1 Configuration

**File**: `config/mobile-payment.php`

```php
return [
    'default_provider' => env('MOBILE_PAYMENT_DEFAULT_PROVIDER', 'qosic'),

    'expiration_minutes' => env('MOBILE_PAYMENT_EXPIRATION', 30),

    'verification' => [
        'max_attempts' => 5,
        'backoff_seconds' => [10, 30, 60, 120, 300],
    ],

    'providers' => [
        'qosic' => [
            'api_url'   => env('QOSIC_API_URL'),
            'api_key'   => env('QOSIC_API_KEY'),
            'client_id' => env('QOSIC_CLIENT_ID'),
            'enabled'   => env('QOSIC_ENABLED', false),
        ],
        'fedapay' => [
            'api_url'    => env('FEDAPAY_API_URL'),
            'api_key'    => env('FEDAPAY_API_KEY'),
            'secret_key' => env('FEDAPAY_SECRET_KEY'),
            'enabled'    => env('FEDAPAY_ENABLED', false),
        ],
        'paygate' => [
            'api_url'  => env('PAYGATE_API_URL'),
            'api_key'  => env('PAYGATE_API_KEY'),
            'enabled'  => env('PAYGATE_ENABLED', false),
        ],
    ],
];
```

### 6.2 Environment Variables

```env
# Mobile Payment Feature
FEATURE_MOBILE_PAYMENT=true

# Default provider
MOBILE_PAYMENT_DEFAULT_PROVIDER=qosic
MOBILE_PAYMENT_EXPIRATION=30

# QOSIC
QOSIC_ENABLED=true
QOSIC_API_URL=https://api.qosic.com/v1
QOSIC_API_KEY=
QOSIC_CLIENT_ID=

# FedaPay
FEDAPAY_ENABLED=false
FEDAPAY_API_URL=https://api.fedapay.com/v1
FEDAPAY_API_KEY=
FEDAPAY_SECRET_KEY=

# PayGate
PAYGATE_ENABLED=false
PAYGATE_API_URL=https://api.paygate.africa/v1
PAYGATE_API_KEY=

# SMS
FEATURE_SMS_NOTIFICATIONS=false
SMS_PROVIDER=
SMS_API_KEY=
```

---

## 7. Verification Job System

### 7.1 VerifyMobilePaymentJob

**File**: `app/Jobs/VerifyMobilePaymentJob.php`

```php
class VerifyMobilePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public array $backoff = [10, 30, 60, 120, 300];
    public int $timeout = 30;

    public function __construct(
        private readonly int $transactionId,
    ) {}

    public function handle(MobilePaymentService $service): void
    {
        $transaction = MobilePaymentTransaction::find($this->transactionId);

        if (!$transaction || $transaction->isTerminal()) {
            return; // Already resolved
        }

        if ($transaction->isExpired()) {
            $service->markExpired($transaction);
            return;
        }

        $result = $service->verifyWithProvider($transaction);

        match ($result['status']) {
            'success' => $service->handleVerificationSuccess($transaction),
            'failed'  => $service->handleVerificationFailure($transaction),
            'pending' => $this->handleStillPending($transaction),
        };
    }

    private function handleStillPending(MobilePaymentTransaction $transaction): void
    {
        $transaction->increment('verification_attempts');
        $transaction->update(['last_checked_at' => now()]);

        if ($this->attempts() >= $this->tries) {
            // Max retries reached, mark as failed
            $transaction->update(['status' => 'failed']);
        }
        // Otherwise, Laravel's backoff will retry automatically
    }

    public function failed(\Throwable $exception): void
    {
        $transaction = MobilePaymentTransaction::find($this->transactionId);
        if ($transaction && !$transaction->isTerminal()) {
            $transaction->update(['status' => 'failed']);
        }

        Log::channel(config('sync.log_channel', 'sync'))
            ->error("Mobile payment verification failed", [
                'transaction_id' => $this->transactionId,
                'error' => $exception->getMessage(),
            ]);
    }
}
```

### 7.2 ExpireStaleMobilePaymentsJob

**File**: `app/Jobs/ExpireStaleMobilePaymentsJob.php`

Runs on scheduler (every 5 minutes) to expire transactions past their `expires_at` window:

```php
class ExpireStaleMobilePaymentsJob implements ShouldQueue
{
    public function handle(): void
    {
        MobilePaymentTransaction::query()
            ->whereIn('status', ['pending', 'verifying'])
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);
    }
}
```

Register in `app/Console/Kernel.php`:

```php
$schedule->job(new ExpireStaleMobilePaymentsJob)->everyFiveMinutes();
```

---

## 8. UI Changes

### 8.1 AddPaymentModal Modifications

**File**: `app/Livewire/Payment/AddPaymentModal.php`

Current `payment_type` is a simple dropdown (`CASH`, `CHEQUE`, `DIGI`). Changes:

1. **Add properties**:
```php
public $phone_number;
public $provider;
public bool $is_mobile_payment = false;
public $mobile_transaction_ref;
public $mobile_payment_status;
```

2. **Update `rules()`** — add conditional validation:
```php
if ($this->payment_type === PaymentTypeEnums::DIGI) {
    $rules['phone_number'] = ['required', 'string', (new Phone())->country(['TG', 'GH', 'BJ'])];
    $rules['provider'] = ['required', Rule::in(['qosic', 'fedapay', 'paygate'])];
}
```

3. **Modify `submit()`** — branch on payment type:
```php
if ($this->payment_type === PaymentTypeEnums::DIGI) {
    // Do NOT create Payment records yet
    // Instead: call MobilePaymentService::initiate()
    $service = app(MobilePaymentService::class);
    $transaction = $service->initiate(
        invoiceId: $this->invoice_id,
        invoiceNo: $this->invoice_no,
        amount: $this->amount,
        phoneNumber: $this->phone_number,
        provider: $this->provider,
        taxpayerId: $this->taxpayer_id,
        userId: Auth::id(),
        code: $this->code,
        paymentData: $paymentData, // Same structure as before
    );

    $this->mobile_transaction_ref = $transaction->reference;
    $this->mobile_payment_status = 'verifying';
    $this->dispatch('mobile-payment-initiated', reference: $transaction->reference);
    return; // Don't create Payment records — the job will do it on success
}

// Existing CASH/CHEQUE flow continues unchanged below...
```

### 8.2 Blade View Changes

**File**: `resources/views/livewire/payment/add-payment-modal.blade.php`

Add after the `payment_type` select:

```blade
{{-- Show provider/phone when DIGI is selected --}}
@if($payment_type === 'DIGI')
    <div class="fv-row mb-7">
        <label class="required fw-semibold fs-6 mb-2">{{ __('Provider') }}</label>
        <select wire:model="provider" class="form-select form-select-solid">
            <option value="">{{ __('Sélectionner un opérateur') }}</option>
            @if(config('mobile-payment.providers.qosic.enabled'))
                <option value="qosic">QOSIC (TMoney/Flooz)</option>
            @endif
            @if(config('mobile-payment.providers.fedapay.enabled'))
                <option value="fedapay">FedaPay</option>
            @endif
            @if(config('mobile-payment.providers.paygate.enabled'))
                <option value="paygate">PayGate</option>
            @endif
        </select>
        @error('provider') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="fv-row mb-7">
        <label class="required fw-semibold fs-6 mb-2">{{ __('Numéro de téléphone') }}</label>
        <input type="tel" wire:model="phone_number" class="form-control form-control-solid"
               placeholder="+228 XX XX XX XX" />
        @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
@endif

{{-- Verification status feedback --}}
@if($mobile_payment_status === 'verifying')
    <div class="alert alert-warning d-flex align-items-center">
        <span class="spinner-border spinner-border-sm me-3"></span>
        {{ __('Paiement en cours de vérification...') }}
        <br><small>Réf: {{ $mobile_transaction_ref }}</small>
    </div>
@elseif($mobile_payment_status === 'success')
    <div class="alert alert-success">
        {{ __('Paiement vérifié avec succès!') }}
    </div>
@elseif($mobile_payment_status === 'failed')
    <div class="alert alert-danger">
        {{ __('Le paiement a échoué. Veuillez réessayer.') }}
    </div>
@endif
```

### 8.3 Real-Time Status Updates (Livewire Polling)

For verification feedback, the modal can poll for status:

```php
// In AddPaymentModal.php:
public function checkMobilePaymentStatus()
{
    if (!$this->mobile_transaction_ref) return;

    $transaction = MobilePaymentTransaction::where('reference', $this->mobile_transaction_ref)->first();
    if ($transaction) {
        $this->mobile_payment_status = $transaction->status;
        if (in_array($transaction->status, ['success', 'failed', 'expired'])) {
            $this->dispatch('mobile-payment-resolved', status: $transaction->status);
        }
    }
}
```

In the blade, add polling when verifying:

```blade
@if($mobile_payment_status === 'verifying')
    <div wire:poll.5s="checkMobilePaymentStatus">
        ...
    </div>
@endif
```

---

## 9. SMS Module

### 9.1 Architecture

```
SmsService (abstraction)
  → SmsProviderInterface
     → TwilioSmsProvider / InfobipSmsProvider / LocalSmsGateway

Toggle: config('features.sms_notifications')
```

### 9.2 Integration Points

SMS should be sent at these events:

| Event | Message | To |
|-------|---------|-----|
| Mobile payment initiated | "Paiement de {amount} FCFA initié. Réf: {ref}" | Payer phone |
| Payment verified (success) | "Paiement de {amount} FCFA confirmé pour avis {invoice_no}" | Payer phone |
| Payment failed | "Paiement de {amount} FCFA échoué. Réf: {ref}" | Payer phone |

### 9.3 Feature Toggle

Guard all SMS sends:

```php
if (config('features.sms_notifications')) {
    app(SmsService::class)->send($phone, $message);
}
```

---

## 10. Sync Impact

### 10.1 SyncPaymentResource Changes

**File**: `app/Http/Resources/SyncPaymentResource.php`

Add new fields to the transformation:

```php
return [
    // ... existing fields
    'provider'    => $this->provider,
    'phone_number' => $this->phone_number,
    'external_id'  => $this->external_id,
];
```

### 10.2 PaymentImportService Changes

When importing payments from the remote backend, handle the new fields:

```php
// In import logic, map new fields if present:
'provider'    => $paymentData['provider'] ?? null,
'phone_number' => $paymentData['phone_number'] ?? null,
'external_id'  => $paymentData['external_id'] ?? null,
```

### 10.3 Remote Backend Communication

The remote backend guide (`docs/sync-remote-backend-guide.md`) states valid payment types include `DIGI`. The remote backend should be informed of the new fields but they are all **nullable** — backward compatible.

---

## 11. Offline / Low-Connectivity Strategy

The prompt specifies some communes operate in **low or unstable internet environments**. This is the most challenging aspect.

### 11.1 Problem

Mobile payments require internet to:
1. Initiate the payment with the provider
2. Verify the transaction status

### 11.2 Strategy

| Scenario | Handling |
|----------|---------|
| **Internet available** | Normal flow: initiate → verify → create Payment |
| **Internet drops after initiation** | Verification job retries with exponential backoff (up to 5 min delay). Transaction stays in `verifying` state. |
| **Internet drops during initiation** | API call fails → show error to user, suggest retry or use CASH |
| **Prolonged outage** | `ExpireStaleMobilePaymentsJob` marks transactions as `expired` after 30 min. No phantom payments. |
| **Internet restored** | Pending verification jobs in the queue resume automatically |

### 11.3 Key Design Rule

> **Never create a `Payment` record until verification succeeds.**

This ensures the accounting ledger is always clean. `MobilePaymentTransaction` is the staging area. Only verified transactions graduate to `Payment`.

### 11.4 UI Resilience

- The modal should save the transaction reference locally (browser `localStorage`) so the user can check status later
- A new "Paiements Mobiles en Attente" section in the dashboard could show pending/verifying transactions

---

## 12. File-by-File Change Manifest

### Modified Files (Existing)

| # | File | Changes |
|---|------|---------|
| 1 | **`app/Enums/PaymentStatusEnums.php`** | Add `VERIFYING = 'VERIFYING'`, `FAILED = 'FAILED'`, `EXPIRED = 'EXPIRED'` |
| 2 | **`app/Models/Payment.php`** | Add to `$fillable`: `provider`, `phone_number`, `external_id`, `verification_attempts`, `last_checked_at`, `expires_at`. Add `$casts` for `last_checked_at`, `expires_at`. Add `provider()` relationship if needed |
| 3 | **`app/Traits/PaymentTrait.php`** | In `getPaid()` and `getPaidNotAccounted()`: exclude `VERIFYING`, `FAILED`, `EXPIRED` from status filters. These methods use `whereIn(['PENDING', 'ACCOUNTED', 'DONE'])` — verify and add explicit exclusion as safety |
| 4 | **`app/Livewire/Payment/AddPaymentModal.php`** | Add `phone_number`, `provider`, `is_mobile_payment`, `mobile_transaction_ref`, `mobile_payment_status` properties. Add conditional rules for DIGI. Branch `submit()` for mobile flow. Add `checkMobilePaymentStatus()` polling method |
| 5 | **`resources/views/livewire/payment/add-payment-modal.blade.php`** | Add provider dropdown, phone input (conditional on DIGI), verification status feedback with polling |
| 6 | **`app/Helpers/Constants.php`** | Add `PROVIDER_QOSIC`, `PROVIDER_FEDAPAY`, `PROVIDER_PAYGATE` constants |
| 7 | **`app/DataTables/RecoveriesDataTable.php`** | Add `provider`, `phone_number` columns. Show mobile payment indicator |
| 8 | **`app/Http/Resources/SyncPaymentResource.php`** | Add `provider`, `phone_number`, `external_id` to transformation |
| 9 | **`app/Providers/AppServiceProvider.php`** | Register `MobilePaymentService`, `MobilePaymentProviderFactory`. Bind provider implementations |
| 10 | **`app/Console/Kernel.php`** | Register `ExpireStaleMobilePaymentsJob` on schedule |
| 11 | **`config/features.php`** | Add `mobile_payment` and `sms_notifications` feature flags |
| 12 | **`.env.example`** | Add all mobile payment and SMS environment variables |

### New Files

| # | File | Type | Purpose |
|---|------|------|---------|
| 1 | `database/migrations/2026_03_28_000001_add_mobile_payment_fields_to_payments_table.php` | Migration | Add provider, phone, external_id, verification fields to payments |
| 2 | `database/migrations/2026_03_28_000002_create_mobile_payment_transactions_table.php` | Migration | Staging table for mobile transactions |
| 3 | `database/migrations/2026_03_28_000003_create_sms_logs_table.php` | Migration | SMS audit log |
| 4 | `config/mobile-payment.php` | Config | Provider credentials, verification settings, timeouts |
| 5 | `app/Contracts/MobilePaymentProviderInterface.php` | Interface | Provider contract: `initiate()`, `verify()`, `getName()` |
| 6 | `app/Services/MobilePayment/MobilePaymentService.php` | Service | Orchestrates: initiate, verify, handle success/failure, create Payment records |
| 7 | `app/Services/MobilePayment/MobilePaymentProviderFactory.php` | Factory | Resolves provider by name |
| 8 | `app/Services/MobilePayment/Providers/QosicProvider.php` | Provider | QOSIC API integration |
| 9 | `app/Services/MobilePayment/Providers/FedapayProvider.php` | Provider | FedaPay API integration |
| 10 | `app/Services/MobilePayment/Providers/PaygateProvider.php` | Provider | PayGate API integration |
| 11 | `app/Models/MobilePaymentTransaction.php` | Model | Eloquent model for `mobile_payment_transactions` |
| 12 | `app/Models/SmsLog.php` | Model | Eloquent model for `sms_logs` |
| 13 | `app/Jobs/VerifyMobilePaymentJob.php` | Job | Retries verification with backoff [10,30,60,120,300]s |
| 14 | `app/Jobs/ExpireStaleMobilePaymentsJob.php` | Job | Scheduled cleanup of stale transactions |
| 15 | `app/Events/MobilePaymentVerified.php` | Event | Fired on successful verification |
| 16 | `app/Listeners/HandleMobilePaymentVerified.php` | Listener | Optional: send SMS, log, notify |
| 17 | `app/Http/Requests/InitiateMobilePaymentRequest.php` | Request | Form validation for mobile payment input |
| 18 | `app/Services/SmsService.php` | Service | SMS sending abstraction |
| 19 | `tests/Feature/MobilePayment/MobilePaymentFlowTest.php` | Test | End-to-end flow test |
| 20 | `tests/Feature/MobilePayment/VerifyMobilePaymentJobTest.php` | Test | Verification job retry/backoff test |

---

## 13. Risk Analysis

### 13.1 High-Risk Areas

| Risk | Mitigation |
|------|------------|
| **Breaking `getPaid()` calculations** | New statuses (`VERIFYING`, `FAILED`, `EXPIRED`) are NOT in the `whereIn(['PENDING', 'ACCOUNTED', 'DONE'])` filter — they're automatically excluded. But add explicit tests to verify. |
| **Double payment** (race condition) | Use `MobilePaymentTransaction.reference` uniqueness + check for existing pending transaction for same invoice before initiating |
| **Observer side effects** | `PaymentObserver` fires on `Payment::create()`. Mobile payments only create Payment records AFTER verification — observer sees normal records. No risk. |
| **InvoiceCodeBalance desync** | Observer triggers `syncForInvoice()` on every Payment change. Mobile Payment records are created via `Invoice::getCode()` → `Payment::create()` — same path as cash. No risk. |
| **COMPTANT edge case** | COMPTANT invoices auto-fill full amount. Mobile payment for COMPTANT must enforce this. Add check in `MobilePaymentService::initiate()`. |
| **Concurrent payments** | Two users paying same invoice simultaneously. Add optimistic lock: check `invoice.pay_status !== 'PAID'` and `amount + paid <= bill` before creating Payment records in `handleVerificationSuccess()`. Wrap in `DB::transaction()` with `lockForUpdate()`. |

### 13.2 Low-Risk Areas

| Area | Why |
|------|-----|
| Database migrations | All new columns are nullable. Existing data unaffected. |
| Sync system | New fields are nullable. Remote backend sees standard DIGI payments. |
| Workflow state machine | Completely unrelated to payment method. |
| DataTables | Adding columns is additive — existing columns unchanged. |

---

## 14. Implementation Order

### Phase 1: Foundation (No UI changes yet)

```
1. Create config/mobile-payment.php
2. Update config/features.php
3. Create migrations (payments fields, mobile_payment_transactions, sms_logs)
4. Run migrations
5. Create MobilePaymentTransaction model
6. Create MobilePaymentProviderInterface
7. Create MobilePaymentProviderFactory
8. Create provider stubs (QosicProvider, FedapayProvider, PaygateProvider)
9. Create MobilePaymentService (initiate, verify, handleSuccess, handleFailure)
10. Create VerifyMobilePaymentJob
11. Create ExpireStaleMobilePaymentsJob
12. Register services in AppServiceProvider
13. Register scheduler in Kernel.php
14. Update PaymentStatusEnums (add VERIFYING, FAILED, EXPIRED)
15. Update Payment model ($fillable, $casts)
```

### Phase 2: Core Integration

```
16. Update PaymentTrait (safety check on getPaid exclusions)
17. Write MobilePaymentFlowTest — test the complete flow with mocked provider
18. Write VerifyMobilePaymentJobTest — test retry/backoff/expiration
19. Verify InvoiceCodeBalanceService works correctly with mobile-created payments
```

### Phase 3: UI

```
20. Modify AddPaymentModal.php (add DIGI branch)
21. Modify add-payment-modal.blade.php (provider/phone UI, status polling)
22. Update RecoveriesDataTable (add mobile payment columns)
23. Manual testing of full UI flow
```

### Phase 4: SMS & Polish

```
24. Create SmsService
25. Create SmsLog model
26. Integrate SMS sends at verification events
27. Update SyncPaymentResource
28. Update .env.example
29. Documentation
```

### Phase 5: Provider-Specific Implementation

```
30. Implement QosicProvider with real API calls
31. Implement FedapayProvider with real API calls
32. Implement PaygateProvider with real API calls
33. End-to-end testing with sandbox accounts
```

---

## Appendix A: Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        USER INTERFACE                            │
│                                                                  │
│  AddPaymentModal                                                 │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────┐              │
│  │  CASH    │  │  CHEQUE  │  │  DIGI (Mobile)   │              │
│  └────┬─────┘  └────┬─────┘  └────────┬─────────┘              │
│       │              │                  │                        │
│       └──────┬───────┘                  │                        │
│              │                          │                        │
│     Direct Payment::create()    MobilePaymentService             │
│              │                 ::initiate()                       │
│              │                          │                        │
└──────────────┼──────────────────────────┼────────────────────────┘
               │                          │
               │                          ▼
               │              ┌──────────────────────┐
               │              │ MobilePaymentTransaction │
               │              │   status: pending       │
               │              └──────────┬───────────┘
               │                         │
               │                         ▼
               │              ┌──────────────────────┐
               │              │ Provider API Call     │
               │              │ (QOSIC/FedaPay/PG)   │
               │              └──────────┬───────────┘
               │                         │
               │                         ▼
               │              ┌──────────────────────┐
               │              │ VerifyMobilePayment   │
               │              │ Job (5 retries)       │
               │              └──────────┬───────────┘
               │                         │
               │                    SUCCESS?
               │                   ╱        ╲
               │                 YES         NO → mark failed
               │                  │
               │                  ▼
               │         ┌────────────────────┐
               │         │ Invoice::getCode() │  ← SAME as cash flow
               │         │ (split by tax code)│
               │         └────────┬───────────┘
               │                  │
               ▼                  ▼
    ┌─────────────────────────────────────┐
    │        Payment::create()             │  ← 1 to N records per code
    │        (enters accounting pipeline)  │
    └──────────────────┬──────────────────┘
                       │
                       ▼
    ┌─────────────────────────────────────┐
    │      PaymentObserver::created()      │  ← AUTOMATIC (existing)
    └──────────────────┬──────────────────┘
                       │
                       ▼
    ┌─────────────────────────────────────┐
    │ InvoiceCodeBalanceService            │  ← AUTOMATIC (existing)
    │ ::syncForInvoice()                   │
    └──────────────────┬──────────────────┘
                       │
                       ▼
    ┌─────────────────────────────────────┐
    │ Invoice.pay_status updated           │
    │ (OWING → PART PAID → PAID)          │
    └─────────────────────────────────────┘
```

---

## Appendix B: Status Mapping

### MobilePaymentTransaction Statuses

```
PENDING ─────→ VERIFYING ──→ SUCCESS ──→ Payment::create() ──→ Accounting
                    │
                    ├────────→ FAILED (provider declined / max retries)
                    │
                    └────────→ EXPIRED (expires_at passed)
```

### Payment Statuses (Extended)

```
Existing:                    New (mobile-specific):
  PENDING  ──→ ACCOUNTED      VERIFYING (waiting for provider)
  PENDING  ──→ CANCELED       FAILED    (provider declined)
  ACCOUNTED ──→ DONE          EXPIRED   (timeout)
```

**Note**: `VERIFYING`, `FAILED`, `EXPIRED` only appear on Payment records if you decide to create payment records immediately (Option B). In the recommended architecture, these statuses live on `MobilePaymentTransaction` and Payment records are only created with status `ACCOUNTED` after verification.

---

## Appendix C: Key Code References

| Concept | File | Method/Line | Notes |
|---------|------|-------------|-------|
| Payment split by tax code | `app/Traits/InvoiceTrait.php` | `getCode()` | Creates array of payment data per code |
| Sum paid amounts | `app/Traits/PaymentTrait.php` | `getPaid()` | Excludes annulations, filters by status |
| Balance calculation | `app/Services/InvoiceCodeBalanceService.php` | `syncForInvoice()` | Uses `sumPaymentsByCode()` — only counts PENDING/ACCOUNTED/DONE |
| Observer trigger | `app/Observers/PaymentObserver.php` | `created()`, `updated()`, `deleted()` | Calls `syncForInvoice()` on related invoice |
| Invoice pay_status update | `app/Livewire/Payment/AddPaymentModal.php` | `submit()` ~line 130 | `$paystatus = $this->amount + $this->paid >= $this->bill ? "PAID" : "PART PAID"` |
| COMPTANT enforcement | `app/Livewire/Payment/AddPaymentModal.php` | `render()` ~line 88 | COMPTANT auto-fills amount, sets `edit_amount = false` |
| DIGI type exists | `app/Enums/PaymentTypeEnums.php` | `DIGI = 'DIGI'` | Already defined, not yet implemented |
| Regisseur auto-account | `app/Livewire/Payment/AddPaymentModal.php` | `submit()` ~line 122 | Sets `status = ACCOUNTED` if user has regisseur role |
| Sync export payments | `app/Http/Resources/SyncPaymentResource.php` | `toArray()` | Transforms payment for sync API |
| Sync import payments | `app/Http/Controllers/Api/SyncV1PaymentsController.php` | `store()` | Creates/updates payments by UUID |
| Feature flags pattern | `config/features.php` | — | Environment-gated booleans |

---

*End of analysis document*
