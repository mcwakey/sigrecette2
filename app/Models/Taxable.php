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
        'tariff_type',
        'unit',
        'unit_type',
        'modality',
        'periodicity',
        'penalty',
        'penalty_type',
        'tax_label_id',
        'use_second_formula'
    ];

    protected function casts(): array
    {
        return [
            'use_second_formula' => 'boolean',
        ];
    }
    public function tax_label()
    {
        return $this->belongsTo(TaxLabel::class);
    }

    public function taxpayertaxables()
    {
        return $this->hasMany(TaxpayerTaxable::class);
    }

    public function stock_requests()
    {
        return $this->hasMany(StockRequest::class);
    }
}
