<?php

namespace App\Helpers;

use App\Enums\PaymentStatusEnums;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentHelper
{
    /**
     * Obtenir les paiements filtrés par statut
     *
     * @param $invoice_id
     * @param array $status
     * @return Collection
     */
    public static function getPaymentsByStatus($invoice_id, array $status)
    {
        return Payment::where('invoice_id', $invoice_id)
            ->whereIn('status', $status)
            ->get();
    }

    /**
     * @param $invoice_id
     * @return float|int
     */
    public static function getPaid($invoice_id): float|int
    {
        $payments = self::getPaymentsByStatus($invoice_id, [PaymentStatusEnums::PENDING,PaymentStatusEnums::ACCOUNTED,PaymentStatusEnums::DONE]);
        $s_amount = [];

        foreach ($payments as $index => $payment) {
            if ($payment->description != Constants::ANNULATION && $payment->description != Constants::REDUCTION) {
                $s_amount[$index] = $payment->amount;
            }
        }
        return array_sum($s_amount) ?? 0;
    }
    /**
     * @param $invoice_id
     * @return float|int
     */
    public static function getPaidNotAccounted($invoice_id): float|int
    {
        $payments = self::getPaymentsByStatus($invoice_id, [PaymentStatusEnums::PENDING]);
        $s_amount = [];

        foreach ($payments as $index => $payment) {
            if ($payment->description != Constants::ANNULATION && $payment->description != Constants::REDUCTION) {
                $s_amount[$index] = $payment->amount;
            }
        }
        return array_sum($s_amount) ?? 0;
    }

    /**
     * @param Invoice $invoice
     * @return float|int
     */
    public static function getRestToPaid(Invoice $invoice): float|int
    {
        $paid = self::getPaymentsByStatus($invoice->invoice_no, [PaymentStatusEnums::PENDING,PaymentStatusEnums::ACCOUNTED,PaymentStatusEnums::DONE])
            ->sum('amount');
        $restToPay = $invoice->amount - $paid;

        return max($restToPay, 0);
    }
}
