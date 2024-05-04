<?php

namespace App\Models;

use App\Enums\PrintNameEnums;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PrintFile extends Model
{
    protected $fillable = [
        'name',
        'last_sequence_number',
        'total_last_sequence',

    ];
    public static function getLastPrintFileByType($type):PrintFile|null{
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");
        return PrintFile::where('name',$type)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->orderBy('created_at','desc')
            ->first();
    }
    public static function createPrintFile(string $type, $data):PrintFile{
        $last_print = PrintFile::getLastPrintFileByType($type);
        $print_data = [
            'name' =>$type,
            'last_sequence_number' => $last_print==null?1:$last_print->last_sequence_number+1,
            'total_last_sequence'=>$last_print==null?0:$last_print->total_last_sequence
        ];
        $print= PrintFile::create($print_data);

        //dd($data,$print);
        return Invoice::addPrintableToInvoices($data,$print);
    }

}
