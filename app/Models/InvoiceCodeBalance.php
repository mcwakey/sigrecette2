<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceCodeBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'taxpayer_id',
        'code',
        'year',
        'amount_billed',
        'amount_paid',
        'remaining_amount',
        'status',
        'last_payment_at',
    ];

    protected $casts = [
        'amount_billed' => 'float',
        'amount_paid' => 'float',
        'remaining_amount' => 'float',
        'last_payment_at' => 'datetime',
        'year' => 'integer',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }
}
