<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\InvoiceCodeBalanceService;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $this->sync($payment);
    }

    public function updated(Payment $payment): void
    {
        $this->sync($payment);
    }

    public function deleted(Payment $payment): void
    {
        $this->sync($payment);
    }

    private function sync(Payment $payment): void
    {
        $invoice = $this->resolveInvoice($payment);
        if ($invoice) {
            app(InvoiceCodeBalanceService::class)->syncForInvoice($invoice);
        }
    }

    private function resolveInvoice(Payment $payment): ?Invoice
    {
        if (empty($payment->invoice_id)) {
            return null;
        }

        return Invoice::where('id', $payment->invoice_id)
            ->orWhere('invoice_no', $payment->invoice_id)
            ->first();
    }
}
