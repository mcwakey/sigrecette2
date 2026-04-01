<?php

namespace App\Traits;

use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Enums\PrintNameEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PrintFile;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

trait InvoiceTrait
{
    public static function getReceiverName($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice && $invoice->delivery_date !== null) {
            return $invoice->delivery_to;
        }
        return "";
    }
    /**
     * Retrieve invoices based on provided UUIDs.
     *
     * @return array Returns a collection of invoices if all UUIDs are found,
     *                               `false` if any UUID is not found, or an empty array if `$uuids` is empty.
     */
    public static function retrieveByUUIDs(array $uuids, string|null $type = null): array
    {
        if ($uuids === []) {
            return [];
        }

        $eagerLoad = [
            'invoiceitems.taxpayer_taxable.taxable.tax_label',
            'taxpayer_taxables.taxable.tax_label',
            'taxpayer.town.canton',
            'taxpayer.zone',
            'taxpayer.category',
            'taxpayer.activity',
            'payments',
        ];

        if ($type === 'payment') {
            $payments = Payment::whereIn('uuid', $uuids)->get();
            $invoiceIds = $payments->pluck('invoice_id')->unique()->filter()->all();
            return Invoice::with($eagerLoad)
                ->whereIn('id', $invoiceIds)
                ->get()
                ->all();
        }

        return Invoice::with($eagerLoad)
            ->whereIn('uuid', $uuids)
            ->get()
            ->all();
    }
    /**
     * Retrieve invoices based on provided UUIDs.
     *
     * @return array Returns a collection of invoices if all UUIDs are found,
     *                               `false` if any UUID is not found, or an empty array if `$uuids` is empty.
     */
    public static function filterByType(array $invoices, string $type): array
    {
        $invoices_return = [];
        foreach ($invoices as $invoice) {
            if ($type === PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS->value && $invoice->ondistributionprint == false) {
                $invoices_return[] = $invoice;
            } elseif ($type === PrintNameEnums::FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES->value && $invoice->onrecoveryprint == false) {
                $invoices_return[] = $invoice;
            }
        }
        return array_values($invoices_return);
    }
    /**
     * Sum amounts by tax code for the given invoice.
     *
     * This function calculates the total amount for each tax code present in the invoice
     * by summing up the amounts of all invoice items associated with that tax code.
     *
     * @param Invoice $invoice The invoice object.
     * @return array An associative array where keys are tax codes and values are the total amounts.
     */
    public static function sumAmountsByTaxCode(Invoice $invoice): array
    {
        $sumsByTaxCode = [];
        foreach ($invoice->invoiceitems as $item) {
            $code = $item->taxpayer_taxable->taxable->tax_label->code;
            $name = $item->taxpayer_taxable->name;
            $amount = $item->amount;
            if (array_key_exists($code, $sumsByTaxCode)) {
                $sumsByTaxCode[$code]['amount'] += $amount;
            } else {
                $sumsByTaxCode[$code] = [
                    'name' => $name,
                    'amount' => $amount
                ];
            }
        }
        asort($sumsByTaxCode);
        return $sumsByTaxCode;
    }

    /**
     * @param array $uuids
     * @return void
     */
    public static function isuperFunction(array $uuids): void
    {
        $data = Invoice::retrieveByUUIDs($uuids);
        usort($data, function ($a, $b) {
            $codeA = $a->taxpayer_taxable->taxable->tax_label->code;
            $codeB = $b->taxpayer_taxable->taxable->tax_label->code;
            return strcmp($codeA, $codeB);
        });
        $default = $data[0];
        $default->invoiceitems()->get();
    }
    public static function getAmountsSummary(): array
    {
        $year = Year::getActiveYear()->name;
        $startDate = Carbon::parse("{$year}-01-01 00:00:00");
        $endDate = Carbon::parse("{$year}-12-31 23:59:59");
        // Amount remaining to be collected
        $totalAmountRemaining = self::whereIn('status', [InvoiceStatusEnums::APPROVED->value, InvoiceStatusEnums::APPROVED_CANCELLATION->value])
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->where('invoices.pay_status', '!=', InvoicePayStatusEnums::PAID->value)
            ->sum('amount');
        $totalReduceAmountRemaining = self::whereIn('status', [InvoiceStatusEnums::APPROVED_CANCELLATION->value])
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->where('invoices.pay_status', '!=', InvoicePayStatusEnums::PAID->value)
            ->whereNotNull('reduce_amount')
            ->sum('reduce_amount');
        $remainingAmount = $totalAmountRemaining - $totalReduceAmountRemaining;
        // Amount collected
        $totalAmountCollected = self::whereIn('status', [InvoiceStatusEnums::APPROVED->value, InvoiceStatusEnums::APPROVED_CANCELLATION->value])
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->where('invoices.pay_status', '=', InvoicePayStatusEnums::PAID->value)
            ->sum('amount');
        $collectedAmount = $totalAmountCollected;
        return [
            'remaining_amount' => $remainingAmount,
            'collected_amount' => $collectedAmount,
        ];
    }
    /**
     * Get payment codes for a given invoice based on the specified amount.
     *
     * This function calculates the payment codes required to cover the specified amount
     * based on the amounts already paid for each tax code of the invoice.
     *
     * @param int $id The ID of the invoice.
     * @param float $amount The amount to be paid.
     * @param array $paymentData Additional data for payments.
     * @return array|null An array containing the payment codes or null if the invoice does not exist.
     */
    public static function getCode($id, float $amount, array $paymentData): ?array
    {
        $invoice = Invoice::find($id);
        if ($invoice instanceof Invoice) {
            $paymentArray = [];
            [$sumsByTaxCode, $paidAmounts] = Invoice::returnPaidAndSumByCode($invoice);
            $paidTotal = array_sum($paidAmounts) ?? 0;
            foreach ($sumsByTaxCode as $code => $code_amount) {
                if ($amount > 0 && $code_amount['amount'] > 0) {
                    if ($paymentData["code"] == null) {
                        $paymentData["code"] = $code;
                    }//elseif ($amount> $sumsByTaxCode[ $paymentData["code"] ]['amount']){$amount=$sumsByTaxCode[$paymentData["code"]]['amount'];}
                    $paymentData['amount'] = min($amount, $code_amount['amount']);
                    if ($paymentArray !== []) {
                        $paymentData['remaining_amount'] = $invoice->amount - ($paidTotal + $paymentData['amount']) + end($paymentArray)['amount'];
                    } else {
                        $paymentData['remaining_amount'] = $invoice->amount - ($paidTotal + $paymentData['amount']);
                    }
                    $paymentArray[] = $paymentData;
                    $amount -= $paymentData['amount'];
                }
            }
            return $paymentArray;
        }
        return null;
    }
    public static function returnPaidAndSumByCode(Invoice $invoice): array
    {
        $last_payments = Payment::where('invoice_id', $invoice->invoice_no)->where('status', PaymentStatusEnums::ACCOUNTED->value)->get();
        $sumsByTaxCode = Invoice::sumAmountsByTaxCode($invoice);
        $paidAmounts = [];
        foreach ($sumsByTaxCode as $code => &$totalAmount) {
            foreach ($last_payments as $index => $payment) {
                if (($payment->description !== Constants::ANNULATION && $payment->description !== Constants::REDUCTION) && $payment->code == $code) {
                    $totalAmount['amount'] -= $payment->amount;
                    $paidAmounts[$index] = $payment->amount;
                }
            }
            if ($totalAmount['amount'] <= 0) {
                unset($sumsByTaxCode[$code]);
            }
        }
        return [$sumsByTaxCode, $paidAmounts];
    }
    public static function addPrintableToInvoices($collection, PrintFile $printFile): PrintFile
    {
        DB::transaction(function () use ($collection, $printFile) {
            foreach ($collection as $invoice) {
                if ($invoice instanceof Invoice) {
                    $invoice->printFiles()->sync($printFile->id);
                }
            }
        });
        return $printFile;
    }
    /**
     * Search for a given value in multiple columns.
     */
    public static function search(string $value): QueryBuilder
    {
        $columns = [
            'id',
            'invoice_no'
        ];
        $query = Invoice::query()->whereIn('invoices.status', [InvoiceStatusEnums::APPROVED->value, InvoiceStatusEnums::APPROVED_CANCELLATION->value])
            ->where('invoices.pay_status', '!=', InvoicePayStatusEnums::PAID->value);
        foreach ($columns as $column) {
            $query->orWhere($column, 'like', "%{$value}%");
        }
        return $query;
    }
    public static function getPrintData(array $filterBy, string $type = null): Collection
    {
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");
        $query = Invoice::whereIn('invoices.status', $filterBy)
            ->where('invoices.type', '=', Constants::INVOICE_TYPE_TITRE)
            ->whereBetween('invoices.created_at', [$startOfYear, $endOfYear]);
        if ($type != null) {
            if ($type === PrintNameEnums::BORDEREAU_REDUCTION->value) {
                $query = $query->whereNot("invoices.reduce_amount", "=", '')
                    ->WhereDoesntHave('printFiles', function ($query) use ($type) {
                        $query->where('name', $type);
                    });
            } elseif ($type === PrintNameEnums::BORDEREAU->value) {
                $query = $query->where("invoices.reduce_amount", "=", '')
                    ->WhereDoesntHave('printFiles', function ($query) use ($type) {
                        $query->where('name', $type);
                    });
            } elseif ($type === PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS->value) {
                $query = $query->whereNot("invoices.ondistributionprint", "=", false)->WhereDoesntHave('printFiles', function ($query) use ($type) {
                    $query->where('name', $type);
                });
            } elseif ($type === PrintNameEnums::FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES->value) {
                $query = $query->whereNot("invoices.onrecoveryprint", "=", false)->WhereDoesntHave('printFiles', function ($query) use ($type) {
                    $query->where('name', $type);
                });
            }
        }
        return $query
            ->get();
    }
    public static function getPrintFile(array $filterBy, string $type = null): ?PrintFile
    {
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");
        $query = Invoice::whereIn('invoices.status', $filterBy)
            ->where('invoices.type', '=', Constants::INVOICE_TYPE_TITRE)
            ->whereBetween('invoices.created_at', [$startOfYear, $endOfYear]);
        if ($type != null) {
            if ($type === PrintNameEnums::BORDEREAU_REDUCTION->value) {
                $query = $query->whereNot("invoices.reduce_amount", "=", '')
                    ->whereHas('printFiles', function ($query) use ($type) {
                        $query->where('name', $type);
                    });
            } elseif ($type === PrintNameEnums::BORDEREAU->value) {
                $query = $query->where("invoices.reduce_amount", "=", '')
                    -> whereHas('printFiles', function ($query) use ($type) {
                        $query->where('name', $type);
                    });
            }
        }

        $invoice = $query->first();
        return $invoice?->printFiles->first();
    }

    public static function getPrintableUuid(string $status = InvoiceStatusEnums::ACCEPTED->value): array
    {
        return Invoice::where('status', $status)
            ->where('type', Constants::TITRE)
            ->select('uuid')
            ->pluck('uuid')
            ->toArray();
    }
    public function canSubmitToRelaunch(): bool
    {
        return $this->isInvoiceLastPaymentOlderThanThreeMonths($this->invoice_id);
    }
    public function isInvoiceLastPaymentOlderThanThreeMonths($invoice_id, int $month = 3): bool
    {
        $invoice = Invoice::find($invoice_id);

        if (!$invoice || $invoice->pay_status === InvoicePayStatusEnums::PAID->value) {
            return false;
        }
        $lastPayment = Payment::where('invoice_id', $invoice_id)
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$lastPayment) {
            return true;
        }
        return $lastPayment->created_at->lt(now()->subMonths($month));
    }

    public function releaseTaxpayerTaxable(): void
    {
        foreach ($this->taxpayer_taxables as $taxpayerTaxable) {
            $taxpayerTaxable->billable = '0';
            $taxpayerTaxable->bill_status = "NOT BILLED";
            $taxpayerTaxable->invoice_id = null;
            $taxpayerTaxable->save();
        }
    }
    public static function fixErrorInvoiceGeneration(): void
    {
        DB::transaction(function () {
            $s_date = Carbon::parse("2025-01-01 00:00:00");
            $e_date = Carbon::parse("2025-3-31 23:59:59");
            $end_of_year = Carbon::createFromDate(date('Y'), 12, 31);
            $invoices = Invoice::whereBetween('invoices.created_at', [$s_date, $e_date])
                ->where('invoices.type', '=', Constants::TITRE)
                ->get();
            foreach ($invoices as $invoice) {
                $edited = false;
                $totalAmount = 0;
                $amountIsEdited = false;
                if ($end_of_year != $invoice->to_date) {
                    $invoice->to_date = $end_of_year;
                    $edited = true;
                    $invoice->status = InvoiceStatusEnums::DRAFT->value;
                    $invoice->validity = 'VALID';
                }
                if ($invoice->id == 100222) {
                }
                foreach ($invoice->invoiceitems as $invoiceitem) {
                    $period = 1;
                    $periodicity = $invoiceitem->taxpayer_taxable->taxable->periodicity;
                    $qty = $periodicity == "Mois" ? 12 : 1;
                    if ($periodicity == "Mois" && $invoiceitem->qty != $qty) {
                        $edited = true;

                        $temp_seize = $invoiceitem->taxpayer_taxable->seize;
                        if ($invoiceitem->taxpayer_taxable->taxable->use_second_formula) {
                            $temp_seize = 1;
                        }
                        if ($invoiceitem->taxpayer_taxable->taxable->tariff_type == "FIXED") {
                            $itemAmount = $invoiceitem->taxpayer_taxable->taxable->tariff * $qty * $temp_seize * $period;
                        } else {
                            $itemAmount = $invoiceitem->taxpayer_taxable->taxable->tariff * $qty * $temp_seize * $period / 100;
                        }
                        $taxpayerTaxable = $invoiceitem->taxpayer_taxable;
                        $taxpayerTaxable->invoice_id = $invoice->id;
                        $taxpayerTaxable->bill_status = 'BILLED';
                        $taxpayerTaxable->save();
                        $totalAmount += $itemAmount;
                        $invoiceitem->amount = $itemAmount;
                        $invoiceitem->ii_tariff = $invoiceitem->taxpayer_taxable->taxable->tariff;
                        $invoiceitem->qty = $qty;
                        $invoiceitem->save();
                        $amountIsEdited = true;
                        $invoice->qty = $qty;
                    }
                }
                if ($edited) {
                    if ($amountIsEdited) {
                        $invoice->amount = $totalAmount;
                    }

                    $invoice->save();
                }
            }
        });
    }
}
