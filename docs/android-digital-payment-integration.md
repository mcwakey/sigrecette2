# Android Mobile Digital Payment Integration — Complete Backend Analysis

> **Purpose:** This document provides everything needed for Android Studio Copilot to implement digital (mobile money) payment in the SigRecette mobile app without breaking any existing workflow.
> 
> **Architecture:** The mobile app integrates **directly** with payment providers (PayGate, QOSIC, FedaPay) — the local backend server is NOT required to be online during payment. Completed DIGI payments are synced to the backend during the normal sync cycle, exactly like CASH/CHEQUE payments.

---

## TABLE OF CONTENTS

1. [System Overview](#1-system-overview)
2. [Current Sync Architecture (Mobile ↔ Backend)](#2-current-sync-architecture)
3. [Data Models & DB Schema](#3-data-models--db-schema)
4. [Existing Payment Flow (Cash/Cheque — via SyncIn)](#4-existing-payment-flow)
5. [Digital Payment Flow (Backend — Already Implemented)](#5-digital-payment-flow-backend)
6. [API Endpoints Reference](#6-api-endpoints-reference)
7. [Enums & Constants](#7-enums--constants)
8. [Android Implementation Plan](#8-android-implementation-plan)
9. [Critical Rules — DO NOT BREAK](#9-critical-rules--do-not-break)
10. [Sequence Diagrams](#10-sequence-diagrams)
11. [Error Handling](#11-error-handling)
12. [Testing Checklist](#12-testing-checklist)

---

## 1. SYSTEM OVERVIEW

SigRecette is a tax collection system with:
- **Laravel Backend** — manages taxpayers, invoices, payments, tax codes
- **Android Mobile App** — used by field agents to collect payments offline, then sync
- **Remote Server** — central server that syncs with local backend

### Payment Types
| Type | Enum Value | How It Works |
|------|-----------|--------------|
| Cash | `CASH` | Collected offline → synced via `POST /v1/synchronisation/in` |
| Cheque | `CHEQUE` | Collected offline → synced via `POST /v1/synchronisation/in` |
| Digital (Mobile Money) | `DIGI` | **NEW** — Mobile calls provider API directly → verifies → stores locally → synced via `POST /v1/synchronisation/in` |

### Key Principle
**Digital payments are handled DIRECTLY by the mobile app.** The local server is not online during payment collection. The mobile app calls the payment provider APIs (PayGate, QOSIC, FedaPay) directly, verifies the transaction, stores the confirmed payment locally, and syncs it to the backend during the regular sync cycle — just like CASH/CHEQUE payments.

---

## 2. CURRENT SYNC ARCHITECTURE

### Sync Out (Backend → Mobile)
**Endpoint:** `POST /v1/synchronisation/out`
**Auth:** Sanctum Bearer Token + IP whitelist
**Request:**
```json
{
  "zone": "ZONE_NAME",
  "category": "CATEGORY 1"
}
```
**Response:** Returns ALL data for a zone:
```json
{
  "zones": [...],
  "activities": [...],
  "categories": [...],
  "ereas": [...],
  "towns": [...],
  "genders": [...],
  "id_types": [...],
  "taxlabels": [...],
  "taxables": [...],
  "taxpayers": [...],
  "taxpayer_taxables": [...],
  "invoices": [...],
  "invoice_items": [...],
  "payments": [...]
}
```

### Payment Object from Sync Out (what mobile currently receives)
```json
{
  "id": 100042,
  "taxpayerId": 10024,
  "invoiceId": "INV-2026-001",
  "description": "Avis INV-2026-001",
  "reference": "REF-001",
  "amount": 5000,
  "remainingAmount": 15000,
  "paymentType": "CASH",
  "createdAt": "2026-03-15T10:30:00Z",
  "userId": 1,
  "status": "PENDING"
}
```
**Note:** Keys are camelCase in the mobile response. The `invoiceId` here is actually `invoice_no` (string like "INV-2026-001"), NOT the numeric ID.

### Sync In (Mobile → Backend)
**Endpoint:** `POST /v1/synchronisation/in`
**Auth:** Sanctum Bearer Token + IP whitelist
**Request structure:**
```json
{
  "data": [
    [
      [
        {
          "_id": 10024,
          "userId": 1,
          "dataStatus": "new|existing",
          "name": "...",
          "taxpayerTaxables": [
            {
              "_id": null,
              "dataStatus": "new",
              "taxpayerId": 10024,
              "taxableId": 5,
              "name": "..."
            }
          ],
          "invoices": [
            {
              "_id": 100101,
              "payStatus": "PART PAID"
            }
          ],
          "payments": [
            {
              "_id": null,
              "dataStatus": "new",
              "amount": 5000,
              "paymentType": "CASH",
              "invoiceId": "INV-2026-001",
              "taxpayerId": 10024,
              "userId": 1,
              "status": "PENDING",
              "remainingAmount": 15000,
              "description": "Avis INV-2026-001",
              "reference": "REF-001"
            }
          ]
        }
      ]
    ]
  ]
}
```

**How SyncIn processes payments:**
1. Extracts `payments` array from each taxpayer group
2. Strips `_id`, `dataStatus`, `time` fields
3. Transforms camelCase keys → snake_case
4. Batch inserts via `Payment::insert()` (bypasses model events!)
5. Updates invoice `pay_status` field

**IMPORTANT:** SyncIn uses raw `insert()`, which does NOT trigger PaymentObserver. This means InvoiceCodeBalance is NOT auto-updated for synced payments. Digital payments use `Payment::create()` which DOES trigger the observer.

---

## 3. DATA MODELS & DB SCHEMA

### payments table
```
id                    BIGINT AUTO_INCREMENT (starts at 100001)
amount                DOUBLE default 0
payment_type          VARCHAR — "CASH" | "CHEQUE" | "DIGI"
invoice_type          VARCHAR — "TITRE" | "COMPTANT"
reference             VARCHAR nullable
description           VARCHAR nullable
remaining_amount      DOUBLE nullable
invoice_id            BIGINT FK → invoices.id (BUT often stores invoice_no string!)
taxpayer_id           BIGINT FK → taxpayers.id
user_id               BIGINT FK → users.id
r_user_id             BIGINT FK → users.id (regisseur)
status                VARCHAR — "PENDING" | "ACCOUNTED" | "DONE" | "CANCELED" | "VERIFYING" | "FAILED" | "EXPIRED"
uuid                  VARCHAR UNIQUE (auto-generated on create)
code                  VARCHAR nullable (tax label code)
deposit               DOUBLE nullable
notes                 TEXT nullable
provider              VARCHAR nullable — "qosic" | "fedapay" | "paygate"
network               VARCHAR nullable — "TMONEY" | "FLOOZ"
phone_number          VARCHAR nullable
external_id           VARCHAR nullable (provider transaction reference)
verification_attempts SMALLINT default 0
last_checked_at       TIMESTAMP nullable
expires_at            TIMESTAMP nullable
created_at            TIMESTAMP
updated_at            TIMESTAMP
```

### mobile_payment_transactions table (audit/tracking)
```
id                    BIGINT AUTO_INCREMENT
reference             VARCHAR UNIQUE (UUID v4)
invoice_id            BIGINT FK → invoices.id
taxpayer_id           BIGINT FK → taxpayers.id nullable
amount                DECIMAL(15,2)
phone_number          VARCHAR
provider              VARCHAR — "qosic" | "fedapay" | "paygate"
external_id           VARCHAR nullable
status                VARCHAR — "pending" | "verifying" | "success" | "failed" | "expired"
verification_attempts SMALLINT default 0
last_checked_at       TIMESTAMP nullable
verified_at           TIMESTAMP nullable
expires_at            TIMESTAMP nullable
provider_response     LONGTEXT nullable (JSON)
meta                  LONGTEXT nullable (JSON: {code, invoice_no, order_no, invoice_type, notes})
user_id               BIGINT FK → users.id nullable
created_at            TIMESTAMP
updated_at            TIMESTAMP
```

### invoices table (relevant fields)
```
id                    BIGINT AUTO_INCREMENT (starts at 100001)
invoice_no            VARCHAR (display number like "INV-2026-001")
order_no              VARCHAR nullable
amount                DOUBLE default 0
reduce_amount         DOUBLE nullable
pay_status            VARCHAR — "OWING" | "PART PAID" | "PAID"
status                VARCHAR — "DRAFT" | "APPROVED" | "APPROVED-CANCELLATION-OR-REDUCTION" | etc.
type                  VARCHAR — "TITRE" | "COMPTANT"
validity              VARCHAR — "VALID" | "EXPIRED" | "ARCHIVED"
taxpayer_id           BIGINT FK → taxpayers.id
uuid                  VARCHAR UNIQUE
from_date             DATE
to_date               DATE
delivery              VARCHAR default "NOT DELIVERED"
delivery_date         DATE nullable
```

### taxpayers table (relevant fields)
```
id                    BIGINT AUTO_INCREMENT
name                  VARCHAR
mobilephone           VARCHAR nullable
zone_id               BIGINT FK → zones.id
erea_id               BIGINT FK → ereas.id
category_id           BIGINT FK → categories.id
type                  VARCHAR — "TITRE" | "DEMANDE"
```

---

## 4. EXISTING PAYMENT FLOW (Cash/Cheque — DO NOT CHANGE)

### On Mobile (Current Behavior)
1. Agent browses taxpayers for their zone (data from SyncOut)
2. Agent selects taxpayer → sees invoices with `status=APPROVED` and `payStatus != PAID`
3. Agent enters payment: amount, payment_type (CASH/CHEQUE), reference
4. Payment stored locally in SQLite with `dataStatus: "new"`
5. Invoice `payStatus` updated locally
6. On sync: all new payments sent via `POST /v1/synchronisation/in`

### On Backend (SyncInController)
1. Receives nested taxpayer groups
2. For each taxpayer: processes taxpayer data, taxables, invoices, payments
3. Payments: batch inserted with `Payment::insert()` (raw SQL, no model events)
4. No PaymentObserver triggered, no InvoiceCodeBalance update

### Cash Payment Data Shape (what mobile sends)
```json
{
  "amount": 5000,
  "paymentType": "CASH",
  "invoiceId": "INV-2026-001",
  "taxpayerId": 10024,
  "userId": 1,
  "status": "PENDING",
  "remainingAmount": 15000,
  "description": "Avis INV-2026-001, OR ORD-001",
  "reference": "REF-001",
  "code": "TAX01",
  "invoiceType": "TITRE",
  "dataStatus": "new"
}
```

---

## 5. DIGITAL PAYMENT FLOW (Mobile-Side — Direct Provider Integration)

The mobile app handles the ENTIRE digital payment lifecycle directly with the provider. No backend involvement until sync.

### How It Must Work on Mobile
1. User selects invoice → picks `DIGI` payment type
2. Enters phone number, selects network (TMONEY/FLOOZ)
3. Provider is set (default: `paygate`, configurable)
4. **Mobile app calls provider API directly** (e.g. PayGate `POST /api/v1/pay`)
5. Provider sends USSD prompt to user's phone
6. **Mobile app polls provider verify API** with backoff [10, 30, 60, 120, 300] seconds
7. On provider confirmation (`success`):
   - Mobile creates a Payment record **locally in SQLite** with `paymentType: "DIGI"`, `status: "ACCOUNTED"`
   - Updates local invoice `payStatus` (PAID or PART PAID)
   - Payment includes: `provider`, `network`, `phoneNumber`, `externalId`, `reference` (UUID)
8. On next sync: DIGI payment is sent via `POST /v1/synchronisation/in` like CASH/CHEQUE

### Provider API Details (Called Directly by Mobile)

#### PayGate (Primary — Recommended)
**Base URL:** `https://paygateglobal.com`

**Step 1 — Initiate Payment:**
```
POST https://paygateglobal.com/api/v1/pay
Content-Type: application/json
```
**Request:**
```json
{
  "auth_token": "YOUR_PAYGATE_API_KEY",
  "phone_number": "22890123456",
  "amount": 5000,
  "identifier": "a1b2c3d4-uuid-reference",
  "network": "TMONEY",
  "description": "Avis INV-2026-001"
}
```
**Response:**
```json
{
  "status": 0,
  "tx_reference": "PG_REF_123"
}
```
**Initiate Status Codes:**
| Code | Meaning | Action |
|------|---------|--------|
| 0 | Success — USSD sent to phone | Proceed to verify |
| 2 | Invalid auth token | Check API key config |
| 4 | Invalid parameters | Check phone/amount/network |
| 6 | Duplicate identifier | UUID collision, generate new one |

**Step 2 — Verify Payment (Poll):**
```
POST https://paygateglobal.com/api/v1/status
Content-Type: application/json
```
**Request:**
```json
{
  "auth_token": "YOUR_PAYGATE_API_KEY",
  "tx_reference": "PG_REF_123"
}
```
**Response:**
```json
{
  "status": 0,
  "tx_reference": "PG_REF_123",
  "identifier": "a1b2c3d4-uuid-reference",
  "payment_reference": "PAY_REF_456",
  "datetime": "2026-04-06 10:30:00",
  "payment_method": "TMONEY"
}
```
**Verify Status Codes:**
| Code | Meaning | Action |
|------|---------|--------|
| 0 | Successful — payment confirmed | Create local Payment record |
| 2 | In progress — user hasn't confirmed yet | Retry after backoff |
| 4 | Expired — user didn't respond | Show "Délai expiré", allow retry |
| 6 | Cancelled — user rejected on phone | Show "Paiement annulé" |

**Alternative Verify (by identifier instead of tx_reference):**
```
POST https://paygateglobal.com/api/v2/status
```
```json
{
  "auth_token": "YOUR_PAYGATE_API_KEY",
  "identifier": "a1b2c3d4-uuid-reference"
}
```
Same response format. Useful if `tx_reference` was lost.

**Networks:** `TMONEY`, `FLOOZ`

#### QOSIC (Alternative)
**Step 1 — Initiate:**
```
POST {QOSIC_BASE_URL}/QosicBridge/tm/v1/requestpayment
Authorization: Basic {base64(username:password)}
Content-Type: application/json
```
```json
{
  "msisdn": "22890123456",
  "amount": 5000,
  "firstname": "SIG",
  "lastname": "RECETTE",
  "transref": "a1b2c3d4-uuid-reference"
}
```
**Response:** `responsecode === "00"` means success. `serviceref` is the external ID.

**Step 2 — Verify:**
```
POST {QOSIC_BASE_URL}/QosicBridge/tm/v1/gettransactionstatus
Authorization: Basic {base64(username:password)}
```
```json
{
  "transref": "a1b2c3d4-uuid-reference"
}
```
**Response codes:** `"00"` = success, `"01"/"11"/"12"` = pending, others = failed.

#### FedaPay (Alternative)
**Step 1 — Initiate:**
```
POST {FEDAPAY_BASE_URL}/v1/transactions
Authorization: Bearer {secret_key}
Content-Type: application/json
```
```json
{
  "description": "Avis INV-2026-001",
  "amount": 5000,
  "currency": {"iso": "XOF"},
  "customer": {
    "phone_number": {"number": "22890123456", "country": "BJ"}
  },
  "custom_metadata": {
    "reference": "a1b2c3d4-uuid-reference"
  }
}
```
**Response:** `transaction.id` is the external ID.

**Step 2 — Verify:**
```
GET {FEDAPAY_BASE_URL}/v1/transactions?custom_metadata[reference]=a1b2c3d4-uuid-reference
Authorization: Bearer {secret_key}
```
**Status mapping:** `"approved"/"transferred"` = success, `"declined"/"refunded"/"canceled"` = failed, others = pending.

---

## 6. API ENDPOINTS REFERENCE

### Authentication (Backend — for Sync Only)
All sync endpoints require Sanctum bearer token:
```
Authorization: Bearer {token}
```

Obtain token:
```
POST /api/v1/auth
Body: { "email": "...", "password": "..." }
Response: { "token": "...", "user": {...} }
```

### Existing Endpoints (Unchanged — Used for Sync)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/v1/synchronisation/out` | Get all zone data (taxpayers, invoices, payments) |
| POST | `/api/v1/synchronisation/in` | Sync changes back (taxpayers, taxables, invoices, payments — **including DIGI**) |
| GET | `/api/v1/sync/invoices` | Get paginated invoices (v1 sync) |
| GET | `/api/v1/sync/payments` | Get paginated payments (v1 sync) |
| POST | `/api/v1/sync/payments` | Import payments with UUID (v1 sync) |

### Provider APIs (Called Directly by Mobile — NO Backend Involved)

| Provider | Initiate URL | Verify URL | Auth |
|----------|-------------|------------|------|
| PayGate | `POST https://paygateglobal.com/api/v1/pay` | `POST https://paygateglobal.com/api/v1/status` | `auth_token` in body |
| QOSIC | `POST {base}/QosicBridge/tm/v1/requestpayment` | `POST {base}/QosicBridge/tm/v1/gettransactionstatus` | Basic Auth header |
| FedaPay | `POST {base}/v1/transactions` | `GET {base}/v1/transactions?custom_metadata[reference]=...` | Bearer token header |

### NO New Backend Endpoints Needed
The mobile app talks directly to payment providers. Completed payments are synced through the existing `POST /v1/synchronisation/in` endpoint. No new backend controller is required.

---

## 7. ENUMS & CONSTANTS

### Payment Status (PaymentStatusEnums)
```
PENDING     = "PENDING"      — Created, awaiting regisseur validation
ACCOUNTED   = "ACCOUNTED"    — Validated by regisseur (or auto for DIGI)
DONE        = "DONE"         — Final
CANCELED    = "CANCELED"     — Annulled
VERIFYING   = "VERIFYING"    — Mobile payment verification in progress
FAILED      = "FAILED"       — Mobile payment failed
EXPIRED     = "EXPIRED"      — Mobile payment expired
```

### Payment Type (PaymentTypeEnums)
```
CASH   = "CASH"
CHEQUE = "CHEQUE"
DIGI   = "DIGI"        — Digital/Mobile money
```

### Invoice Pay Status (InvoicePayStatusEnums)
```
OWING     = "OWING"       — Not paid
PART_PAID = "PART PAID"   — Partially paid
PAID      = "PAID"        — Fully paid
```

### Invoice Status (InvoiceStatusEnums)
```
DRAFT                = "DRAFT"
ACCEPTED             = "ACCEPTED"
PENDING              = "PENDING"
REJECTED             = "REJECTED"
APPROVED             = "APPROVED"
APPROVED_CANCELLATION = "APPROVED-CANCELLATION-OR-REDUCTION"
CANCELED             = "APPROVED-CANCELED"
REDUCED              = "APPROVED-REDUCED"
```

### Providers
```
PROVIDER_QOSIC   = "qosic"
PROVIDER_FEDAPAY  = "fedapay"
PROVIDER_PAYGATE  = "paygate"
```

### Networks
```
NETWORK_TMONEY = "TMONEY"
NETWORK_FLOOZ  = "FLOOZ"
```

### Invoice Types
```
TITRE    = "TITRE"     — Annual tax invoice (partial payments allowed)
COMPTANT = "COMPTANT"  — One-time invoice (must pay full amount)
```

### Tax Code Splitting
Invoices have multiple tax codes (e.g. TAX01, TAX02). When paying:
- Payment amount is split across tax codes based on remaining balance per code
- Each tax code gets its own Payment record
- `Invoice::getCode($invoiceId, $amount, $paymentData)` handles this splitting
- The `code` field on Payment tracks which tax code it applies to

---

## 8. ANDROID IMPLEMENTATION PLAN

### Phase 1: Provider Configuration

Store provider credentials securely on the device (e.g. encrypted SharedPreferences or BuildConfig):
```kotlin
// PaymentProviderConfig.kt
object PaymentProviderConfig {
    // PayGate (primary)
    const val PAYGATE_BASE_URL = "https://paygateglobal.com"
    const val PAYGATE_AUTH_TOKEN = "YOUR_PAYGATE_API_KEY"  // from secure config
    
    // QOSIC (alternative)
    const val QOSIC_BASE_URL = "..."
    const val QOSIC_USERNAME = "..."
    const val QOSIC_PASSWORD = "..."
    
    // FedaPay (alternative) 
    const val FEDAPAY_BASE_URL = "https://sandbox-api.fedapay.com"
    const val FEDAPAY_SECRET_KEY = "..."
    
    // Verification
    val VERIFY_BACKOFF_SECONDS = listOf(10, 30, 60, 120, 300)
    const val MAX_VERIFY_ATTEMPTS = 5
    const val TRANSACTION_EXPIRY_MINUTES = 30
}
```

### Phase 2: Provider API Interface

```kotlin
// PaygateApi.kt (Retrofit)
interface PaygateApi {
    
    @POST("api/v1/pay")
    suspend fun initiatePayment(
        @Body request: PaygateInitiateRequest
    ): Response<PaygateInitiateResponse>
    
    @POST("api/v1/status")
    suspend fun verifyPayment(
        @Body request: PaygateVerifyRequest
    ): Response<PaygateVerifyResponse>
}

// Request/Response data classes
data class PaygateInitiateRequest(
    val auth_token: String,
    val phone_number: String,   // "22890123456"
    val amount: Int,            // Integer amount in FCFA
    val identifier: String,     // UUID v4 generated by mobile
    val network: String,        // "TMONEY" | "FLOOZ"
    val description: String     // "Avis INV-2026-001"
)

data class PaygateInitiateResponse(
    val status: Int,            // 0=success, 2=invalid token, 4=invalid params, 6=duplicate
    val tx_reference: String?   // Provider reference (save this!)
)

data class PaygateVerifyRequest(
    val auth_token: String,
    val tx_reference: String    // From initiate response
)

data class PaygateVerifyResponse(
    val status: Int,            // 0=success, 2=in progress, 4=expired, 6=cancelled
    val tx_reference: String?,
    val identifier: String?,
    val payment_reference: String?,
    val datetime: String?,
    val payment_method: String?
)
```

### Phase 3: Payment Service (Mobile-Side)

```kotlin
// MobilePaymentService.kt
class MobilePaymentService(
    private val paygateApi: PaygateApi,
    private val paymentDao: PaymentDao,     // Room DAO
    private val invoiceDao: InvoiceDao      // Room DAO
) {
    /**
     * Full digital payment flow:
     * 1. Generate UUID reference
     * 2. Call provider initiate API
     * 3. Poll provider verify API with backoff
     * 4. On success: create local Payment + update Invoice
     */
    suspend fun initiateAndVerify(
        invoice: Invoice,
        amount: Double,
        phoneNumber: String,
        network: String,        // "TMONEY" | "FLOOZ"
        provider: String,       // "paygate" | "qosic" | "fedapay"
        userId: Int,
        code: String?,          // Tax code (optional)
        notes: String?
    ): PaymentResult {
        
        // 1. Generate unique reference
        val reference = UUID.randomUUID().toString()
        val description = "Avis ${invoice.invoiceNo}"
        
        // 2. Initiate with provider
        val initiateResponse = paygateApi.initiatePayment(
            PaygateInitiateRequest(
                auth_token = PaymentProviderConfig.PAYGATE_AUTH_TOKEN,
                phone_number = phoneNumber,
                amount = amount.toInt(),
                identifier = reference,
                network = network,
                description = description
            )
        )
        
        if (!initiateResponse.isSuccessful || initiateResponse.body()?.status != 0) {
            return PaymentResult.Failed(
                message = mapInitiateError(initiateResponse.body()?.status ?: -1)
            )
        }
        
        val txReference = initiateResponse.body()!!.tx_reference
            ?: return PaymentResult.Failed("Pas de référence transaction")
        
        // 3. Poll for verification with backoff
        return verifyWithBackoff(
            txReference = txReference,
            reference = reference,
            invoice = invoice,
            amount = amount,
            phoneNumber = phoneNumber,
            network = network,
            provider = provider,
            userId = userId,
            code = code,
            notes = notes
        )
    }
    
    private suspend fun verifyWithBackoff(
        txReference: String,
        reference: String,
        invoice: Invoice,
        amount: Double,
        phoneNumber: String,
        network: String,
        provider: String,
        userId: Int,
        code: String?,
        notes: String?
    ): PaymentResult {
        val backoffs = PaymentProviderConfig.VERIFY_BACKOFF_SECONDS
        
        for (attempt in 0 until PaymentProviderConfig.MAX_VERIFY_ATTEMPTS) {
            // Wait with backoff
            delay(backoffs.getOrElse(attempt) { 300 } * 1000L)
            
            val verifyResponse = paygateApi.verifyPayment(
                PaygateVerifyRequest(
                    auth_token = PaymentProviderConfig.PAYGATE_AUTH_TOKEN,
                    tx_reference = txReference
                )
            )
            
            val status = verifyResponse.body()?.status ?: -1
            
            when (status) {
                0 -> {
                    // SUCCESS — Create local payment and update invoice
                    createLocalPayment(
                        invoice = invoice,
                        amount = amount,
                        phoneNumber = phoneNumber,
                        network = network,
                        provider = provider,
                        reference = reference,
                        externalId = txReference,
                        userId = userId,
                        code = code,
                        notes = notes
                    )
                    return PaymentResult.Success(reference = reference)
                }
                2 -> {
                    // IN PROGRESS — Continue polling
                    // Report progress to UI: "Tentative ${attempt + 1}..."
                    continue
                }
                4 -> return PaymentResult.Expired
                6 -> return PaymentResult.Cancelled
                else -> continue
            }
        }
        
        return PaymentResult.Timeout
    }
    
    /**
     * Create a Payment record locally (SQLite/Room) — identical structure to CASH.
     * This will be synced to backend via POST /v1/synchronisation/in
     */
    private suspend fun createLocalPayment(
        invoice: Invoice,
        amount: Double,
        phoneNumber: String,
        network: String,
        provider: String,
        reference: String,
        externalId: String,
        userId: Int,
        code: String?,
        notes: String?
    ) {
        val paid = paymentDao.getPaidForInvoice(invoice.invoiceNo)
        val remainingAmount = invoice.amount - (paid + amount)
        
        val payment = Payment(
            _id = null,                 // New payment, no backend ID yet
            dataStatus = "new",
            amount = amount,
            paymentType = "DIGI",       // CRITICAL: must be "DIGI"
            invoiceId = invoice.invoiceNo,
            invoiceType = invoice.type,
            taxpayerId = invoice.taxpayerId,
            userId = userId,
            status = "ACCOUNTED",       // DIGI payments are auto-accounted
            remainingAmount = maxOf(remainingAmount, 0.0),
            description = "Avis ${invoice.invoiceNo}",
            reference = reference,      // UUID v4
            code = code,
            provider = provider,        // "paygate" | "qosic" | "fedapay"
            network = network,          // "TMONEY" | "FLOOZ"
            phoneNumber = phoneNumber,
            externalId = externalId,    // tx_reference from provider
            notes = notes ?: "Paiement mobile vérifié"
        )
        
        paymentDao.insert(payment)
        
        // Update local invoice payStatus
        val totalPaid = paid + amount
        val payStatus = if (totalPaid >= invoice.amount) "PAID" else "PART PAID"
        invoiceDao.updatePayStatus(invoice.id, payStatus)
    }
}

sealed class PaymentResult {
    data class Success(val reference: String) : PaymentResult()
    data class Failed(val message: String) : PaymentResult()
    object Expired : PaymentResult()
    object Cancelled : PaymentResult()
    object Timeout : PaymentResult()
}
```

### Phase 4: Payment Data Shape for Sync

When the DIGI payment is synced via `POST /v1/synchronisation/in`, it must be in the same structure as CASH/CHEQUE payments but with DIGI-specific fields:

```json
{
  "_id": null,
  "dataStatus": "new",
  "amount": 5000,
  "paymentType": "DIGI",
  "invoiceId": "INV-2026-001",
  "invoiceType": "TITRE",
  "taxpayerId": 10024,
  "userId": 1,
  "status": "ACCOUNTED",
  "remainingAmount": 15000,
  "description": "Avis INV-2026-001",
  "reference": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
  "code": "TAX01",
  "provider": "paygate",
  "network": "TMONEY",
  "phoneNumber": "22890123456",
  "externalId": "PG_REF_123",
  "notes": "Paiement mobile vérifié"
}
```

**Key differences from CASH/CHEQUE:**
| Field | CASH/CHEQUE | DIGI |
|-------|-----------|------|
| `paymentType` | `"CASH"` / `"CHEQUE"` | `"DIGI"` |
| `status` | `"PENDING"` | `"ACCOUNTED"` (provider-verified) |
| `provider` | not sent | `"paygate"` / `"qosic"` / `"fedapay"` |
| `network` | not sent | `"TMONEY"` / `"FLOOZ"` |
| `phoneNumber` | not sent | `"22890123456"` |
| `externalId` | not sent | provider tx_reference |
| `reference` | user-entered | UUID v4 (auto-generated) |

The backend `SyncInController.transformKeysToSnakeCase()` will auto-convert `paymentType` → `payment_type`, `phoneNumber` → `phone_number`, `externalId` → `external_id`, etc. All these fields exist on the `payments` table.

### Phase 5: UI Changes to Payment Screen

The existing payment screen has a payment type selector (CASH/CHEQUE). Add:

1. **Add "Mobile Money" option** to payment type selector (value: `DIGI`)
2. **When DIGI selected, show:**
   - Phone number input (required, format: country code + number, e.g. 22890123456)
   - Network selector: TMONEY / FLOOZ (required)
   - Amount input (same as CASH/CHEQUE)
3. **On submit:**
   - Validate: phone_number not empty, network selected, amount > 0
   - **Check internet connectivity** (REQUIRED — must have internet to reach provider)
   - If no internet → show error "Le paiement mobile nécessite une connexion internet"
   - Call `MobilePaymentService.initiateAndVerify()`
4. **During verification (polling):**
   - Show progress dialog: "Paiement initié. Confirmez sur votre téléphone..."
   - Update text with attempt count: "Vérification en cours... (tentative X/5)"
   - User sees USSD prompt on their phone → they confirm
   - Allow manual cancel (but warn: payment may still be processed)
5. **On result:**
   - `Success` → Show success toast + update payment list + close modal
   - `Failed` → Show error + "Réessayer" button
   - `Expired` → Show "Délai expiré" + allow new attempt
   - `Cancelled` → Show "Paiement annulé par l'utilisateur"
   - `Timeout` → Show "Vérification échouée après 5 tentatives" + note that money may still be deducted

### Phase 6: Integrate with Existing Sync

**SyncOut:** `SearchPaymentResource` now returns `paymentType`, `provider`, `network`, `phoneNumber`, `externalId`, `code`, `invoiceType` fields. DIGI payments from the backend (or from other agents) will arrive with `paymentType: "DIGI"`. Display these as read-only — do not allow editing/deleting DIGI payments that came from the server.

**SyncIn:** No code changes needed. The existing flow already:
1. Strips `_id`, `dataStatus`, `time`
2. Converts camelCase → snake_case
3. Batch inserts with `Payment::insert()`

The DIGI-specific fields (`provider`, `network`, `phone_number`, `external_id`) will flow through automatically because: camelCase input → `transformKeysToSnakeCase()` → snake_case columns → `INSERT` matches Payment table columns.

### Phase 7: Room Database Schema

Add DIGI fields to the local Payment entity:

```kotlin
@Entity(tableName = "payments")
data class Payment(
    @PrimaryKey(autoGenerate = true) val localId: Long = 0,
    @ColumnInfo(name = "_id") val _id: Int?,
    val dataStatus: String,     // "new" | "synced"
    val amount: Double,
    val paymentType: String,    // "CASH" | "CHEQUE" | "DIGI"
    val invoiceId: String,      // invoice_no string
    val invoiceType: String?,   // "TITRE" | "COMPTANT"
    val taxpayerId: Int,
    val userId: Int,
    val status: String,         // "PENDING" | "ACCOUNTED"
    val remainingAmount: Double?,
    val description: String?,
    val reference: String?,
    val code: String?,          // Tax label code
    // DIGI-specific fields (null for CASH/CHEQUE)
    val provider: String?,      // "paygate" | "qosic" | "fedapay"
    val network: String?,       // "TMONEY" | "FLOOZ"
    val phoneNumber: String?,   // "22890123456"
    val externalId: String?,    // Provider tx_reference
    val notes: String?
)
```

Add a migration to add these columns if the payments table already exists.

---

## 9. CRITICAL RULES — DO NOT BREAK

### 1. DIGI payments require internet (but NOT the local server)
The mobile app needs internet to reach the payment provider (PayGate/QOSIC/FedaPay). The local backend server does NOT need to be online. DIGI is blocked only when there is no internet at all.

### 2. DIGI payments are stored locally like CASH/CHEQUE
After provider verification succeeds, create a local Payment record in SQLite with `dataStatus: "new"`. It syncs to the backend via the existing `POST /v1/synchronisation/in` endpoint.

### 3. DIGI payments MUST have status "ACCOUNTED"
Unlike CASH/CHEQUE (`status: "PENDING"` until regisseur validates), DIGI payments are provider-verified and must be created with `status: "ACCOUNTED"`. This is critical — the backend treats ACCOUNTED differently in reports.

### 4. DIGI payments MUST include provider fields
Every DIGI payment sent to the backend must include `provider`, `network`, `phoneNumber`, and `externalId`. These fields map to columns on the `payments` table.

### 5. Use UUID v4 as the payment reference
Generate a UUID v4 on the mobile device for each DIGI payment. This becomes both the `reference` field on the Payment and the `identifier` param sent to the provider. It ensures idempotency — PayGate returns status 6 for duplicate identifiers.

### 6. NEVER create a local Payment until provider confirms success
Only after the verify API returns status 0 (success) should you insert into SQLite. If verification fails/expires/times out, do NOT create a Payment record.

### 7. Amount validation
- For `COMPTANT` invoices: amount MUST equal `invoice.amount` (full payment only)
- For `TITRE` invoices: amount must be ≤ remaining balance (`invoice.amount - paid`)
- Amount must be > 0

### 8. Invoice eligibility for payment
Only invoices with:
- `status === "APPROVED"` or `status === "APPROVED-CANCELLATION-OR-REDUCTION"`
- `payStatus !== "PAID"`
- `validity === "VALID"` (if available in local data)

### 9. Tax code splitting is handled server-side
Do NOT try to split payments by tax code on mobile. Just send the total amount and optionally a `code`. When SyncIn processes the payment, the backend can handle code-level tracking.

### 10. Existing CASH/CHEQUE flow MUST remain unchanged
All current offline payment collection and sync behavior stays exactly the same. DIGI is additive.

### 11. Provider API keys must be stored securely
Do NOT hardcode API keys in source code. Use Android's EncryptedSharedPreferences, BuildConfig fields (excluded from VCS), or a secure keystore. Never log API keys.

### 12. Handle "money deducted but verify failed" gracefully
If the user's money was deducted but verification times out (5 attempts), show a clear message: "Le paiement a peut-être été débité. Veuillez vérifier votre solde et réessayer la vérification." Provide a manual "Vérifier à nouveau" button that re-calls the verify API.

---

## 10. SEQUENCE DIAGRAMS

### Digital Payment Flow (Mobile → Provider Directly)
```
Mobile App                     Provider (PayGate)           User's Phone
    |                               |                           |
    |-- POST /api/v1/pay ---------->|                           |
    |<-- {status:0, tx_ref} --------|                           |
    |                               |-- USSD prompt ----------->|
    |                               |                           |
    |   [Show: "Confirmez sur votre téléphone..."]              |
    |                               |                    [User confirms]
    |                               |                           |
    |   [Wait 10s backoff]          |                           |
    |-- POST /api/v1/status ------->|                           |
    |<-- {status:2} (pending) ------|                           |
    |                               |                           |
    |   [Wait 30s backoff]          |                           |
    |-- POST /api/v1/status ------->|                           |
    |<-- {status:0} (success!) -----|                           |
    |                               |                           |
    |-- [Create Payment in SQLite with status:ACCOUNTED]        |
    |-- [Update Invoice payStatus locally]                      |
    |-- [Show success to agent]                                 |
```

### Sync Flow (Mobile → Backend — Later)
```
Mobile App                         Backend (when online)
    |                                    |
    |-- POST /v1/synchronisation/in ---->|
    |   (includes DIGI payments with     |
    |    provider, network, phoneNumber, |
    |    externalId, status:ACCOUNTED)   |
    |                                    |
    |                                    |-- Payment::insert()
    |                                    |-- Invoice::update(payStatus)
    |<-- 200 OK -------------------------|
```

### Cash Payment Flow (Existing — No Changes)
```
Mobile App                    Backend
    |                            |
    |-- [Offline: Create payment in SQLite]
    |-- [Offline: Update invoice payStatus]
    |                            |
    |-- POST /synchronisation/in -->|
    |                            |-- Batch insert payments
    |                            |-- Update invoices
    |<-- 200 OK -----------------|
```

### Failed Payment Flow
```
Mobile App                     Provider (PayGate)
    |                               |
    |-- POST /api/v1/pay ---------->|
    |<-- {status:0, tx_ref} --------|
    |                               |
    |   [Wait 10s]                  |
    |-- POST /api/v1/status ------->|
    |<-- {status:2} (pending) ------|
    |   [Wait 30s]                  |
    |-- POST /api/v1/status ------->|
    |<-- {status:4} (expired!) -----|
    |                               |
    |-- [Show "Délai expiré"]       |
    |-- [NO Payment created locally]|
    |-- [Offer "Réessayer" button]  |
```

---

## 11. ERROR HANDLING

### Provider Errors (PayGate)
| Initiate Status | Meaning | Mobile Action |
|----------------|---------|---------------|
| 0 | Success — USSD sent | Proceed to verify polling |
| 2 | Invalid auth token | Check API key config, show "Config erreur" |
| 4 | Invalid parameters | Check phone/amount/network, show field errors |
| 6 | Duplicate identifier | Generate new UUID and retry |

| Verify Status | Meaning | Mobile Action |
|--------------|---------|---------------|
| 0 | Payment confirmed | Create local Payment, show success |
| 2 | Still in progress | Continue polling (next backoff interval) |
| 4 | Expired (user didn't respond) | Show "Délai expiré", allow new attempt |
| 6 | Cancelled by user | Show "Paiement annulé", allow new attempt |

### Network Errors
| Error | Mobile Action |
|-------|---------------|
| No internet | Block DIGI option entirely, show message |
| Timeout on initiate call | Show error, allow retry |
| Timeout on verify call | Retry on next backoff, don't assume failure |
| Connection lost mid-verification | Resume polling when internet returns |

### Critical: Money Deducted But Verify Failed
If all 5 verify attempts return status 2 (pending):
- **DO NOT create a Payment record**
- Show message: "La vérification a échoué après 5 tentatives. Votre argent a peut-être été débité."
- Provide "Vérifier à nouveau" button that retries verify with the saved `tx_reference`
- Store the `tx_reference` and `identifier` locally so the user can retry later
- The PayGate v2 status API (`POST /api/v2/status` with `identifier`) is useful as fallback

### Sync Errors (Backend)
| HTTP Code | Meaning | Mobile Action |
|-----------|---------|---------------|
| 401 | Token expired | Re-authenticate |
| 500 + step "inserting payments" | Payment insert failed | Check error message for column issues |
| 500 + step "updating invoice" | Invoice update failed | Non-critical, payment still synced |

---

## 12. TESTING CHECKLIST

### Functional Tests
- [ ] CASH payment flow unchanged (create offline, sync, verify on backend)
- [ ] CHEQUE payment flow unchanged
- [ ] DIGI payment: initiate → poll → success → local Payment created with ACCOUNTED
- [ ] DIGI payment: initiate → poll → failed/expired → NO local Payment created
- [ ] DIGI payment: initiate → poll → timeout → retry verify → success
- [ ] DIGI payment: COMPTANT invoice → amount auto-set to invoice amount
- [ ] DIGI payment: TITRE invoice → partial payment amount
- [ ] DIGI with TMONEY network
- [ ] DIGI with FLOOZ network
- [ ] DIGI payment blocked when offline (no internet)
- [ ] Local invoice payStatus updates correctly after DIGI success
- [ ] DIGI payments received via SyncOut display correctly (read-only)

### Sync Tests
- [ ] DIGI payments sync via `POST /v1/synchronisation/in` successfully
- [ ] Backend receives all DIGI fields: provider, network, phone_number, external_id
- [ ] Backend stores payment with status ACCOUNTED and payment_type DIGI
- [ ] SyncOut returns DIGI payments with new fields (provider, network, etc.)
- [ ] No duplicate payments after re-sync (UUID reference is unique)
- [ ] CASH/CHEQUE payments still sync correctly alongside DIGI payments

### Provider Integration Tests
- [ ] PayGate initiate returns tx_reference on success
- [ ] PayGate verify returns correct status codes (0, 2, 4, 6)
- [ ] PayGate duplicate identifier returns status 6
- [ ] Backoff timing works: 10s, 30s, 60s, 120s, 300s intervals
- [ ] API key is not logged or exposed in error messages

### Edge Cases
- [ ] App loses internet mid-verification → can resume when online
- [ ] User tries to pay already-PAID invoice → rejected before API call
- [ ] User enters amount > remaining balance → rejected before API call
- [ ] Double-tap submit → second call gets duplicate identifier error (status 6)
- [ ] Multiple DIGI payments on same invoice → each gets unique UUID
- [ ] Backend server down during sync → payments retained locally, sync on next attempt
- [ ] User force-closes app during verification → saved tx_reference allows manual re-verify

---

## APPENDIX: Backend Files Reference

| File | Purpose |
|------|---------|
| `app/Http/Controllers/Api/SyncInController.php` | Handles mobile→backend sync (CASH/CHEQUE/DIGI) |
| `app/Http/Controllers/Api/SyncOutController.php` | Backend→mobile data export |
| `app/Http/Resources/SearchPaymentResource.php` | Payment data shape sent to mobile (includes DIGI fields) |
| `app/Models/Payment.php` | Payment model (fillable includes provider, network, phone_number, external_id) |
| `app/Models/Invoice.php` | Invoice model |
| `app/Traits/InvoiceTrait.php` | Invoice helpers (getCode, returnPaidAndSumByCode) |
| `app/Traits/PaymentTrait.php` | Payment helpers (getPaid, getRestToPaid) |
| `app/Enums/PaymentStatusEnums.php` | Payment statuses (PENDING, ACCOUNTED, DONE, CANCELED, VERIFYING, FAILED, EXPIRED) |
| `app/Enums/PaymentTypeEnums.php` | Payment types (CASH, CHEQUE, DIGI) |
| `app/Helpers/Constants.php` | All constants (PROVIDER_QOSIC/FEDAPAY/PAYGATE, NETWORK_TMONEY/FLOOZ) |
| `config/mobile-payment.php` | Provider config (reference for credentials/URLs) |
| `config/features.php` | Feature flags (mobile_payment_feature) |
| `docs/PAYGATE.md` | PayGate API documentation |

---

## APPENDIX: Backend Change Made

`SearchPaymentResource.php` has been updated to include DIGI-specific fields in the SyncOut response:
- `invoiceType`, `code`, `provider`, `network`, `phoneNumber`, `externalId`

No other backend changes are needed. The existing `SyncInController` already handles DIGI payments because:
- `transformKeysToSnakeCase()` converts `phoneNumber`→`phone_number`, `externalId`→`external_id`, etc.
- `Payment::insert()` writes to all matching columns
- The `payments` table already has `provider`, `network`, `phone_number`, `external_id` columns

---

## APPENDIX: Provider Credentials Location

The backend stores provider credentials in `.env` and `config/mobile-payment.php`. For the mobile app, the same credentials must be configured:

```
# PayGate
PAYGATE_BASE_URL=https://paygateglobal.com
PAYGATE_API_KEY=your_auth_token_here

# QOSIC  
QOSIC_BASE_URL=...
QOSIC_USERNAME=...
QOSIC_PASSWORD=...

# FedaPay
FEDAPAY_BASE_URL=https://sandbox-api.fedapay.com
FEDAPAY_SECRET_KEY=...
```

Store these securely in the Android app (EncryptedSharedPreferences or similar). They should match the values in the backend's `.env` file.
