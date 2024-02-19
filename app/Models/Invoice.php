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
        // 'password',
        // 'last_login_at',
        // 'last_login_ip',
        // 'profile_photo_path',
    ];
    
    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function taxpayertaxables()
    {
        return $this->hasMany(TaxpayerTaxable::class);
    }

    // public function taxpayertaxables()
    // {
    //     return $this->belongsTo(Taxpayertaxable::class);
    // }
}
