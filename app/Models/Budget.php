<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_id',
        'tax_label_id',
        'expected_amount',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function tax_label()
    {
        return $this->belongsTo(TaxLabel::class);
    }
    public static function updateOrCreateBudget($yearId, $taxLabelId, $expectedAmount)
    {
        return self::updateOrCreate(
            [
                'year_id' => $yearId,
                'tax_label_id' => $taxLabelId,
                'expected_amount' => $expectedAmount,
            ]
        );
    }
}
