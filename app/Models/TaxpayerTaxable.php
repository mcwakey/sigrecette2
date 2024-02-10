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
        'taxpayer_id',
        'taxable_id',
        // 'penalty',
        // 'penalty_type',
        // 'tax_label_id',
    ];

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function taxable()
    {
        return $this->belongsTo(Taxable::class);
    }
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
