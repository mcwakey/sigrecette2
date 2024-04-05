<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_type',
        'reference',
        'description',
        'remaining_amount',
        'taxpayer_id',
        'invoice_id',
        'user_id',
        'r_user_id',
        'status',
        'uuid'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function r_user()
    {
        return $this->belongsTo(User::class);
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            $payment->uuid = Uuid::uuid4()->toString();
        });
    }
}
