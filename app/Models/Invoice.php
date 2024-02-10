<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
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
