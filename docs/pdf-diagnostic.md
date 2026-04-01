# PDF Export System — Full Diagnostic Report

**Date:** 2026-03-31  
**System:** SIG-RECETTE v0.9.2 — Laravel 10.x / PHP 8.2  
**Package:** `barryvdh/laravel-dompdf` v2.2.0 → `dompdf/dompdf` v2.0.8

---

## 1. Architecture Overview

### Entry Points
| Route | Controller/Action | Purpose |
|---|---|---|
| `GET /generate-pdf/{data?}/{type?}/{action?}/{id?}` | `PrintController@download` → `PrintWithoutData` | Ad-hoc PDF generation |
| `GET /generatepdf/{printFile}/{type?}/{action?}` | `PrintController@downloadWithPrintData` → `PrintWithData` | PDF from saved PrintFile |
| `GET /print-all-invoice` | `PrintController@downloadMultipleInvoicePdf` → `DownloadInvoiceZipJob` | ZIP of multiple invoice PDFs |
| `GET /invoices/{invoice}` | `InvoiceController@show` | Invoice detail view (browser-rendered, not PDF) |

### Service Layer
```
PrintController
  └── PrintWithoutData / PrintWithData (Actions)
        └── PrintService::processType()
              ├── type=1  → PdfGeneratorService::downloadReceipt()
              ├── type=2  → handleTypeTwo() — dispatches to 7 sub-methods
              ├── type=6-16 → PdfGeneratorService::generateState*Pdf()
              ├── type=11 → PdfGeneratorService::generataxpayerFormPdf()
              ├── type=77 → PdfGeneratorService::generateInvoicePdf() (relance)
              └── default → PdfGeneratorService::generateInvoicePdf()
```

### Templates (18 Blade views in `resources/views/exports/`)
| Template | Used By | Paper |
|---|---|---|
| `invoices.blade.php` | Single invoice/relance | A4 portrait |
| `invoices-list.blade.php` | Bordereau list | A4 landscape |
| `invoices-distribution.blade.php` | Distribution sheets | A4 landscape |
| `invoices-recouvrement.blade.php` | Recovery sheets | A4 landscape |
| `invoices-registre.blade.php` | Registry journal | A4 landscape |
| `invoices-journal-receveur.blade.php` | Receiver journal | A4 landscape |
| `payments.blade.php` | Payment receipts | A5 landscape |
| `taxpayer-form.blade.php` | Taxpayer info sheet | A4 portrait |
| `livre-journal-regie.blade.php` | Ledger journal | A4 landscape |
| `state-*.blade.php` (6 files) | Various state reports | A4 landscape |
| `registre-journal-des-declarations-prealables-des-usagers.blade.php` | COMPTANT invoices | A4 landscape |

---

## 2. Identified Issues

### 2.1 CRITICAL — DomPDF CSS Limitations Causing Rendering Failures

**`invoices.blade.php`** uses CSS features **not supported by DomPDF**:
- **`display: flex`** (line 112) — DomPDF has ZERO flexbox support
- **`position: fixed`** (line 119) — Partially supported, unreliable
- **`transform: translate(-50%, -50%) rotate(-30deg)`** (line 122) — NOT supported
- **`rgba()` colors** (line 124) — Partial support, inconsistent rendering

**Impact:** The watermark ("Avis Affichage", "Relance") renders incorrectly or not at all. The `.count_page` element using `display: flex` + `justify-content: end` silently fails.

### 2.2 CRITICAL — `downloadReceipt()` Return Type Mismatch

```php
// PdfGeneratorService.php L119-123
public function downloadReceipt($data)
{
    $data = json_decode($data, true);
    $filename = "receipt-" . $data[2] . '-' . Str::random(8) . ".pdf";
    return PDF::loadView('exports.payments', ['data' => $data])->stream($filename);
}
```

Returns an `Illuminate\Http\Response` directly, but `PrintService::handleReceipt()` declares return type `array` and `PrintWithoutData` checks `$result['success']`. This will throw:
```
Cannot use object of type Illuminate\Http\Response as array
```

### 2.3 CRITICAL — `payments.blade.php` Template is Hardcoded Placeholder

The receipt template is a **non-functional dummy** with:
- Hardcoded `Invoice ID: 834847473`
- Hardcoded `John Doe`, `123 Acme Str.`, `Laravel Daily`
- Hardcoded `Total: $129.00 USD`
- The `$data` variable is expected to be a plain array from `json_decode`, but the template iterates `$data as $item` expecting `$item['name']`, `$item['quantity']`, `$item['description']`, `$item['price']` — none of which match the actual payment model structure

### 2.4 HIGH — Bordereau Generation Always Returns Failure

```php
// PrintService.php L60-69
private function generateBordereauListPdf($action, $data, $pdfGenerator): array
{
    LongPrintTaskJob::dispatch(...);
    // The actual PDF generation code is COMMENTED OUT
    return ['success' => false, 'message' => 'Invalid data structure.'];
}
```

The bordereau list is dispatched as a queue job but the method **always returns `['success' => false]`**. Users see an error flash message even though the PDF is being generated asynchronously. The UX is broken — users don't know the PDF is being built in the background.

### 2.5 HIGH — ZIP Generation Happens Synchronously in Queue Job

`downloadMultipleInvoice()` in `PrintService` generates one PDF per invoice sequentially:
```php
foreach ($uuid as $invoiceUid) {
    $result = $pdfGenerator->generateInvoicePdf([$invoiceUid], 'invoices', $action);
    // ...
}
```

For 200+ invoices, this creates massive memory pressure and can timeout. Each PDF generation loads the Blade template, renders HTML, converts to PDF — no streaming or chunking.

### 2.6 HIGH — No Memory Limit Management

DomPDF is memory-intensive. There is no `ini_set('memory_limit', ...)` before bulk PDF operations. The queue job has no `$timeout` or `$tries` property set, so it uses Laravel defaults (60s, 1 try). Bulk operations with 100+ invoices will frequently timeout.

### 2.7 MEDIUM — `edition_state` Side Effect in `generateBordereauListPdf()`

```php
// PdfGeneratorService.php L268-273
foreach ($data as $invoice) {
    if ($invoice->edition_state == "PRINT") {
        $invoice->edition_state = "bPRINT";
        $invoice->status = InvoiceStatusEnums::PENDING->value;
        $invoice->save();
    }
}
```

PDF generation **mutates invoice status** as a side-effect. If the PDF fails mid-generation after some invoices are updated, data is left in an inconsistent state. No transaction wrapping.

### 2.8 MEDIUM — `generateInvoicePdf()` Return Inconsistency

When `$action == null`, a `stream()` response is returned. When `$action == 2`, same. But the array includes both `'pdf'` (the stream response) and `'filename'` (string). The caller `PrintWithoutData` does `return $result['pdf']` which works, but `downloadMultipleInvoice()` does `$zip->addFromString($result['filename'], $result['pdf'])` — which tries to write an HTTP Response object as a string. This silently fails or corrupts the ZIP.

### 2.9 MEDIUM — QR Code SVG Embedded as Base64 Data URI

`QrcodeGeneratorService` generates SVG/WEBP images. When returned as base64, DomPDF must decode and render inline. Large commune logos plus QR codes increase PDF size and rendering time significantly. SVG with complex paths is particularly expensive for DomPDF's CPDF backend.

### 2.10 LOW — Inconsistent Error Handling

- `PrintWithoutData` catches `\Throwable` and returns an error view
- `PrintWithData` does the same but with different flash message logic
- Neither logs the error — failures are silently swallowed

### 2.11 LOW — N+1 Query in Invoice Templates

`invoices.blade.php` accesses:
- `$data->taxpayer->social_work` / `$data->taxpayer->name`
- `$data->taxpayer->town?->canton->name`
- `$data->taxpayer->zone?->name`
- `$data->taxpayer->category?->name`
- `$data->taxpayer->activity?->name`

Each relationship is lazy-loaded. For single invoices this is acceptable but for bulk generation (`downloadMultipleInvoice`) it multiplies queries.

---

## 3. DomPDF vs Alternatives Assessment

### Current: `dompdf/dompdf` v2.0.8

| Aspect | Rating | Notes |
|---|---|---|
| CSS Support | Poor | No flexbox, no grid, limited transforms, no `rgba()` |
| Memory Usage | High | ~50-100MB per complex page |
| Speed | Slow | 1-5 seconds per page for complex layouts |
| Multi-page Tables | Poor | Breaks pagination, `page-break-inside: avoid` unreliable |
| Unicode/UTF-8 | Good | With proper font configuration |
| Image Support | Fair | Base64 images work, remote images need `enable_remote` |
| Stability | Fair | Frequent silent CSS failures, no clear error reporting |
| Laravel Integration | Excellent | `barryvdh/laravel-dompdf` is mature |

### Alternative 1: `spatie/laravel-pdf` (Browsershot + Chromium)

| Aspect | Rating | Notes |
|---|---|---|
| CSS Support | Excellent | Full CSS3, flexbox, grid, transforms — Chrome rendering |
| Memory Usage | Medium | Spawns Chrome process, needs ~100MB for Chrome |
| Speed | Medium | 2-4 seconds cold start, ~1s warm |
| Multi-page Tables | Excellent | Native browser pagination |
| Server Requirement | **Puppeteer + Node.js + Chromium** | Must be installed on server |
| Laravel Integration | Excellent | Fluent API, `Pdf::view('template')->save()` |
| Migration Effort | **Low** | Same Blade templates, just remove DomPDF-specific hacks |

**Verdict:** Best option if Node.js + Chromium can be installed on the server. Zero CSS limitations. Same Blade templates work as-is.

### Alternative 2: `tecnickcom/tcpdf` (via `elibyy/tcpdf-laravel`)

| Aspect | Rating | Notes |
|---|---|---|
| CSS Support | None | Programmatic API only, no HTML rendering |
| Speed | Fast | Direct PDF object manipulation |
| Migration Effort | **Very High** | Must rewrite ALL 18 templates as PHP code |

**Verdict:** Not recommended — would require rewriting the entire view layer.

### Alternative 3: `mpdf/mpdf`

| Aspect | Rating | Notes |
|---|---|---|
| CSS Support | Good | Better than DomPDF: `position: fixed` works, basic transforms |
| Memory Usage | Medium | Better than DomPDF for large documents |
| Speed | Medium | Faster than DomPDF for multi-page |
| Multi-page Tables | Good | Better table splitting support |
| Laravel Integration | Fair | `carlos-meneses/laravel-mpdf` or manual setup |
| Migration Effort | **Low** | Same HTML/CSS templates, minor adjustments |

**Verdict:** Good middle ground. Better CSS support than DomPDF without needing Chromium. Easiest migration.

### Alternative 4: Wkhtmltopdf (via `barryvdh/laravel-snappy`)

| Aspect | Rating | Notes |
|---|---|---|
| CSS Support | Good | WebKit-based rendering |
| Speed | Fast | Fastest HTML-to-PDF solution |
| Server Requirement | wkhtmltopdf binary | Single binary, easy to install |
| Stability | Poor | Project is **abandoned/unmaintained** since 2020 |
| Laravel Integration | Good | `barryvdh/laravel-snappy` |

**Verdict:** Not recommended due to abandoned upstream.

---

## 4. Recommendation

### Short-term (fix current DomPDF issues): **APPLIED**

1. ~~Fix CSS in `invoices.blade.php`~~ ✅ Replaced `display: flex`, `transform`, `rgba()` with DomPDF-compatible equivalents
2. ~~Fix `downloadReceipt()` return type~~ ✅ Now returns `['success' => true, 'pdf' => ..., 'filename' => ...]` array
3. ~~Fix `generateBordereauListPdf` async message~~ ✅ Returns success flash message instead of error
4. Wrap side-effect status updates in DB transactions (deferred — low priority)
5. ~~Fix bulk ZIP generation~~ ✅ Uses `->getContent()` to extract raw bytes from Response
6. ~~Add `$timeout = 300` and `$tries = 3` to queue jobs~~ ✅ Applied to LongPrintTaskJob and DownloadInvoiceZipJob

### Medium-term (package migration): **Recommended if server allows**

**Best choice: `spatie/laravel-pdf`** (if Node.js + Chromium available)
- Zero template rewriting needed
- Full CSS3 support eliminates all rendering issues
- `Pdf::view('exports.invoices', $data)->format('a4')->save($path)`

**Fallback choice: `mpdf/mpdf`**
- If Chromium cannot be installed on the server
- Better CSS support than DomPDF with similar API
- HTML/CSS templates need minimal changes

---

## 5. Additional Bugs Found

### 5.1 Order Number Print Restriction

**File:** `app/Livewire/Invoice/AddOrdernoForm.php` (line 48-51)

```php
if ($invoice->type == Constants::INVOICE_TYPE_TITRE && $invoice->edition_state != "PRINT") {
    $this->error_message = "Veuillez au préalable imprimer l'avis.";
    $this->addError('orderno', $this->error_message);
}
```

Blocks entering `order_no` until the invoice has been printed (`edition_state == "PRINT"`). This creates a circular dependency:
- Can't submit to PENDING without `order_no` (InvoiceGuard)
- Can't enter `order_no` without printing first
- Printing is only meaningful AFTER the invoice has an order number

**Fix:** Remove the print-before-order restriction, as it blocks legitimate workflow.

### 5.2 Undefined `status_text` Property

**File:** `resources/views/pages/taxpayers/show.blade.php` (line 493)

```blade
{{ json_decode($action->response)->status_text }}
```

The `$action->response` JSON may not always contain `status_text`. If the response was logged without this field (e.g., from a different HTTP client or version), this crashes with `Undefined property: stdClass::$status_text`.

**Fix:** Use null-safe operator.

### 5.3 API Notification Route Missing Auth

**File:** `routes/api.php` (line 49)

```php
Route::post('/v1/user/notifications', [NotificationController::class, 'notifications']);
```

This route is NOT inside the `auth:sanctum` middleware group. But `NotificationController::notifications()` calls `Auth::user()->id` — which throws an error when the user's session cookie isn't sent in the API request (e.g., from a different origin).

The JS client uses `fetch('/api/v1/user/notifications')` without sending authentication tokens. Since the web middleware sets the session cookie, this works when the page is loaded but fails on cross-origin requests or when the session expires.

**Fix:** The route needs `auth:sanctum` OR the JS needs to send a CSRF token and use `X-Requested-With` header. Since this is a simple session-based notification poll, moving the route inside the web middleware group is the cleanest fix. OR add the `auth` middleware and handle 401 gracefully in JS.
