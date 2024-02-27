<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'taxpayer_taxable_id',
        'qty',	 	 	
        'amount',
        'invoice_id',
    ];
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function taxpayer_taxable()
    {
        return $this->belongsTo(Taxpayertaxable::class);
    }
}
