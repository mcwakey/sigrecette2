<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'taxpayer_id',
        'ip_address',
        'request',
        'response'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }
}

