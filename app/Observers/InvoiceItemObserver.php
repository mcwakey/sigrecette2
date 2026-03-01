<?php

namespace App\Observers;

use App\Models\InvoiceItem;
use App\Services\InvoiceCodeBalanceService;

class InvoiceItemObserver
{
    public function created(InvoiceItem $invoiceItem): void
    {
        $this->sync($invoiceItem);
    }

    public function updated(InvoiceItem $invoiceItem): void
    {
        $this->sync($invoiceItem);
    }

    public function deleted(InvoiceItem $invoiceItem): void
    {
        $this->sync($invoiceItem);
    }

    private function sync(InvoiceItem $invoiceItem): void
    {
        $invoice = $invoiceItem->invoice;
        if ($invoice) {
            app(InvoiceCodeBalanceService::class)->syncForInvoice($invoice);
        }
    }
}
