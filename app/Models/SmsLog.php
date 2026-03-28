<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'phone_number',
        'message',
        'provider',
        'status',
        'external_id',
        'meta',
        'loggable_type',
        'loggable_id',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }
}
