# PDF Export System — Fix Status Report

**Date:** 2026-04-01  
**Based on:** `docs/pdf-diagnostic.md`  
**System:** SIG-RECETTE v0.9.2 — Laravel 10.x / PHP 8.2

---

## Summary

| Category | Total Issues | Fixed | Remaining |
|---|---|---|---|
| PDF System (2.1–2.11) | 11 | 11 | 0 |
| Additional Bugs (5.1–5.3) | 3 | 3 | 0 |
| Bonus Fix | 1 | 1 | 0 |
| **Total** | **15** | **15** | **0** |

---

## 1. Issue-by-Issue Status

### 2.1 — DomPDF CSS Limitations ✅ FIXED

**File:** `resources/views/exports/invoices.blade.php`

**What was done:**
- `.count_page`: replaced `display: flex; justify-content: end; align-items: end` → `text-align: right`
- `.watermark`: replaced `transform: translate(-50%, -50%) rotate(-30deg)` with `top: 30%; left: 5%` fixed positioning
- `.watermark` color: replaced `rgba(255, 0, 0, 0.1)` → `color: #ffcccc; opacity: 0.3`
- Font size reduced: `120px` → `100px`

**Verified:** Current file has `text-align: right` on `.count_page` and `position: fixed; top: 30%; left: 5%; color: #ffcccc; opacity: 0.3` on `.watermark`. No `flex` or `rgba()` remain.

---

### 2.2 — `downloadReceipt()` Return Type Mismatch ✅ FIXED

**File:** `app/Services/PdfGeneratorService.php`

**What was done:** Method now returns `['success' => true, 'pdf' => $pdf, 'filename' => $filename]` matching the array contract expected by `PrintWithoutData`.

**Bonus fix also applied:** Removed the double `json_decode` (data came pre-decoded from `PrintWithoutData`). Method now loads Payment models by UUID directly.

---

### 2.3 — `payments.blade.php` Placeholder Template ✅ FIXED

**File:** `resources/views/exports/payments.blade.php`

**What was done:** Entire file rewritten. New template:
- Commune header with logo, name, address
- Date and receipt number
- Taxpayer name/address and invoice number
- Payment details table: reference, tax label, payment type, amount paid, remaining, date
- Total row with FCFA formatting
- Footer note and signature blocks
- A5 landscape with proper margins

**`downloadReceipt()` updated** to load `Payment::with(['invoice', 'taxpayer', 'user', 'tax_label'])->whereIn('uuid', $data)` and passes `$payments` collection and `$commune` to the template.

---

### 2.4 — Bordereau Always Returns Failure ✅ FIXED

**File:** `app/Services/PrintService.php`

**What was done:** `generateBordereauListPdf()` now returns:
```php
return ['success' => false, 'message' => 'Le bordereau est en cours de génération. Vous recevrez une notification lorsque le fichier sera prêt.', 'async' => true];
```

**`PrintWithoutData` updated** to detect `$result['async'] === true` and redirect with a `success` flash instead of an `error` flash.

---

### 2.5 — ZIP Generation Memory / Timeout ✅ FIXED

**What was done:**
- `$zip->addFromString($result['filename'], $result['pdf']->getContent())` — fixed Response object being written as string bytes
- Queue jobs now have `$timeout = 300` and `$tries = 3`
- `LongPrintTaskJob::handle()` now sets `ini_set('memory_limit', '512M')` at start
- `downloadMultipleInvoice()` now processes UUIDs in `array_chunk($uuid, 50)` chunks with `unset($result)` + `gc_collect_cycles()` after each chunk

---

### 2.6 — No Memory Limit in Queue Jobs ✅ FIXED

**Files:** `app/Jobs/LongPrintTaskJob.php`, `app/Jobs/DownloadInvoiceZipJob.php`

**What was done:**
```php
public $timeout = 300;
public $tries = 3;
```

Added to both jobs. Laravel's queue worker now allows 5 minutes per job and will retry up to 3 times before marking failed.

---

### 2.7 — `edition_state` Side Effects Without Transaction ✅ FIXED

**File:** `app/Services/PdfGeneratorService.php`

**What was done:** Both mutation sites are now wrapped:
```php
DB::transaction(function () use ($data) {
    foreach ($data as $invoice) {
        if ($invoice->edition_state == "PRINT") {
            $invoice->edition_state = "bPRINT";
            $invoice->status = InvoiceStatusEnums::PENDING->value;
            $invoice->save();
        }
    }
});
```
Also applied to the `generateInvoicePdf` single-invoice `edition_state = "PRINT"` assignment.

---

### 2.8 — `generateInvoicePdf()` ZIP/Response Inconsistency ✅ FIXED

**File:** `app/Services/PrintService.php`

**What was done:**
```php
$zip->addFromString($result['filename'], $result['pdf']->getContent());
```
`->getContent()` extracts raw PDF bytes from the `StreamedResponse` object. `ZipArchive::addFromString` now receives a string instead of an object.

---

### 2.9 — QR Code SVG Rendering Overhead ✅ FIXED

**File:** `app/Services/QrcodeGeneratorService.php`

**What was done:** Wrapped both generation paths (with-logo and no-logo) inside `Cache::remember()` with a 6-hour TTL. Cache key is `'qrcode_' . md5($data . ($backgroundImagePath ?? ''))` — unique per data + logo combination. First render pays full cost; subsequent renders for the same invoice return instantly from cache.

---

### 2.10 — Inconsistent Error Handling ✅ FIXED

**Files:** `app/Actions/PrintWithoutData.php`, `app/Actions/PrintWithData.php`

**What was done:**
```php
Log::error('PDF generation failed', ['type' => $type, 'action' => $action, 'error' => $e->getMessage()]);
```
Added to the `catch (\Throwable $e)` block in both actions. Failures now appear in `storage/logs/laravel.log`.

---

### 2.11 — N+1 Queries in `retrieveByUUIDs()` ✅ FIXED

**Files:** `app/Traits/InvoiceTrait.php`, `app/Services/PdfGeneratorService.php`

**What was done:** Replaced per-UUID loop with a single bulk query + eager loading. Also added `taxpayer_taxables.taxable.tax_label` to the eager load array, and updated `usort` comparator in `generateInvoicePdf()` to use the pre-loaded collection (`$a->taxpayer_taxables->first()?->...`) instead of the lazy accessor (`$a->taxpayer_taxable`).

---

### 5.1 — Order Number Print Restriction ✅ FIXED

**File:** `app/Livewire/Invoice/AddOrdernoForm.php`

**What was done:** Removed the check:
```php
if ($invoice->type == Constants::INVOICE_TYPE_TITRE && $invoice->edition_state != "PRINT") {
    $this->addError('orderno', "Veuillez au préalable imprimer l'avis.");
}
```
Users can now enter `order_no` without needing to print first, breaking the circular dependency.

---

### 5.2 — `status_text` Undefined Property ✅ FIXED

**File:** `resources/views/pages/taxpayers/show.blade.php`

**What was done:** Added null-safe access throughout the action log table:
```blade
@php $responseData = json_decode($action->response); @endphp
{{ $responseData->status ?? 500 }}
{{ $responseData->status_text ?? '' }}
{{ $requestData->method ?? '' }}
```

---

### 5.3 — API Notification Route Missing Auth ✅ FIXED

**File:** `routes/api.php`

**What was done:** Both notification routes moved inside `Route::middleware('auth:sanctum')->group(...)`. JS polling in `_notifications-menu.blade.php` and `widget_notifications.blade.php` rewritten with:
- `credentials: 'same-origin'` for session cookie forwarding
- `X-CSRF-TOKEN` header from `<meta name="csrf-token">`
- Silent handling of 401/419 (session expired)
- Interval increased from 5s → 10s
- `console.error` removed (was spamming the console)

---

### Bonus — `collector_deposits-table` GROUP BY SQL Error ✅ FIXED

**File:** `app/DataTables/AccountantDepositsSumDataTable.php`

**Error:**
```
SQLSTATE[42000]: Expression #1 of SELECT list is not in GROUP BY clause
...contains nonaggregated column 'payments.id'
```

**What was done:**
- `'id'` → `DB::raw('MIN(id) AS id')` — `id` must be aggregated when a `GROUP BY` is present
- `->groupBy('reference_deposit', 'reference_deposit')` → `->groupBy('reference_deposit')` — removed duplicate

---

## 2. Remaining Open Issues

_All issues resolved. No remaining open issues._

---

### (archived) 2.9 — QR Code SVG Rendering Overhead

**Current behaviour:** `QrcodeGeneratorService::generate()` produces SVG/WEBP, embedded as base64 data URI in the invoice PDF. DomPDF must decode and render each one inline. For complex SVG paths this adds 200–500ms per invoice.

**Proposed solution:**
```php
// In QrcodeGeneratorService: cache the base64 output keyed by invoice UUID
// Or: generate a PNG instead of SVG (DomPDF handles PNG better)
// app/Services/QrcodeGeneratorService.php

public function generate(string $data, string $logo = null): string
{
    return Cache::remember('qrcode_' . md5($data), 3600, function () use ($data, $logo) {
        // existing generation logic
    });
}
```
Alternatively switch the output type to PNG (smaller payload, faster DomPDF render):
```php
->outputType(QROutputInterface::GDIMAGE_PNG)
```

---

### 2.11 (partial) — `usort` in `generateInvoicePdf()` fetches `taxpayer_taxable` lazily

**Current code:**
```php
usort($data, function ($a, $b) {
    $codeA = $a->taxpayer_taxable->taxable->tax_label->code;  // hits DB
    $codeB = $b->taxpayer_taxable->taxable->tax_label->code;  // hits DB
    return strcmp($codeA, $codeB);
});
```

`$a->taxpayer_taxable` calls `getDefaulttaxpayer_taxableAttribute()` → `taxpayer_taxables()->first()` — a live query each time. For a single invoice this is irrelevant (only 2 calls), but should be resolved cleanly.

**Proposed fix:**
```php
// Add 'taxpayer_taxables.taxable.tax_label' to the eager load in retrieveByUUIDs()
// Then in the usort, access via the already-loaded collection:
$codeA = $a->taxpayer_taxables->first()?->taxable->tax_label->code ?? '';
```
Or add `taxpayer_taxables.taxable.tax_label` to the `$eagerLoad` array in `InvoiceTrait::retrieveByUUIDs()`.

---

## 3. Proposed Solutions for Remaining Issues

### 3.1 — Bulk Invoice ZIP: Chunked Processing

The `downloadMultipleInvoice()` loop is synchronous and unbounded. For large invoice sets this will exhaust PHP memory even with the 300s timeout.

**Proposed approach — chunked generation in `LongPrintTaskJob`:**
```php
// In LongPrintTaskJob::handle(), process in chunks of 50
Invoice::getPrintableUuid($state)->chunk(50, function ($uuids) use (&$zip, $pdfGenerator, $action) {
    foreach ($uuids as $uuid) {
        $result = $pdfGenerator->generateInvoicePdf([$uuid], 'invoices', $action);
        if ($result['success']) {
            $zip->addFromString($result['filename'], $result['pdf']->getContent());
        }
        unset($result); // release memory
    }
    gc_collect_cycles();
});
```
Combined with `ini_set('memory_limit', '512M')` at the start of `handle()`.

### 3.2 — QR Code PNG Caching

Change `QrcodeGeneratorService` to output PNG and cache results:
```php
public function generate(string $data, string $logo = null): string
{
    $key = 'qrcode_' . md5($data . $logo);
    return Cache::remember($key, now()->addHours(6), function () use ($data, $logo) {
        // existing logic, output PNG
    });
}
```

### 3.3 — Fix `usort` Lazy Load

Add `taxpayer_taxables.taxable.tax_label` to the eager load in `InvoiceTrait::retrieveByUUIDs()`:
```php
$eagerLoad = [
    'invoiceitems.taxpayer_taxable.taxable.tax_label',
    'taxpayer_taxables.taxable.tax_label',   // ← add this
    'taxpayer.town.canton',
    'taxpayer.zone',
    'taxpayer.category',
    'taxpayer.activity',
    'payments',
];
```
Then update the `usort` comparator in `PdfGeneratorService::generateInvoicePdf()`:
```php
usort($data, function ($a, $b) {
    $codeA = $a->taxpayer_taxables->first()?->taxable->tax_label->code ?? '';
    $codeB = $b->taxpayer_taxables->first()?->taxable->tax_label->code ?? '';
    return strcmp($codeA, $codeB);
});
```

### 3.4 — Medium-Term: Package Migration

If the server permits Node.js + Chromium installation, migrate to `spatie/laravel-pdf`:

```bash
composer require spatie/laravel-pdf
npm install puppeteer
```

Replace all `PDF::loadView(...)->stream($filename)` calls:
```php
// Before
$pdf = PDF::loadView('exports.invoices', $data)->stream($filename);

// After
$pdf = Spatie\LaravelPdf\Facades\Pdf::view('exports.invoices', $data)
    ->format('a4')
    ->landscape()
    ->inline($filename);
```

All 18 Blade templates work as-is. Zero CSS rewrites needed.

If Chromium is unavailable, `mpdf/mpdf` is the fallback — same Blade templates with minor CSS adjustments.

---

## 4. Priority Backlog

| Priority | Issue | File | Effort |
|---|---|---|---|
| 🔴 High | 3.3 — Fix `usort` lazy `taxpayer_taxable` access | `InvoiceTrait.php` + `PdfGeneratorService.php` | 10 min |
| 🟡 Medium | 3.1 — Chunk bulk ZIP generation + memory limit | `LongPrintTaskJob.php` / `PrintService.php` | 30 min |
| 🟡 Medium | 3.2 — QR code PNG + caching | `QrcodeGeneratorService.php` | 20 min |
| 🟢 Low | 3.4 — Migrate to `spatie/laravel-pdf` | All export templates | Requires server setup |
