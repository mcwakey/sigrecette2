<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = [
        'name',
        'status',
            ];
    use HasFactory;

    public static function  getActiveYear():Year {
        $currentYear = date('Y');
        $activeYear = Year::where('status', "ACTIVE")->first();

        if (!$activeYear) {
            $activeYear = Year::where('name', $currentYear)->first();
        }

        return $activeYear;
    }
}
