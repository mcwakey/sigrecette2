<?php
namespace App\Services\Sync;

use App\Enums\InvoicePayStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentImportService
{
    public function import(array $payments): array
    {
        $errors = [];
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $affectedInvoices = [];

        DB::transaction(function () use ($payments, &$errors, &$created, &$updated, &$skipped, &$affectedInvoices) {
            foreach ($payments as $index => $paymentData) {
                $invoice = Invoice::where('uuid', $paymentData['invoice_uuid'])->first();
                if (!$invoice) {
                    $errors[] = [
                        'index' => $index,
                        'uuid' => $paymentData['uuid'] ?? null,
                        'reason' => 'invoice_not_found',
                    ];
                    $skipped++;
                    continue;
                }

                $data = [
                    'uuid' => $paymentData['uuid'],
                    'invoice_id' => $invoice->id,
                    'taxpayer_id' => $paymentData['taxpayer_id'] ?? $invoice->taxpayer_id,
                    'amount' => $paymentData['amount'],
                    'payment_type' => $paymentData['payment_type'],
                    'invoice_type' => $paymentData['invoice_type'] ?? $invoice->type,
                    'reference' => $paymentData['reference'] ?? null,
                    'description' => $paymentData['description'] ?? null,
                    'remaining_amount' => $paymentData['remaining_amount'] ?? null,
                    'status' => $paymentData['status'],
                    'deposit' => $paymentData['deposit'] ?? null,
                    'notes' => $paymentData['notes'] ?? null,
                ];

                $existing = Payment::where('uuid', $paymentData['uuid'])->first();
                if ($existing) {
                    $existing->update($data);
                    $updated++;
                } else {
                    Payment::create($data);
                    $created++;
                }

                $affectedInvoices[$invoice->id] = true;
            }

            foreach (array_keys($affectedInvoices) as $invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if (!$invoice) {
                    continue;
                }

                $paid = Payment::where('invoice_id', $invoice->id)
                    ->whereIn('status', [
                        PaymentStatusEnums::PENDING,
                        PaymentStatusEnums::ACCOUNTED,
                        PaymentStatusEnums::DONE,
                    ])
                    ->where(function ($query) {
                        $query->whereNull('description')
                            ->orWhereNotIn('description', [Constants::ANNULATION, Constants::REDUCTION]);
                    })
                    ->sum('amount');

                if ($paid <= 0) {
                    $invoice->pay_status = InvoicePayStatusEnums::OWING;
                } elseif ($paid >= $invoice->amount) {
                    $invoice->pay_status = InvoicePayStatusEnums::PAID;
                } else {
                    $invoice->pay_status = InvoicePayStatusEnums::PART_PAID;
                }

                $invoice->save();
            }
        });

        return [
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }
}
