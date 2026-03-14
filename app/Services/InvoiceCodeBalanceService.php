<?php

namespace App\Services;

use App\Enums\PaymentStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\InvoiceCodeBalance;
use App\Models\Payment;
use Carbon\Carbon;

class InvoiceCodeBalanceService
{
    public function syncForInvoice(Invoice $invoice): void
    {
        $invoice->loadMissing('invoiceitems.taxpayer_taxable.taxable.tax_label');
        $year = $this->getInvoiceYear($invoice);

        $amountsByCode = Invoice::sumAmountsByTaxCode($invoice);
        $codes = array_keys($amountsByCode);

        foreach ($amountsByCode as $code => $data) {
            $amountBilled = (float) $data['amount'];
            $amountPaid = $this->sumPaymentsByCode($invoice, $code);
            $remaining = max($amountBilled - $amountPaid, 0);

            $status = $remaining <= 0 ? 'PAID' : 'OWING';

            InvoiceCodeBalance::updateOrCreate(
                ['invoice_id' => $invoice->id, 'code' => $code],
                [
                    'taxpayer_id' => $invoice->taxpayer_id,
                    'year' => $year,
                    'amount_billed' => $amountBilled,
                    'amount_paid' => $amountPaid,
                    'remaining_amount' => $remaining,
                    'status' => $status,
                    'last_payment_at' => $this->lastPaymentAt($invoice, $code),
                ]
            );
        }

        if (!empty($codes)) {
            InvoiceCodeBalance::where('invoice_id', $invoice->id)
                ->whereNotIn('code', $codes)
                ->delete();
        }
    }

    private function getInvoiceYear(Invoice $invoice): int
    {
        if (!empty($invoice->from_date)) {
            return Carbon::parse($invoice->from_date)->year;
        }
        return $invoice->created_at?->year ?? (int) date('Y');
    }

    private function sumPaymentsByCode(Invoice $invoice, string $code): float
    {
        $query = Payment::query()
            ->where('code', $code)
            ->whereIn('status', [
                PaymentStatusEnums::PENDING,
                PaymentStatusEnums::ACCOUNTED,
                PaymentStatusEnums::DONE,
            ])
            ->where(function ($q) use ($invoice) {
                $q->where('invoice_id', $invoice->id)
                    ->orWhere('invoice_id', $invoice->invoice_no);
            })
            ->where(function ($q) {
                $q->whereNull('description')
                    ->orWhereNotIn('description', [Constants::ANNULATION, Constants::REDUCTION]);
            });

        return (float) $query->sum('amount');
    }

    private function lastPaymentAt(Invoice $invoice, string $code): ?Carbon
    {
        $payment = Payment::query()
            ->where('code', $code)
            ->where(function ($q) use ($invoice) {
                $q->where('invoice_id', $invoice->id)
                    ->orWhere('invoice_id', $invoice->invoice_no);
            })
            ->orderByDesc('created_at')
            ->first();

        return $payment?->created_at ? Carbon::parse($payment->created_at) : null;
    }
}
