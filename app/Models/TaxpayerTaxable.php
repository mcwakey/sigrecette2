<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxpayerTaxable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'seize',
        'location',
        'longitude',
        'latitude',
        'taxpayer_id',
        'taxable_id',
        'billable',
        'invoice_id',
        'bill_status',
        'auth_reference'
    ];

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function taxable()
    {
        return $this->belongsTo(Taxable::class);
    }

    public function invoice_item()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


}
