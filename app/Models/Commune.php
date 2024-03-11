<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable = [
        'mayor_name',
        'phone_number',
        'address',
        'treasury_name',
        'treasury_address',
        'treasury_rib',
    ];

    public static function getOrCreate(array|null $info=null)
    {
        return self::firstOr(function () use ($info) {
            return self::create($info);
        });
    }
}
