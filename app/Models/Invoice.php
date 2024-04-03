<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

//id 	invoice_no 	order_no 	nic 	status 	pay_status 	delivery 	delivery_date


}
