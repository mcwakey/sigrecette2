<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'code',
    ];

    public function taxables()
    {
        return $this->hasMany(Taxable::class);
    }
}
