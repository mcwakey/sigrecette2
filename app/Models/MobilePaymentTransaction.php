<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilePaymentTransaction extends Model
{
    protected $fillable = [
        'reference',
        'invoice_id',
        'taxpayer_id',
        'amount',
        'phone_number',
        'provider',
        'network',
        'external_id',
        'status',
        'verification_attempts',
        'last_checked_at',
        'verified_at',
        'expires_at',
        'provider_response',
        'meta',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'provider_response' => 'array',
        'meta' => 'array',
        'last_checked_at' => 'datetime',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
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

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerifying(): bool
    {
        return $this->status === 'verifying';
    }

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }
}
