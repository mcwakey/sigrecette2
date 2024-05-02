<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintFile extends Model
{
    protected $fillable = [
        'name',
        'last_sequence_number',
        'total_last_sequence',

    ];
    public static function getLastPrintFileByType($type){
        return PrintFile::where('name',$type)
            ->orderBy('created_at','desc')
            ->first();
    }

}
