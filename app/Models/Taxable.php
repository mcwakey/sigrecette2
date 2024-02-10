<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tariff',
        'unit',
        'modality',
        'periodicity',
        'penalty',
        'penalty_type',
        'tax_label_id',
    ];

    public function tax_label()
    {
        return $this->belongsTo(TaxLabel::class);
    }

    public function taxpayertaxables()
    {
        return $this->hasMany(TaxpayerTaxable::class);
    }
}
