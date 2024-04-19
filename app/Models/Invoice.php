<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'taxpayer_id',
        'invoice_no',
        'order_no',
        'amount',
        'reduce_amount',
        'qty',
        'from_date',
        'to_date',
        'pay_status',
        'status',
        'uuid',
        'delivery_date'
        // 'profile_photo_path',
    ];

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function taxpayer_taxables()
    {
        return $this->hasMany(TaxpayerTaxable::class);
    }

    public function getDefaulttaxpayer_taxableAttribute()
    {
        return $this->taxpayer_taxables()->first();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoiceitems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public static function countInvoices(Year $year){
        return Invoice::whereYear('created_at', $year->name)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->merge(['NOEXPIRED' => Invoice::whereYear('created_at', $year->name)
                ->where('status', '!=', 'EXPIRED')
                ->count()])
            ->merge(['Pending' => Invoice::whereYear('created_at', $year->name)
                ->where('status', '!=', 'EXPIRED')
                ->where('delivery', '=', 'NOT DELIVERED')
                ->count()])
            ->merge(['Total' => Invoice::whereYear('created_at', $year->name) ->count()])
            ->toArray();
    }

    public static function getReceiverName($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice && $invoice->delivery_date !== null) {
            return $invoice->delivery_to;
        }
        return "";
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            $invoice->uuid = Uuid::uuid4()->toString();
        });
    }

    /**
     * Retrieve invoices based on provided UUIDs.
     *
     * @param array $uuids
     * @param string|null $type
     * @return array Returns a collection of invoices if all UUIDs are found,
     *                               `false` if any UUID is not found, or an empty array if `$uuids` is empty.
     */
    public static function retrieveByUUIDs(array $uuids, string|null $type = null): array
    {
        $invoices = [];

        if (empty($uuids)) {
            return [];
        }

        foreach ($uuids as $uuid) {
            $invoice = null;

            if ($type === 'payment') {
                $payment = Payment::where('uuid', $uuid)->first();

                if ($payment instanceof Payment) {
                    $invoiceId = $payment->invoice_id;

                    // Vérifiez si cette invoice a déjà été récupérée
                    if (!isset($invoices[$invoiceId])) {
                        $invoice = Invoice::find($invoiceId);
                        if ($invoice instanceof Invoice) {
                            $invoices[$invoiceId] = $invoice;
                        }
                    }
                }
            } else {
                $invoice = Invoice::where('uuid', $uuid)->first();
            }

            if ($invoice instanceof Invoice) {
                $invoices[$invoice->id] = $invoice;
            }
        }

        return array_values($invoices); // Réorganise les valeurs pour obtenir un tableau indexé à partir de 0
    }




    public static function isuperFunction(array $uuids){
        $data=Invoice::retrieveByUUIDs($uuids);
        usort($data, function ($a, $b) {
            $codeA = $a->taxpayer_taxable->taxable->tax_label->code;
            $codeB = $b->taxpayer_taxable->taxable->tax_label->code;
            return strcmp($codeA, $codeB);
        });

        $default= $data[0];
        $invoiceitems=$default->invoiceitems()->get();
        //$array = (array)$invoiceitems;
        dd($invoiceitems);
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
    public static function sumAmountsByTaxCode(Invoice $invoice)
    {
        $sumsByTaxCode = [];

        foreach ($invoice->invoiceitems as $item) {
            $code = $item->taxpayer_taxable->taxable->tax_label->code;
            $name = $item->taxpayer_taxable->taxable->tax_label->name;
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
    public static function getCode($id, float $amount, array $paymentData): ?array {
        $invoice = Invoice::find($id);

        if ($invoice instanceof Invoice) {
            $paymentArray = [];
            $last_payments = Payment::where('invoice_id', $invoice->invoice_no)->get();
            $sumsByTaxCode = Invoice::sumAmountsByTaxCode($invoice);
            $paidAmounts = [];
            foreach ($sumsByTaxCode as $code => &$totalAmount) {
                foreach ($last_payments as $index => $payment) {
                    if (($payment->description !== Constants::$ANNULATION && $payment->description !== Constants::$REDUCTION) && $payment->code == $code) {
                        $totalAmount['amount'] -= $payment->amount;
                        $paidAmounts[$index] = $payment->amount;
                    }
                }
                if ($totalAmount['amount'] <= 0) {
                    unset($sumsByTaxCode[$code]);
                }
            }
            $paidTotal = array_sum($paidAmounts) ?? 0;
            foreach ($sumsByTaxCode as $code => $code_amount) {
                if ($amount > 0 && $code_amount['amount'] > 0) {
                    $paymentData["code"] = $code;
                    $paymentData['amount'] = min($amount, $code_amount['amount']);
                    $paymentData['remaining_amount'] = $invoice->amount - ($paidTotal + $paymentData['amount']);
                    $paymentArray[] = $paymentData;
                    $amount -= $paymentData['amount'];
                }
            }
            return $paymentArray;
        }
        return null;
    }

    public static function getRestToPaid(Invoice $invoice):int{
        $s_amount= [];
        $last_payments = Payment::where('invoice_id', $invoice->invoice_no)->get();
        foreach ($last_payments as $index => $payment) {
            //if ($payment->description !== "Annulation/Réduction") {
                $s_amount[$index] = $payment->amount;

        }
        $paid=array_sum($s_amount) ?? 0;
        return $invoice->amount - $paid;

    }


}
