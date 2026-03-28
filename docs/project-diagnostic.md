# SIG-RECETTE — Full Project Diagnostic & Optimization Report

> **Generated**: March 28, 2026
> **Laravel Version**: 10.x | **PHP**: 8.2 | **Project Version**: 0.9.2

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Critical Bugs (Must-Fix)](#2-critical-bugs-must-fix)
3. [Security Issues](#3-security-issues)
4. [Performance Optimization](#4-performance-optimization)
5. [Code Quality & Architecture](#5-code-quality--architecture)
6. [Database & Migrations](#6-database--migrations)
7. [Testing Gaps](#7-testing-gaps)
8. [Frontend Optimization](#8-frontend-optimization)
9. [Configuration & Infrastructure](#9-configuration--infrastructure)
10. [Improvement Roadmap](#10-improvement-roadmap)

---

## 1. Executive Summary

### Project Overview

SIG-RECETTE is a **tax revenue management system** built on Laravel 10 with Livewire 3, managing taxpayers, invoices, payments, and synchronization with a remote backend. The codebase contains **30 Eloquent models**, **37 DataTable classes**, **23 Livewire components**, and **5 background jobs**.

### Health Score

| Category            | Score   | Status |
|---------------------|---------|--------|
| Critical Bugs       | 4 found | 🔴     |
| Security            | 6 issues| 🔴     |
| Performance         | 7 issues| 🟠     |
| Code Quality        | 8 issues| 🟡     |
| Database Design     | 5 issues| 🟡     |
| Test Coverage       | ~5%     | 🔴     |
| Frontend            | 4 issues| 🟡     |
| Configuration       | 4 issues| 🟠     |

**Total findings: 38 actionable improvements**

---

## 2. Critical Bugs (Must-Fix)

### 2.1 🔴 Duplicate Policy Key — `AuthServiceProvider`

**File**: `app/Providers/AuthServiceProvider.php`

```php
protected $policies = [
    User::class => UserPolicy::class,
    User::class => CollectorPolicy::class, // ← OVERWRITES the line above
    Role::class => RolePolicy::class,
];
```

**Impact**: PHP arrays cannot have duplicate keys. `CollectorPolicy` silently overwrites `UserPolicy`, meaning **all User model policy checks resolve to `CollectorPolicy`**, and `UserPolicy` is never used.

**Fix**: Use Gate definitions (already partially done) or create a combined policy:

```php
protected $policies = [
    User::class => UserPolicy::class,
    Role::class => RolePolicy::class,
];

// In boot():
Gate::define('update-collector', [CollectorPolicy::class, 'update']);
Gate::define('create-collector', [CollectorPolicy::class, 'create']);
Gate::define('delete-collector', [CollectorPolicy::class, 'delete']);
```

---

### 2.2 🔴 Route Nesting Bug — Double `/v1/v1` Prefix

**File**: `routes/api.php`

```php
Route::prefix('v1')->group(function () {
    // ...
    Route::middleware('auth:sanctum')->post('/v1/synchronisation/out', ...);
    Route::middleware('auth:sanctum')->post('/v1/synchronisation/in', ...);
});
```

**Impact**: These routes resolve to `/api/v1/v1/synchronisation/out` and `/api/v1/v1/synchronisation/in`. Either the sync client is already compensating for this (hitting the doubled path) or these endpoints are unreachable.

**Fix**: Remove the inner `/v1` prefix:

```php
Route::middleware('auth:sanctum')->post('/synchronisation/out', [SyncOutController::class, 'search']);
Route::middleware('auth:sanctum')->post('/synchronisation/in', [SyncInController::class, 'syncIn']);
```

---

### 2.3 🔴 Hardcoded Password in `.env.example`

**File**: `.env.example`, line ~77

```
BACKUP_ARCHIVE_PASSWORD=12345Sig789
```

**Impact**: This file is committed to version control. Anyone with repository access knows the backup password.

**Fix**: Replace with an empty placeholder:

```
BACKUP_ARCHIVE_PASSWORD=
```

---

### 2.4 🔴 Migration `down()` Drops Wrong Table

**File**: `database/migrations/2024_02_02_090250_create_towns_table.php`

```php
public function down()
{
    Schema::dropIfExists('genders'); // ← Should be 'towns'
}
```

**Impact**: Running `php artisan migrate:rollback` will drop the `genders` table instead of `towns`, causing data loss and schema corruption.

**Fix**:

```php
public function down()
{
    Schema::dropIfExists('towns');
}
```

---

## 3. Security Issues

### 3.1 🔴 Debug Mode Defaults to `true`

**File**: `config/app.php`

```php
'debug' => (bool) env('APP_DEBUG', true),
```

**Risk**: If `APP_DEBUG` is missing from `.env` on a production server, full stack traces, SQL queries, and environment variables are exposed to end users.

**Fix**: Change default to `false`:

```php
'debug' => (bool) env('APP_DEBUG', false),
```

---

### 3.2 🔴 Livewire Debug Hardcoded to `true`

**File**: `config/livewire.php`

```php
'debug' => true,
```

**Risk**: Exposes Livewire component state and internal mechanisms in all environments.

**Fix**:

```php
'debug' => env('LIVEWIRE_DEBUG', false),
```

---

### 3.3 🟠 CORS Wildcard Configuration

**File**: `config/cors.php`

```php
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

**Risk**: Any website can make API requests to your server. While `supports_credentials` is `false` (mitigating cookie theft), this still enables CSRF-like attacks on unauthenticated endpoints.

**Fix**: Restrict to known origins:

```php
'allowed_origins' => [env('APP_URL', 'http://localhost')],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
```

---

### 3.4 🟠 Session Encryption Disabled

**File**: `config/session.php`

```php
'encrypt' => false,
```

**Risk**: Session data stored in plain text. If an attacker accesses session storage (files, database), they can read user data.

**Fix**:

```php
'encrypt' => env('SESSION_ENCRYPT', true),
```

---

### 3.5 🟠 Missing Authorization on Core Controllers

**Affected Files**:
- `app/Http/Controllers/InvoiceController.php` — no `$this->authorize()` calls
- `app/Http/Controllers/TaxpayerController.php` — no authorization
- `app/Http/Controllers/ZonesController.php` — no authorization
- `app/Http/Controllers/YearsController.php` — no authorization
- `app/Http/Controllers/TownsController.php` — no authorization

**Current State**: Only 3 policies exist (`UserPolicy`, `CollectorPolicy`, `RolePolicy`) for 30+ models. Core business entities (Invoice, Payment, Taxpayer) have no policies.

**Risk**: Any authenticated user can perform any action regardless of their role.

**Fix**: Create policies for core models:

```bash
php artisan make:policy InvoicePolicy --model=Invoice
php artisan make:policy PaymentPolicy --model=Payment
php artisan make:policy TaxpayerPolicy --model=Taxpayer
```

Then apply in controllers:

```php
public function index() {
    $this->authorize('viewAny', Invoice::class);
    // ...
}
```

---

### 3.6 🟡 MySQL Strict Mode Disabled

**File**: `config/database.php`

```php
'strict' => false,
```

**Risk**: MySQL silently truncates data, allows zero dates, and ignores GROUP BY rule violations. This can lead to silent data corruption.

**Fix**:

```php
'strict' => true,
```

> **Note**: Enabling strict mode may surface existing issues in queries. Enable it first in a dev environment and fix any query errors before deploying to production.

---

## 4. Performance Optimization

### 4.1 🔴 N+1 Query Issues in DataTables

**File**: `app/DataTables/InvoicesDataTable.php`

```php
->editColumn('zones.name', function (Invoice $invoice) {
    return $invoice->taxpayer->zone->name ?? '-';   // ← N+1: loads taxpayer, then zone
})
->editColumn('taxpayers.address', function (Invoice $invoice) {
    return $invoice->taxpayer->address ?? '-';       // ← N+1: loads taxpayer again
})
```

The query builder at the bottom of the file joins tables but does not use `->with()` for the relationships accessed in `editColumn` callbacks.

**Impact**: With 100 invoices on a page, this generates **200+ additional queries** per page load.

**Fix**: Add eager loading to the `query()` method:

```php
public function query(Invoice $model): QueryBuilder
{
    return $model->newQuery()
        ->with(['taxpayer', 'taxpayer.zone'])
        // ... existing joins
}
```

Compare with `TaxpayerTaxablesDataTable.php` which already does this correctly:

```php
return $model->with('taxable')->with('taxable.tax_label')
```

---

### 4.2 🔴 Batch Sync Uses Triple-Nested Individual Saves

**File**: `app/Http/Controllers/Api/SyncInController.php`

```php
foreach ($data as $taxpayer) {
    foreach ($taxpayer as $taxpayerData) {
        foreach ($taxpayerData as $value) {
            if ($value['dataStatus'] == $this->new) {
                Taxpayer::create([...]);       // ← Individual INSERT per record
            } else {
                Taxpayer::find($id)?->update([...]); // ← SELECT + UPDATE per record
            }
            // Then for each taxpayer's invoices:
            foreach ($value['invoices'] as $invoice) {
                Invoice::create([...]);        // ← Individual INSERT per invoice
            }
        }
    }
}
```

**Impact**: Syncing 500 taxpayers with 3 invoices each = **2,000+ individual queries** inside a single transaction.

**Fix**: Use batch operations:

```php
// Collect all records first
$newTaxpayers = [];
$updateTaxpayers = [];

foreach ($data as $item) {
    if ($item['dataStatus'] === self::NEW) {
        $newTaxpayers[] = [...];
    } else {
        $updateTaxpayers[$item['id']] = [...];
    }
}

// Batch insert new records
Taxpayer::insert($newTaxpayers);

// Batch update with upsert
Taxpayer::upsert($updateTaxpayers, ['id'], ['name', 'address', ...]);
```

---

### 4.3 🟠 Invoice Export Loads Entire Dataset into Memory

**File**: `app/Exports/InvoiceExport.php`

```php
class InvoiceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Invoice::with(['taxpayer', 'taxpayer.zone'])
            ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
            ->get();  // ← Loads ALL matching records at once
    }
}
```

**Impact**: Exporting 50,000 invoices will consume 500MB+ of memory and likely cause a timeout or out-of-memory error.

**Fix**: Implement chunked reading and queue processing:

```php
class InvoiceExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
    public function query()
    {
        return Invoice::with(['taxpayer', 'taxpayer.zone'])
            ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
```

---

### 4.4 🟠 Fat Controllers with Inline Business Logic

**File**: `app/Http/Controllers/TaxpayerController.php` — `r_report()` method (~100+ lines)

```php
public function r_report(Request $request)
{
    $taxpayersByZone = Taxpayer::select('zone_id', DB::raw('COUNT(*) as total'))
        ->where('type', '=', Constants::TITRE)
        ->where(function ($q) { ... })
        ->groupBy('zone_id')
        ->with('zone')       // ← Eager load after groupBy - may not work as intended
        ->get();

    // 80+ more lines of statistics calculations...
}
```

**File**: `app/Http/Controllers/InvoiceController.php` — `index()` method

```php
public function index(Request $request, InvoicesDataTable $dataTable)
{
    $zone = Zone::all();                        // ← Uncached query every page load
    $tax_labels = TaxLabel::all();              // ← Uncached query every page load
    $role = Role::where(...)->first();          // ← Uncached query every page load
    $agent_recouvrements = $role->users()->get(); // ← Dependent query
    // ...
}
```

**Fix**: Move business logic to services and cache reference data:

```php
// In InvoiceController:
public function index(Request $request, InvoicesDataTable $dataTable)
{
    $zones = cache()->remember('zones', 3600, fn() => Zone::all());
    $taxLabels = cache()->remember('tax_labels', 3600, fn() => TaxLabel::all());
    // ...
}

// Move r_report logic to StatisticsService (already exists)
```

---

### 4.5 🟠 Queue & Cache Drivers Set to Synchronous/File

**Files**: `config/queue.php`, `config/cache.php`

| Setting | Current | Recommended |
|---------|---------|-------------|
| `QUEUE_CONNECTION` | `sync` | `database` or `redis` |
| `CACHE_DRIVER` | `file` | `redis` or `database` |
| `SESSION_DRIVER` | `file` | `database` or `redis` |

**Impact**:
- **Sync queue**: All jobs block the HTTP request. A PDF generation job taking 10s will freeze the user's browser for 10s.
- **File cache**: Slow at scale, no atomic operations, no distributed caching.

**Fix**: Switch to database driver (already configured in `config/queue.php`):

```env
QUEUE_CONNECTION=database
CACHE_DRIVER=database
SESSION_DRIVER=database
```

Then run the queue worker:

```bash
php artisan queue:work --tries=3
```

---

### 4.6 🟡 Missing Database Indexes

Based on the DataTable queries and controller filters, the following columns are frequently filtered/joined but may lack indexes:

| Table | Column(s) | Used In |
|-------|-----------|---------|
| `invoices` | `status`, `state`, `pay_status` | InvoicesDataTable filters |
| `invoices` | `taxpayer_id` | Joins and relationship lookups |
| `invoices` | `created_at` | Date range filters on all reports |
| `payments` | `invoice_id` | Payment lookups by invoice |
| `payments` | `status` | Payment status filters |
| `payments` | `taxpayer_id` | Taxpayer payment history |
| `taxpayers` | `zone_id`, `category_id`, `type` | Zone/category filtering |
| `taxpayers` | `tnif` | Unique lookups |

**Fix**: Create a migration to add missing indexes:

```bash
php artisan make:migration add_performance_indexes
```

```php
Schema::table('invoices', function (Blueprint $table) {
    $table->index(['status', 'created_at']);
    $table->index('taxpayer_id');
    $table->index('pay_status');
});

Schema::table('payments', function (Blueprint $table) {
    $table->index(['invoice_id', 'status']);
    $table->index('taxpayer_id');
});

Schema::table('taxpayers', function (Blueprint $table) {
    $table->index(['zone_id', 'type']);
    $table->index('category_id');
});
```

---

### 4.7 🟡 Observer Makes Redundant Queries

**File**: `app/Observers/PaymentObserver.php`

```php
private function resolveInvoice(Payment $payment): ?Invoice
{
    if (!empty($payment->invoice_id)) {
        $invoice = Invoice::find($payment->invoice_id);          // Query 1
        if ($invoice) {
            return $invoice;
        }
        return Invoice::where('invoice_no', $payment->invoice_id)->first(); // Query 2
    }
    // ...
}
```

**Fix**: Consolidate into a single query:

```php
private function resolveInvoice(Payment $payment): ?Invoice
{
    if (empty($payment->invoice_id)) {
        return null;
    }

    return Invoice::where('id', $payment->invoice_id)
        ->orWhere('invoice_no', $payment->invoice_id)
        ->first();
}
```

---

## 5. Code Quality & Architecture

### 5.1 🟠 Missing Form Request Validation in CRUD Controllers

**Affected Controllers**:

| Controller | Methods Missing Validation |
|-----------|----------------------------|
| `ZonesController` | `store()`, `update()` |
| `YearsController` | `store()`, `update()` |
| `TownsController` | `store()`, `update()` |
| `TicketController` | `store()` |

These controllers accept raw `Request` objects without any validation rules.

**Correct pattern already exists** in `app/Http/Requests/Api/StoreTaxpayerRequest.php` and `AuthRequest.php` — but most web controllers don't follow it.

**Fix**: Create Form Request classes:

```bash
php artisan make:request StoreZoneRequest
php artisan make:request UpdateZoneRequest
```

```php
// app/Http/Requests/StoreZoneRequest.php
class StoreZoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:zones,name',
            'commune_id' => 'required|exists:communes,id',
        ];
    }
}

// ZonesController.php
public function store(StoreZoneRequest $request) { ... }
```

---

### 5.2 🟠 Empty Method Stubs (Dead Code)

**File**: `app/Http/Controllers/InvoiceController.php`

```php
public function create() { }
public function store(Request $request) { }
public function edit(Invoice $invoice) { }
public function update(Request $request, Invoice $invoice) { }
public function destroy(Invoice $invoice) { }
```

**Impact**: These methods are registered as routes via `Route::resource()` but do nothing, creating endpoints that return `200 OK` with empty responses.

**Fix**: Either implement them or exclude from the resource route:

```php
Route::resource('/invoices', InvoiceController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
```

---

### 5.3 🟡 Enums Use `const` Instead of PHP 8.1 Native `enum`

**Files**: All 12 files in `app/Enums/`

```php
// Current (class-based constants):
class InvoiceStatusEnums
{
    public const DRAFT = 'DRAFT';
    public const ACCEPTED = 'ACCEPTED';
    public const REJECTED = 'REJECTED';
    // ...
}
```

**Impact**: No type safety — any string can be passed where an enum is expected. No IDE autocomplete for valid values.

**Fix**: Migrate to PHP 8.1 backed enums:

```php
enum InvoiceStatus: string
{
    case Draft = 'DRAFT';
    case Accepted = 'ACCEPTED';
    case Rejected = 'REJECTED';
    // ...
}
```

Then use in models with casting:

```php
protected $casts = [
    'status' => InvoiceStatus::class,
];
```

> **Note**: This is a significant refactor. Do it incrementally — one enum at a time.

---

### 5.4 🟡 No Query Scopes in Models

All filtering logic is scattered across controllers and DataTables.

**Current**:

```php
// In TaxpayerController:
Taxpayer::where('type', '=', Constants::TITRE)
    ->where(function ($q) { ... })
    ->groupBy('zone_id')
    ->get();
```

**Fix**: Define reusable scopes in models:

```php
// app/Models/Taxpayer.php
public function scopeOfType($query, string $type)
{
    return $query->where('type', $type);
}

public function scopeInZone($query, int $zoneId)
{
    return $query->where('zone_id', $zoneId);
}

public function scopeActive($query)
{
    return $query->where('state', TaxpayerStateEnums::APPROVED);
}
```

Usage:

```php
Taxpayer::ofType(Constants::TITRE)->inZone($zoneId)->active()->get();
```

---

### 5.5 🟡 PHPStan at Level 5 (Medium)

**File**: `phpstan.neon`

```yaml
parameters:
    level: 5
```

Level 5 catches undefined variables and basic type mismatches but misses:
- Return type violations (level 6)
- Union type handling (level 7)
- Strict null checking (level 8)

**Recommendation**: Incrementally raise to level 7:

```yaml
parameters:
    level: 7
```

Run and fix errors before committing.

---

### 5.6 🟡 CodeSniffer Line Length Set to 2000 Characters

**File**: `custom_ruleset.xml`

```xml
<rule ref="Generic.Files.LineLength">
    <properties>
        <property name="lineLimit" value="2000"/>
    </properties>
</rule>
```

**Impact**: This effectively disables line length checking. Lines with 500+ characters are unreadable.

**Fix**: Set a practical limit:

```xml
<property name="lineLimit" value="120"/>
<property name="absoluteLineLimit" value="150"/>
```

---

### 5.7 🟡 Inconsistent Service Layer Usage

The project has a well-structured `Services/` directory with `StatisticsService`, `InvoiceCodeBalanceService`, etc. However many controllers bypass services entirely:

| Controller | Should Use Service |
|-----------|--------------------|
| `TaxpayerController::r_report()` | `StatisticsService` (already exists) |
| `InvoiceController::index()` | Inline `Zone::all()`, `TaxLabel::all()` queries |
| `SyncInController::syncIn()` | Dedicated `SyncService` |

**Fix**: Consistently route all business logic through services. Controllers should only:
1. Validate input
2. Call a service method
3. Return a response

---

### 5.8 🟡 Commented-Out Code & Dead Routes

**File**: `routes/api.php` — Contains commented-out routes at the end of the file.

**General pattern**: Multiple files contain blocks of commented-out code that should either be restored or removed.

**Fix**: Remove all commented-out code. Use Git history to recover it if needed.

---

## 6. Database & Migrations

### 6.1 Schema Overview

**88 migration files** managing:

| Category | Tables | Starting ID |
|----------|--------|-------------|
| Users & Auth | `users`, `roles`, `permissions`, `password_resets` | Default |
| Revenue | `invoices`, `invoice_items`, `payments` | 100001 |
| Taxpayers | `taxpayers`, `taxpayer_taxables`, `taxables` | 10001 |
| Geography | `communes`, `cantons`, `towns`, `zones`, `ereas` | Default |
| Reference | `genders`, `id_types`, `years`, `categories`, `activities` | Default |
| Operations | `stock_requests`, `stock_transfers`, `user_logs` | Default |
| Sync | `sync_runs`, `invoice_code_balances` | Default |
| Queue | `jobs`, `job_batches`, `failed_jobs` | Default |

---

### 6.2 🟡 String Columns for Typed Data

**File**: `database/migrations/2024_02_02_093658_create_taxpayers_table.php`

| Column | Current Type | Should Be |
|--------|-------------|-----------|
| `longitude` | `string` | `decimal(10,7)` |
| `latitude` | `string` | `decimal(10,7)` |
| `id_type` | `string` | Foreign key to `id_types` |
| `gender` | `string` | Foreign key to `genders` |

**Impact**: No database-level validation, inefficient storage and filtering on text columns.

---

### 6.3 🟡 Nullable Foreign Keys Without Clear Intent

**Taxpayers table** has multiple nullable foreign keys: `town_id`, `erea_id`, `zone_id`.

**Risk**: Orphaned records where the foreign entity is `NULL` instead of a valid reference. Queries filtering by zone/town silently exclude these records.

**Fix**: For each nullable FK, decide:
- If truly optional: document why and add `->nullable()` comment
- If required: make non-nullable with a default or validation rule

---

### 6.4 🟡 No `down()` Method Best Practices

Several migrations have empty or incorrect `down()` methods. This prevents clean rollbacks.

**Fix**: Audit all migrations and ensure `down()` reverses `up()` correctly.

---

### 6.5 ✅ Good Practices Found

- Soft deletes on `taxpayers` and `users`
- Timestamps on all main tables
- Proper cascade configuration on recent migrations (`invoice_code_balances`)
- Unique constraints on `taxpayers.tnif` and `users.email`
- UUID fields on `invoices` and `payments` for API sync

---

## 7. Testing Gaps

### 7.1 Current State

**Test files found**: 2

| File | Coverage |
|------|----------|
| `tests/Feature/Auth/AuthenticationTest.php` | Login redirect only |
| `tests/Feature/Sync/SyncEndpointsTest.php` | Sync API: IP whitelist, auth, invoice/payment sync |

**Estimated coverage**: ~5% of application logic.

---

### 7.2 🔴 Missing Test Suites

| Area | Priority | Why |
|------|----------|-----|
| **Invoice Lifecycle** | Critical | Core business flow: Draft → Accepted → Approved → Paid |
| **Payment Processing** | Critical | Money handling — must be verified |
| **Taxpayer CRUD** | High | Core data management |
| **Authorization/Policies** | High | Ensures role-based access works |
| **Workflow State Machine** | High | Validates state transitions (via `InvoiceWorkflowSubscriber`) |
| **DataTable Queries** | Medium | Ensures filters return correct data |
| **Export/Import** | Medium | Validates data integrity in Excel operations |
| **Notification Dispatch** | Low | Verifies emails/notifications sent on events |

---

### 7.3 Recommended Test Strategy

```bash
# Generate test scaffolding
php artisan make:test InvoiceLifecycleTest
php artisan make:test PaymentProcessingTest
php artisan make:test TaxpayerCrudTest
php artisan make:test AuthorizationTest
```

**Example — Invoice Lifecycle Test**:

```php
it('transitions invoice through full lifecycle', function () {
    $taxpayer = Taxpayer::factory()->create();
    $invoice = Invoice::factory()->for($taxpayer)->create(['status' => 'DRAFT']);

    // Accept
    $invoice->update(['status' => InvoiceStatusEnums::ACCEPTED]);
    expect($invoice->refresh()->status)->toBe('ACCEPTED');

    // Approve
    $invoice->update(['status' => InvoiceStatusEnums::APPROVED]);
    expect($invoice->refresh()->status)->toBe('APPROVED');

    // Pay
    Payment::factory()->for($invoice)->create();
    expect($invoice->refresh()->pay_status)->toBe('PAID');
});
```

---

## 8. Frontend Optimization

### 8.1 🟠 Three Rich Text Editors Bundled

**Packages installed**:
- `@ckeditor/ckeditor5-*` (6 packages) — ~500KB
- `tinymce: ^5.8.2` — ~400KB
- `quill: ^1.3.7` — ~50KB

**Total overhead**: ~950KB of JavaScript for text editing.

**Fix**: Choose one editor (CKEditor 5 is the most feature-complete) and remove the others:

```bash
npm uninstall tinymce quill
```

---

### 8.2 🟠 jQuery Alongside Alpine.js

Both `jquery: 3.7.1` and `alpinejs: ^3.7.1` are installed. Alpine.js is the modern choice for Livewire/Laravel projects and can replace jQuery for DOM manipulation.

**Fix**: Gradually replace jQuery usage with Alpine.js directives:

```html
<!-- Before (jQuery) -->
<script>$('#modal').modal('show');</script>

<!-- After (Alpine) -->
<div x-data="{ open: false }">
    <button @click="open = true">Open</button>
    <div x-show="open">Modal content</div>
</div>
```

---

### 8.3 🟡 11+ DataTables Packages

```json
"datatables.net": "^1.13.8",
"datatables.net-bs5": "^1.13.8",
"datatables.net-buttons": "^2.4.2",
"datatables.net-buttons-bs5": "^2.4.2",
"datatables.net-colreorder": "^1.7.0",
"datatables.net-colreorder-bs5": "^1.7.0",
"datatables.net-datetime": "^1.5.1",
"datatables.net-fixedcolumns": "^4.3.0",
"datatables.net-fixedcolumns-bs5": "^4.3.0",
"datatables.net-responsive": "^2.5.0",
"datatables.net-responsive-bs5": "^2.5.0"
```

**Fix**: Audit which features are actually used. If only basic DataTables with sorting/filtering are needed, most plugins can be removed.

---

### 8.4 🟡 Deprecated/Redundant Chart Libraries

- `flot: ^4.2.6` — **Deprecated**, last updated 2021
- `apexcharts: 3.45.1` — Modern, actively maintained
- `chart.js: ^4.4.1` — Modern, actively maintained

**Fix**: Remove Flot and standardize on one library:

```bash
npm uninstall flot
```

---

## 9. Configuration & Infrastructure

### 9.1 Environment & Deployment Readiness

| Check | Status | Details |
|-------|--------|---------|
| `.env.example` completeness | ⚠️ | Contains hardcoded password |
| Production debug mode | 🔴 | Defaults to `true` |
| Queue driver | ⚠️ | Sync (blocking) |
| Cache driver | ⚠️ | File (slow) |
| Session driver | ⚠️ | File (not scalable) |
| SSL enforcement | ❓ | Not verified |
| Rate limiting | ✅ | 60 req/min configured |
| Backup configuration | ✅ | Spatie backup configured |
| Logging | ✅ | Stack + daily + sync channels |
| Feature flags | ✅ | `config/features.php` with env-gated flags |

---

### 9.2 Recommended `.env` for Production

```env
APP_DEBUG=false
APP_ENV=production

CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_ENCRYPT=true
QUEUE_CONNECTION=redis

DB_STRICT=true

LIVEWIRE_DEBUG=false

CORS_ALLOWED_ORIGINS=https://your-domain.com

BACKUP_ARCHIVE_PASSWORD=   # Set via server provisioning, NOT in .env.example
```

---

### 9.3 Laravel 11 Upgrade Consideration

The project is on **Laravel 10** (supported until February 2027). Laravel 11 brings:

- Simplified directory structure (no `Kernel.php`)
- Health check route built-in
- Per-second rate limiting
- Improved artisan commands

**Recommendation**: Plan an upgrade when approaching Laravel 10 EOL. Review [Laravel 11 upgrade guide](https://laravel.com/docs/11.x/upgrade) for breaking changes.

---

## 10. Improvement Roadmap

### 🔴 Phase 1 — Critical Fixes (Week 1)

| # | Task | File(s) | Effort |
|---|------|---------|--------|
| 1 | Fix duplicate policy key | `AuthServiceProvider.php` | 15 min |
| 2 | Fix `/v1/v1` route nesting | `routes/api.php` | 15 min |
| 3 | Remove hardcoded password from `.env.example` | `.env.example` | 5 min |
| 4 | Fix migration `down()` dropping wrong table | `create_towns_table.php` | 5 min |
| 5 | Change `APP_DEBUG` default to `false` | `config/app.php` | 5 min |
| 6 | Change Livewire debug to env-driven | `config/livewire.php` | 5 min |

---

### 🟠 Phase 2 — Security & Performance (Week 2-3)

| # | Task | File(s) | Effort |
|---|------|---------|--------|
| 7 | Add eager loading to `InvoicesDataTable` | `InvoicesDataTable.php` | 30 min |
| 8 | Create Invoice, Payment, Taxpayer policies | `app/Policies/` | 2-3 hrs |
| 9 | Add `$this->authorize()` to all controllers | All controllers | 2-3 hrs |
| 10 | Refactor `SyncInController` to batch operations | `SyncInController.php` | 3-4 hrs |
| 11 | Add `WithChunkReading` to `InvoiceExport` | `InvoiceExport.php` | 30 min |
| 12 | Restrict CORS to specific origins | `config/cors.php` | 15 min |
| 13 | Enable session encryption | `config/session.php` | 5 min |
| 14 | Add database indexes migration | New migration | 1 hr |

---

### 🟡 Phase 3 — Code Quality (Week 3-4)

| # | Task | File(s) | Effort |
|---|------|---------|--------|
| 15 | Create FormRequest classes for CRUD controllers | `app/Http/Requests/` | 2-3 hrs |
| 16 | Remove empty method stubs in `InvoiceController` | `InvoiceController.php` | 15 min |
| 17 | Move business logic to services | Controllers → Services | 4-6 hrs |
| 18 | Add query scopes to models | `app/Models/` | 2-3 hrs |
| 19 | Remove commented-out code and dead routes | Multiple files | 1-2 hrs |
| 20 | Raise PHPStan to level 7 | `phpstan.neon` | 4-8 hrs |
| 21 | Fix CodeSniffer line length | `custom_ruleset.xml` | 5 min + fixes |

---

### 🟢 Phase 4 — Testing & Frontend (Week 4-6)

| # | Task | File(s) | Effort |
|---|------|---------|--------|
| 22 | Write invoice lifecycle tests | `tests/Feature/` | 4-6 hrs |
| 23 | Write payment processing tests | `tests/Feature/` | 3-4 hrs |
| 24 | Write authorization tests | `tests/Feature/` | 2-3 hrs |
| 25 | Remove redundant text editors | `package.json` | 1-2 hrs |
| 26 | Remove deprecated Flot charts | `package.json` | 1 hr |
| 27 | Audit DataTables plugin necessity | `package.json` | 1-2 hrs |

---

### 🔵 Phase 5 — Modernization (Month 2+)

| # | Task | Files | Effort |
|---|------|-------|--------|
| 28 | Migrate enums to PHP 8.1 native `enum` | `app/Enums/` + all references | 8-12 hrs |
| 29 | Switch queue/cache/session to Redis | `.env`, config files | 2-3 hrs |
| 30 | Phase out jQuery for Alpine.js | Blade templates, JS files | 8-16 hrs |
| 31 | Enable MySQL strict mode (after fixing queries) | `config/database.php` | 4-8 hrs |
| 32 | Plan Laravel 11 upgrade | All files | 16-24 hrs |

---

## Appendix A: Architecture Inventory

| Component | Count |
|-----------|-------|
| Eloquent Models | 30 |
| Controllers (Web) | ~15 |
| Controllers (API) | ~5 |
| Livewire Components | 23 |
| DataTable Classes | 37 |
| Service Classes | 10+ |
| Contract Interfaces | 8 |
| Background Jobs | 5 |
| Enum Classes | 12 |
| Notifications | 6 |
| Policies | 3 |
| Observers | 2 |
| Custom Middleware | 5 |
| Service Providers | 8 |
| Migrations | 88 |
| Blade Views | 54 |

## Appendix B: Dependency Overview

### Production (Key Packages)

| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^10.0 | Core framework |
| `livewire/livewire` | ^3.0 | Reactive components |
| `spatie/laravel-permission` | ^5.10 | RBAC |
| `yajra/laravel-datatables` | ^10.0 | Server-side DataTables |
| `maatwebsite/excel` | ^3.1 | Excel import/export |
| `barryvdh/laravel-dompdf` | ^2.0 | PDF generation |
| `spatie/laravel-backup` | ^9.0 | Database backups |
| `zerodahero/laravel-workflow` | ^5.0 | State machine |
| `laravel/sanctum` | ^3.0 | API authentication |

### Dev (Key Packages)

| Package | Version | Purpose |
|---------|---------|---------|
| `pestphp/pest` | ^2.36 | Testing |
| `phpstan/phpstan` | ^1.12 | Static analysis |
| `rector/rector` | ^1.2 | Automated refactoring |
| `laravel/pint` | ^1.0 | Code style |
| `barryvdh/laravel-debugbar` | ^3.10 | Debug toolbar |

---

*End of diagnostic report*
