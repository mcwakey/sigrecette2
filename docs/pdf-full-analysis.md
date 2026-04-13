# SigRecette - PDF Generation Full Analysis Report

> **Date:** June 2025  
> **Package:** `barryvdh/laravel-dompdf ^2.0`  
> **Facade:** `Barryvdh\DomPDF\Facade\Pdf`  
> **Config:** `config/dompdf.php` (vendor defaults)

---

## Table of Contents

1. [Architecture Overview](#1-architecture-overview)
2. [Routes](#2-routes)
3. [Controllers](#3-controllers)
4. [Actions](#4-actions)
5. [Services](#5-services)
6. [PDF Generator Methods](#6-pdf-generator-methods)
7. [PDF Templates (Blade)](#7-pdf-templates-blade)
8. [Triggering Pages (Frontend)](#8-triggering-pages-frontend)
9. [Jobs & Async Generation](#9-jobs--async-generation)
10. [Contracts / Interfaces](#10-contracts--interfaces)
11. [Bugs Found & Fixes Applied](#11-bugs-found--fixes-applied)

---

## 1. Architecture Overview

```
┌──────────────────────────────────────────────────────────────────────────┐
│  BLADE VIEW (Page)                                                        │
│  • <a href=".." target="_blank">   (direct link, new tab)                │
│  • window.open(url, '_blank')      (JS, new tab)                         │
│  • fetch() + window.open()         (session store + new tab)             │
└────────────────────────────────────┬─────────────────────────────────────┘
                                     │ HTTP GET
                                     ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  ROUTE                                                                    │
│  GET /generate-pdf/{data?}/{type?}/{action?}/{id?}  → PrintController    │
│  GET /generatepdf/{printFile}/{type?}/{action?}     → PrintController    │
│  GET /print-all-invoice                              → PrintController    │
│  GET /download/{filename}/{id?}                     → FileDownloadController│
│  POST /store-session-params                          → session store      │
└────────────────────────────────────┬─────────────────────────────────────┘
                                     │
                                     ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  CONTROLLER: PrintController                                              │
│  • download()              → PrintWithoutData action                     │
│  • downloadWithPrintData() → PrintWithData action                        │
│  • downloadMultipleInvoicePdf() → DownloadMultipleInvoiceAction          │
└────────────────────────────────────┬─────────────────────────────────────┘
                                     │
                                     ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  ACTIONS                                                                  │
│  PrintWithoutData::execute() → decode data → PrintService::processType() │
│  PrintWithData::execute()    → use PrintFile → PrintService::processType()│
│  DownloadMultipleInvoiceAction::execute() → dispatch DownloadInvoiceZipJob│
└────────────────────────────────────┬─────────────────────────────────────┘
                                     │
                                     ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  SERVICE: PrintService::processType($type, $data, $action, $user)        │
│  match($type) routes to PdfGeneratorService methods                      │
└────────────────────────────────────┬─────────────────────────────────────┘
                                     │
                                     ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  SERVICE: PdfGeneratorService                                             │
│  14+ methods → PDF::loadView('exports.template') → stream() / save()    │
│  Returns: ['success' => bool, 'pdf' => Response, 'message' => string]   │
└────────────────────────────────────┬─────────────────────────────────────┘
                                     │
                                     ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  BLADE TEMPLATES: resources/views/exports/*.blade.php (18 templates)     │
│  Rendered by DomPDF → streamed as HTTP Response                          │
└──────────────────────────────────────────────────────────────────────────┘
```

**Return Flow:**
- If `$result['success'] === true` → returns `$result['pdf']` (streams PDF to browser)
- If `$result['success'] === false` → `back()->with('error', $message)` (redirects back)
- If exception → renders `errors/{code}` view

---

## 2. Routes

| Route | Method | Controller | Name | Purpose |
|-------|--------|-----------|------|---------|
| `GET /generate-pdf/{data?}/{type?}/{action?}/{id?}` | GET | `PrintController@download` | `generatePdf` | Main PDF generation (data as URL param or session) |
| `GET /generatepdf/{printFile}/{type?}/{action?}` | GET | `PrintController@downloadWithPrintData` | `generateWithPrintData` | PDF from saved PrintFile model |
| `GET /print-all-invoice` | GET | `PrintController@downloadMultipleInvoicePdf` | `print-all-invoice` | Bulk invoice ZIP (async job) |
| `GET /download/{filename}/{id?}` | GET | `FileDownloadController@download` | `download.file` | Download pre-generated PDF files |
| `POST /store-session-params` | POST | Closure | `store.session.params` | Store PDF data in session |

**File:** `routes/web.php` (lines 146-160)

---

## 3. Controllers

### 3.1 PrintController
**File:** `app/Http/Controllers/PrintController.php`

| Method | Delegates To | Parameters |
|--------|-------------|-----------|
| `index()` | `PrintablesDataTable` | — |
| `download()` | `PrintWithoutData::execute()` | `$data, $type, $action, User $id` |
| `downloadWithPrintData()` | `PrintWithData::execute()` | `PrintFile $printFile, $type, $action` |
| `downloadMultipleInvoicePdf()` | `DownloadMultipleInvoiceAction::execute()` | Action from request |

### 3.2 FileDownloadController
**File:** `app/Http/Controllers/FileDownloadController.php`

Serves pre-generated PDF files from `storage/app/exports/` with delete-after-send pattern.

---

## 4. Actions

### 4.1 PrintWithoutData
**File:** `app/Actions/PrintWithoutData.php`

```
execute($data, $type, $action, $id)
  → if data == 'null' or null → read from session('edition_params')
  → else → json_decode($data)
  → PrintService::processType($type, $data, $action, $id)
  → if success → return $pdf
  → if async → return back() with success
  → else → return back() with error
  → catch → render error view
```

### 4.2 PrintWithData
**File:** `app/Actions/PrintWithData.php`

```
execute(PrintFile $printFile, $type, $action)
  → PrintService::processType($type, $printFile, $action)
  → if success → return $pdf
  → else → return back() with error
  → catch → render error view
```

### 4.3 DownloadMultipleInvoiceAction
**File:** `app/Actions/DownloadMultipleInvoiceAction.php`

```
execute($action)
  → get printable UUIDs from Invoice model
  → dispatch DownloadInvoiceZipJob (async)
  → return back()
```

---

## 5. Services

### 5.1 PrintService
**File:** `app/Services/PrintService.php`  
**Interface:** `app/Contracts/PrintServiceInterface.php`

`processType($type, $data, $action, User $user)` routing:

| Type | Method Called | Description |
|------|-------------|-------------|
| 1 | `handleReceipt()` → `downloadReceipt()` | Payment receipt |
| 2 | `handleTypeTwo()` (sub-routes by action) | Invoice lists/bordereaux |
| 6 | `generateStateAccountIvCollectorPdf()` | Collector inactive values state |
| 7 | `generateStateValueCollectorPdf()` | Receiver account IV state |
| 8 | `generateStateValueCollectorPdf()` | Collector deposit state |
| 9 | `generateStateValueCollectorPdf()` | Paymaster deposit state |
| 10 | `generateLedgersPdf()` | Ledger journal |
| 11 | `generataxpayerFormPdf()` | Taxpayer form |
| 15 | `generateStateValueCollectorPdf()` | Paymaster IV state |
| 16 | `generateStateValueCollectorPdf()` | Paymaster cash deposit state |
| 77 | `generateInvoicePdf()` (relance) | Invoice relance print |
| default | `generateInvoicePdf()` | Individual invoice |

**Type 2 sub-routing (`handleTypeTwo`):**

| Action | Method Called | Description |
|--------|-------------|-------------|
| 1, 2 | `generateBordereauListPdf()` → `LongPrintTaskJob` | Bordereau (async) |
| 3 | `generateInvoiceRegistrePdf()` | Invoice registry |
| 4 | `generateInvoiceDistribtionOrInvoiceRecouvrementPdf()` | Distribution sheet |
| 5 | `generateJournalInvoiceListPdf()` | Receiver's journal |
| 41 | `generateInvoiceDistribtionOrInvoiceRecouvrementPdf()` | Recovery sheet |
| 42 | `generateInvoiceListPdf()` | Invoice list (recovery) |
| 77 | `generateInvoiceTypeTwoListPdf()` | Comptant declarations registry |

### 5.2 PdfGeneratorService
**File:** `app/Services/PdfGeneratorService.php`  
**Interface:** `app/Contracts/PdfGeneratorInterface.php`

---

## 6. PDF Generator Methods

### 6.1 `generateInvoicePdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Portrait |
| **Template** | `exports.invoices` |
| **Data Source** | `Invoice::retrieveByUUIDs($data)` |
| **Validation** | `count($data) == 1` and commune not null |
| **Filename** | `Avis-{invoice_no}-{date}.pdf` |
| **Notes** | Handles relance (type 77) and duplicate (action 2). Updates `edition_state` to "PRINT". Only works for single invoices. |

### 6.2 `generateInvoiceListPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.invoices-recouvrement` (action 42) |
| **Data Source** | `Invoice::retrieveByUUIDs($data, 'payment')` |
| **Filename** | `Avis-liste-{count}-{date}.pdf` |

### 6.3 `downloadReceipt()`
| Property | Value |
|----------|-------|
| **Paper** | A5, Landscape |
| **Template** | `exports.payments` |
| **Data Source** | `Payment::with([...])->whereIn('uuid', $data)` |
| **Filename** | `receipt-{reference}-{random}.pdf` |

### 6.4 `generateStateValueCollectorPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Templates** | `state-account-iv-receveur`, `state-versement-collecteur`, `state-versement-regisseur`, `state-versement-regisseur-comptant`, `state-iv-regisseur` |
| **Data Source** | Data passed directly from blade (table scrape) |
| **Filename** | `StateValueCollector{random}.pdf` |

### 6.5 `generateStateValueReciepientPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | (Variable) |
| **Filename** | `StateValueCollector{random}.pdf` |

### 6.6 `generataxpayerFormPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Portrait |
| **Template** | `exports.taxpayer-form` |
| **Data Source** | `Taxpayer::getInvoiceAndPayments($data[0])` |
| **Filename** | `Fiche-contribuable{random}.pdf` |

### 6.7 `generateLedgersPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.livre-journal-regie` |
| **Data Source** | `Payment::getPrintData()` |
| **Filename** | `Livre-journal_de_Regie.pdf` |

### 6.8 `generateBordereauListPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.invoices-list` |
| **Data Source** | `Invoice::getPrintData()` or `PrintFile->invoices()` |
| **Output** | **Saved to disk** (not streamed). Downloaded later via `FileDownloadController`. |
| **Filename** | `{type}-{date}.pdf` |

### 6.9 `generateJournalInvoiceListPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.invoices-journal-receveur` |
| **Data Source** | `Invoice::getPrintData(statuses)` |
| **Filename** | `Journal_des_avis_...-{date}.pdf` |

### 6.10 `generateInvoiceRegistrePdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.invoices-registre` |
| **Data Source** | `Invoice::getPrintData(statuses)` |
| **Filename** | `Registre-journal-des-avis-distribués{random}.pdf` |

### 6.11 `generateInvoiceDistribtionOrInvoiceRecouvrementPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Templates** | `exports.invoices-distribution` (action 4), `exports.invoices-recouvrement` (action 41) |
| **Data Source** | `Invoice::retrieveByUUIDs()` filtered by type, or `PrintFile->invoices()` |
| **Filename** | `{type}-{printFile.id}-{date}.pdf` |
| **Side Effects** | Creates `PrintFile`, updates `ondistributionprint` or `onrecoveryprint` flags |

### 6.12 `generateInvoiceTypeTwoListPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.registre-journal-des-declarations-prealables-des-usagers` |
| **Data Source** | `Invoice::whereBetween(activeYear)` type COMPTANT, status APPROVED |
| **Filename** | `Registre-journal_des_déclarations_..._usagers{random}.pdf` |

### 6.13 `generateStateAccountIvCollectorPdf()`
| Property | Value |
|----------|-------|
| **Paper** | A4, Landscape |
| **Template** | `exports.state-account-iv-collectorc` |
| **Data Source** | `StockTransfer::buildAndGetStockTransferWithQuery($period)` |
| **Filename** | `ETAT_DE_COMPTABILITE_DES_VALEURS_INACTIVES_DU_COLLECTEUR{random}.pdf` |
| **Input** | `$data = [$userId, $dateTo]` |

---

## 7. PDF Templates (Blade)

All templates are in `resources/views/exports/`.

| Template File | Used By Method | Paper | Orientation |
|--------------|----------------|-------|-------------|
| `invoices.blade.php` | `generateInvoicePdf()` | A4 | Portrait |
| `invoices-list.blade.php` | `generateBordereauListPdf()`, `generateInvoiceListPdf()` | A4 | Landscape |
| `invoices-registre.blade.php` | `generateInvoiceRegistrePdf()` | A4 | Landscape |
| `invoices-distribution.blade.php` | `generateInvoiceDistribtionOrInvoiceRecouvrementPdf()` | A4 | Landscape |
| `invoices-recouvrement.blade.php` | `generateInvoiceDistribtionOrInvoiceRecouvrementPdf()` / `generateInvoiceListPdf()` | A4 | Landscape |
| `invoices-journal-receveur.blade.php` | `generateJournalInvoiceListPdf()` | A4 | Landscape |
| `journal-invoices.blade.php` | (unused / legacy) | — | — |
| `registre-journal-des-declarations-prealables-des-usagers.blade.php` | `generateInvoiceTypeTwoListPdf()` | A4 | Landscape |
| `payments.blade.php` | `downloadReceipt()` | A5 | Landscape |
| `livre-journal-regie.blade.php` | `generateLedgersPdf()` | A4 | Landscape |
| `taxpayer-form.blade.php` | `generataxpayerFormPdf()` | A4 | Portrait |
| `state-account-iv-collectorc.blade.php` | `generateStateAccountIvCollectorPdf()` | A4 | Landscape |
| `state-account-iv-receveur.blade.php` | `generateStateValueCollectorPdf()` (type 7) | A4 | Landscape |
| `state-account-iv-receveurc.blade.php` | (variant) | A4 | Landscape |
| `state-versement-collector.blade.php` | `generateStateValueCollectorPdf()` (type 8) | A4 | Landscape |
| `state-versement-regisseur.blade.php` | `generateStateValueCollectorPdf()` (type 9) | A4 | Landscape |
| `state-versement-regisseur-comptant.blade.php` | `generateStateValueCollectorPdf()` (type 16) | A4 | Landscape |
| `state-iv-regisseur.blade.php` | `generateStateValueCollectorPdf()` (type 15) | A4 | Landscape |

---

## 8. Triggering Pages (Frontend)

### 8.1 Direct Links (target="_blank") - WORKING

| Page | File | Type | Action | Data |
|------|------|------|--------|------|
| Invoice Actions | `pages/invoices/columns/_actions.blade.php` | default | — | `[$invoice->uuid]` |
| Invoice Relance | `pages/invoices/columns/_actions.blade.php` | 77 | — | `[$invoice->uuid]` |
| Taxpayer Invoices | `livewire/taxpayer/taxpayer-invoices.blade.php` | default | — | `[$invoice->uuid]` |
| Taxpayer Form | `pages/taxpayers/show.blade.php` | 11 | — | `[$taxpayer->id]` |
| Stock Transfer Show | `pages/stock_transfers/show.blade.php` | 6 | — | `[$user->id, $dateTo]` |
| Printables Actions | `pages/printables/columns/_actions.blade.php` | 2 | 1/2/4/41 | `PrintFile $id` |

### 8.2 JavaScript `window.open()` with `fetch()` + Session - FIXED (race condition)

| Page | File | Type | Action | Data |
|------|------|------|--------|------|
| Invoices List | `pages/invoices/list.blade.php` | 2 | 1-77 | Session (invoice UUIDs) |
| Accountant Deposits Show | `pages/accountant_deposits/show.blade.php` | 9/16 | — | Session (table data) |

### 8.3 JavaScript `window.open()` (formerly `window.location.href`) - FIXED

| Page | File | Type | Action | Data |
|------|------|------|--------|------|
| Stock Transfers List | `pages/stock_transfers/list.blade.php` | 6 | — | URL (table scrape JSON) |
| Collector Deposits Show | `pages/collector_deposits/show.blade.php` | 8 | — | URL (table scrape JSON) |
| Collector Deposits List | `pages/collector_deposits/list.blade.php` | 8 | — | URL (table scrape JSON) |
| Stock Requests Show | `pages/stock_requests/show.blade.php` | 7 | — | URL (table scrape JSON) |
| Stock Requests List | `pages/stock_requests/list.blade.php` | 7 | — | URL (table scrape JSON) |
| Ledgers List | `pages/ledgers/list.blade.php` | 10 | — | URL (empty array) |
| Accountant Deposits List | `pages/accountant_deposits/list.blade.php` | 9/16 | — | URL (table scrape JSON) |
| Accountant Deposits Outright | `pages/accountant_deposits_outright/list.blade.php` | 16 | — | URL (table scrape JSON) |

---

## 9. Jobs & Async Generation

### 9.1 LongPrintTaskJob
**File:** `app/Jobs/LongPrintTaskJob.php`

Dispatched by `PrintService::generateBordereauListPdf()` for bordereau PDFs (type 2, action 1/2). Generates PDF, saves to disk. User is notified when ready, then downloads via `FileDownloadController`.

### 9.2 DownloadInvoiceZipJob
**File:** `app/Jobs/DownloadInvoiceZipJob.php`

Dispatched by `DownloadMultipleInvoiceAction` for bulk invoice ZIP generation. Creates a ZIP file with multiple PDFs using `DownloadInvoiceZipService`. User is notified when ready.

### 9.3 DownloadInvoiceZipService
**File:** `app/Services/DownloadInvoiceZipService.php`

Creates ZIP archives using PHP `ZipArchive`. Iterates invoice UUIDs, generates individual PDFs, adds to ZIP.

---

## 10. Contracts / Interfaces

### PdfGeneratorInterface
**File:** `app/Contracts/PdfGeneratorInterface.php`

Declares only 5 of 14+ methods:
- `generateInvoiceListPdf()`
- `generateInvoicePdf()`
- `generateStateValueCollectorPdf()`
- `generateStateValueReciepientPdf()`
- `generataxpayerFormPdf()`

**Missing from interface:** `downloadReceipt()`, `generateLedgersPdf()`, `generateBordereauListPdf()`, `generateJournalInvoiceListPdf()`, `generateInvoiceRegistrePdf()`, `generateInvoiceDistribtionOrInvoiceRecouvrementPdf()`, `generateInvoiceTypeTwoListPdf()`, `generateStateAccountIvCollectorPdf()`

### PrintServiceInterface
**File:** `app/Contracts/PrintServiceInterface.php`

Declares: `processType()`

---

## 11. Bugs Found & Fixes Applied

### BUG #1 - Method Name Typo (CRITICAL - FIXED)

**File:** `app/Services/PrintService.php` line 25  
**Issue:** Called `generateStateAcountIvCollectorPdf()` (missing 'c' in "Account") but the actual method in `PdfGeneratorService` is `generateStateAccountIvCollectorPdf()`.  
**Impact:** Type 6 (stock transfers) PDF generation always throws an exception → error page or redirect.  
**Fix:** Corrected to `generateStateAccountIvCollectorPdf()`.

### BUG #2 - Wrong Count Comparison (CRITICAL - FIXED)

**File:** `app/Services/PdfGeneratorService.php` `generateStateAccountIvCollectorPdf()`  
**Issue:** Checked `if (count($data) > 2)` requiring 3+ elements, but `stock_transfers/show.blade.php` passes `[$user->id, $dateTo]` (2 elements).  
**Impact:** Type 6 PDFs from show page always returned `['success' => false]` → redirect back.  
**Fix:** Changed to `if (count($data) >= 2)`.

### BUG #3 - Variable Reuse / Overwrite (CRITICAL - FIXED)

**File:** `app/Services/PdfGeneratorService.php` `generateStateAccountIvCollectorPdf()`  
**Issue:** After extracting `$period = $data[1]`, the code did `$data = StockTransfer::buildAndGetStockTransferWithQuery($period)` overwriting `$data`. Then passed `'period' => $data[1]` to the view, but `$data` was now the query result, not the original array.  
**Impact:** Wrong period value passed to template, potential index error.  
**Fix:** Renamed to `$stockData` for the query result and used `'period' => $period`.

### BUG #4 - Session Race Condition (MEDIUM - FIXED)

**Files:**
- `resources/views/pages/accountant_deposits/show.blade.php`  
- `resources/views/pages/invoices/list.blade.php`

**Issue:** `fetch()` to store session params was fire-and-forget. The navigation (`window.open()` or `window.location.href`) fired immediately without waiting for the fetch to complete. If the session wasn't saved in time, the PDF route read empty session data → `['success' => false]` → redirect.  
**Fix:** Wrapped the navigation inside `.then()` callback so it waits for the fetch to complete before opening the PDF URL.

### BUG #5 - Same-Tab Navigation on Failure (HIGH - FIXED)

**Files (8 blade views):**
- `pages/stock_transfers/list.blade.php`
- `pages/collector_deposits/show.blade.php`
- `pages/collector_deposits/list.blade.php`
- `pages/stock_requests/show.blade.php`
- `pages/stock_requests/list.blade.php`
- `pages/ledgers/list.blade.php`
- `pages/accountant_deposits/list.blade.php`
- `pages/accountant_deposits_outright/list.blade.php`

**Issue:** Used `window.location.href = url` which replaces the current tab. If PDF generation fails, `back()` redirects to the same page → the page just "reloads" with no visible PDF, making it look like "nothing happened".  
**Fix:** Changed all 8 occurrences to `window.open(url, '_blank')`. PDF now opens in a new tab. If it fails, the error appears in the new tab and the original page is unaffected.

---

## Complete File Chain Per PDF Type

### Individual Invoice (default type)
```
pages/invoices/columns/_actions.blade.php  (target="_blank", data=[$uuid])
  → GET /generate-pdf/{data}/{type}/{action}
  → PrintController::download()
  → PrintWithoutData::execute()
  → PrintService::processType() → default case
  → PdfGeneratorService::generateInvoicePdf()
  → exports/invoices.blade.php  (A4 Portrait)
```

### Invoice Relance (type 77)
```
pages/invoices/columns/_actions.blade.php  (target="_blank", data=[$uuid], type=77)
  → GET /generate-pdf/{data}/77
  → PrintController::download()
  → PrintWithoutData::execute()
  → PrintService::processType() → case 77
  → PdfGeneratorService::generateInvoicePdf($data, 'invoices', $action, true)
  → exports/invoices.blade.php  (A4 Portrait)
```

### Payment Receipt (type 1)
```
(triggered from payment action links)
  → GET /generate-pdf/{data}/1
  → PrintController::download()
  → PrintWithoutData::execute()
  → PrintService::processType() → case 1 → handleReceipt()
  → PdfGeneratorService::downloadReceipt()
  → exports/payments.blade.php  (A5 Landscape)
```

### Bordereau List (type 2, action 1 or 2)
```
pages/invoices/list.blade.php  (fetch+window.open, session data)
  → POST /store-session-params  (save UUIDs in session)
  → GET /generate-pdf/null/2/1
  → PrintController::download()
  → PrintWithoutData::execute() → reads session('edition_params')
  → PrintService::processType() → case 2 → handleTypeTwo() → case 1,2
  → PrintService::generateBordereauListPdf() → dispatches LongPrintTaskJob
  → (returns async message, PDF generated by job)
  → Job: PdfGeneratorService::generateBordereauListPdf()
  → exports/invoices-list.blade.php  (A4 Landscape, saved to disk)
  → User downloads via FileDownloadController::download()
```

### Invoice Registry (type 2, action 3)
```
pages/invoices/list.blade.php  (fetch+window.open, session data)
  → POST /store-session-params
  → GET /generate-pdf/null/2/3
  → PrintService::processType() → case 2 → handleTypeTwo() → case 3
  → PdfGeneratorService::generateInvoiceRegistrePdf()
  → exports/invoices-registre.blade.php  (A4 Landscape)
```

### Distribution Sheet (type 2, action 4)
```
pages/invoices/list.blade.php  OR  pages/printables/columns/_actions.blade.php
  → GET /generate-pdf/... or /generatepdf/{printFile}/2/4
  → PrintService → case 2 → action 4
  → PdfGeneratorService::generateInvoiceDistribtionOrInvoiceRecouvrementPdf()
  → exports/invoices-distribution.blade.php  (A4 Landscape)
```

### Recovery Sheet (type 2, action 41)
```
pages/invoices/list.blade.php  OR  pages/printables/columns/_actions.blade.php
  → PrintService → case 2 → action 41
  → PdfGeneratorService::generateInvoiceDistribtionOrInvoiceRecouvrementPdf()
  → exports/invoices-recouvrement.blade.php  (A4 Landscape)
```

### Receiver's Journal (type 2, action 5)
```
pages/invoices/list.blade.php  (fetch+window.open)
  → PrintService → case 2 → action 5
  → PdfGeneratorService::generateJournalInvoiceListPdf()
  → exports/invoices-journal-receveur.blade.php  (A4 Landscape)
```

### Invoice Recovery List (type 2, action 42)
```
pages/invoices/list.blade.php  (fetch+window.open)
  → PrintService → case 2 → action 42
  → PdfGeneratorService::generateInvoiceListPdf()
  → exports/invoices-recouvrement.blade.php  (A4 Landscape)
```

### Comptant Declarations Registry (type 2, action 77)
```
pages/invoices/list.blade.php  (fetch+window.open)
  → PrintService → case 2 → action 77
  → PdfGeneratorService::generateInvoiceTypeTwoListPdf()
  → exports/registre-journal-des-declarations-prealables-des-usagers.blade.php  (A4 Landscape)
```

### Collector Inactive Values State (type 6)
```
pages/stock_transfers/show.blade.php  (target="_blank", data=[$userId, $dateTo])
pages/stock_transfers/list.blade.php  (window.open, table data JSON)
  → GET /generate-pdf/{data}/6
  → PrintService::processType() → case 6
  → PdfGeneratorService::generateStateAccountIvCollectorPdf()
  → exports/state-account-iv-collectorc.blade.php  (A4 Landscape)
```

### Receiver Account IV State (type 7)
```
pages/stock_requests/show.blade.php  (window.open, table data JSON)
pages/stock_requests/list.blade.php  (window.open, table data JSON)
  → GET /generate-pdf/{data}/7
  → PrintService → case 7
  → PdfGeneratorService::generateStateValueCollectorPdf()
  → exports/state-account-iv-receveur.blade.php  (A4 Landscape)
```

### Collector Deposit State (type 8)
```
pages/collector_deposits/show.blade.php  (window.open, table data JSON)
pages/collector_deposits/list.blade.php  (window.open, table data JSON)
  → GET /generate-pdf/{data}/8
  → PrintService → case 8
  → PdfGeneratorService::generateStateValueCollectorPdf()
  → exports/state-versement-collector.blade.php  (A4 Landscape)
```

### Paymaster Deposit State (type 9)
```
pages/accountant_deposits/show.blade.php  (fetch+window.open, session data)
pages/accountant_deposits/list.blade.php  (window.open, table data JSON)
  → GET /generate-pdf/{data}/9
  → PrintService → case 9
  → PdfGeneratorService::generateStateValueCollectorPdf()
  → exports/state-versement-regisseur.blade.php  (A4 Landscape)
```

### Ledger Journal (type 10)
```
pages/ledgers/list.blade.php  (window.open, empty array)
  → GET /generate-pdf/{data}/10
  → PrintService → case 10
  → PdfGeneratorService::generateLedgersPdf()
  → exports/livre-journal-regie.blade.php  (A4 Landscape)
```

### Taxpayer Form (type 11)
```
pages/taxpayers/show.blade.php  (target="_blank", data=[$taxpayer->id])
  → GET /generate-pdf/{data}/11
  → PrintService → case 11
  → PdfGeneratorService::generataxpayerFormPdf()
  → exports/taxpayer-form.blade.php  (A4 Portrait)
```

### Paymaster IV State (type 15)
```
  → PrintService → case 15
  → PdfGeneratorService::generateStateValueCollectorPdf()
  → exports/state-iv-regisseur.blade.php  (A4 Landscape)
```

### Paymaster Cash Deposit State (type 16)
```
pages/accountant_deposits/show.blade.php  (fetch+window.open, session data)
pages/accountant_deposits_outright/list.blade.php  (window.open, table data JSON)
  → GET /generate-pdf/{data}/16
  → PrintService → case 16
  → PdfGeneratorService::generateStateValueCollectorPdf()
  → exports/state-versement-regisseur-comptant.blade.php  (A4 Landscape)
```

### Bulk Invoice ZIP (print-all-invoice)
```
pages/invoices/list.blade.php  (selectedValue === '00')
  → GET /print-all-invoice
  → PrintController::downloadMultipleInvoicePdf()
  → DownloadMultipleInvoiceAction::execute()
  → Dispatches DownloadInvoiceZipJob (async)
  → Job uses PdfGeneratorService::generateInvoicePdf() per invoice
  → Creates ZIP file → notification → FileDownloadController::download()
```

---

## Summary of All Files Involved

### Core PHP Files
| File | Role |
|------|------|
| `app/Http/Controllers/PrintController.php` | Entry point for all PDF routes |
| `app/Http/Controllers/FileDownloadController.php` | Serves pre-generated files |
| `app/Actions/PrintWithoutData.php` | Action for URL-data-based PDF |
| `app/Actions/PrintWithData.php` | Action for PrintFile-based PDF |
| `app/Actions/DownloadMultipleInvoiceAction.php` | Action for bulk ZIP |
| `app/Services/PrintService.php` | Type router |
| `app/Services/PdfGeneratorService.php` | Core PDF generation (14+ methods) |
| `app/Services/DownloadInvoiceZipService.php` | ZIP creation |
| `app/Contracts/PdfGeneratorInterface.php` | Interface (incomplete) |
| `app/Contracts/PrintServiceInterface.php` | Interface |
| `app/Traits/InvoiceTrait.php` | `retrieveByUUIDs()`, `filterByType()` |
| `app/Jobs/LongPrintTaskJob.php` | Async bordereau generation |
| `app/Jobs/DownloadInvoiceZipJob.php` | Async ZIP generation |
| `routes/web.php` | Route definitions (lines 146-160) |

### PDF Export Templates (18 files)
All in `resources/views/exports/` — see Section 7.

### Triggering Blade Views (14+ files)
All in `resources/views/pages/` and `resources/views/livewire/` — see Section 8.
