<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'taxpayer_id',
        'amount',
        'qty',
        'from_date',
        'to_date',
        // 'last_login_ip',
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
}
