<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable = [
        'name',
        'title',
        'region_name',
        'mayor_name',
        'phone_number',
        'address',
        'treasury_name',
        'treasury_address',
        'treasury_rib',
        'longitude',
        'latitude',
        'limit_json'
    ];

    /**
     * Get the first commune.
     *
     * @return Commune|null
     */
    public static function getFirstCommune(): ?Commune
    {
        return Commune::orderBy('id')->first();
    }
}
