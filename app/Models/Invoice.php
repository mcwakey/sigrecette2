<?php

namespace App\Models;

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
     * @return array|bool Returns a collection of invoices if all UUIDs are found,
     *                               `false` if any UUID is not found, or an empty array if `$uuids` is empty.
     */
    public static function retrieveByUUIDs(array $uuids): array|bool
    {
        $invoices = [];
        foreach ($uuids as $uuid) {
            $invoice = Invoice::where('uuid', $uuid)->first();
            if ($invoice instanceof Invoice) {
                $invoices[] = $invoice;
            } else {
                return false;
            }
        }




        return $invoices ?: [];
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
    public static function sumAmountsByTaxCode(Invoice $invoice)
    {
        //$data = Invoice::retrieveByUUIDs($uuids);

        $sumsByTaxCode = [];
        //$invoice=$data[0];

        foreach ($invoice->invoiceitems as $item) {
            $code = $item->taxpayer_taxable->taxable->tax_label->code;
            $amount = $item->amount;
            if (array_key_exists($code, $sumsByTaxCode)) {
                $sumsByTaxCode[$code] += $amount;
            } else {
                $sumsByTaxCode[$code] = $amount;
            }
        }

        asort($sumsByTaxCode);
        //dd($sumsByTaxCode);
        return $sumsByTaxCode;
    }
    public static function getCode($id, int $amount, array $paymentData): ?array {
        $invoice = Invoice::find($id);

        if ($invoice instanceof Invoice) {
            $paymentArray = [];
            $last_payments = Payment::where('invoice_id', $invoice->invoice_no)->get();
            $sumsByTaxCode = Invoice::sumAmountsByTaxCode($invoice);
            $s_amount= [];
            foreach ($sumsByTaxCode as $code => &$totalAmount) {
                foreach ($last_payments as $index => $payment) {
                    if ($payment->description !== "Annulation/Réduction" && $payment->code == $code) {
                        $totalAmount -= $payment->amount;
                        $s_amount[$index] = $payment->amount;
                    }
                }

                if ($totalAmount === 0) {
                    unset($sumsByTaxCode[$code]);
                }
            }
            $paid = array_sum($s_amount) ?? 0;
            foreach ($sumsByTaxCode as $code => $code_amount) {
                if ($amount > 0&& $code_amount>0) {
                    $paymentData["code"] = $code;
                    $paymentData['amount'] = min($amount, $code_amount);
                    $paymentData['remaining_amount']= $invoice->amount -( $paid+$paymentData['amount']) ;
                    $paymentArray[] = $paymentData;
                    $amount -= $paymentData['amount'];
                }
            }

            return $paymentArray;
        }

        return null;
    }


}
