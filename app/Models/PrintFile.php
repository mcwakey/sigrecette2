<?php

namespace App\Models;

use App\Enums\PrintNameEnums;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrintFile extends Model
{
    protected $fillable = [
        'name',
        'last_sequence_number',
        'total_last_sequence',
        'user_id',

    ];
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }
    public static function getLastPrintFileByType($type):PrintFile|null{
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");
        return PrintFile::where('name',$type)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->orderBy('created_at','desc')
            ->first();
    }
    public static function createPrintFile(string $type, $data,$total=0,User $user=null):PrintFile{
        $last_print = PrintFile::getLastPrintFileByType($type);
        $print_data = [
            'name' =>$type,
            'last_sequence_number' => $last_print==null?1:$last_print->last_sequence_number+1,
            'total_last_sequence'=>$last_print==null?$total:$last_print->total_last_sequence,
            'user_id'=>$user->id
        ];
        $print= PrintFile::create($print_data);
        return Invoice::addPrintableToInvoices($data,$print);
    }
    public static function getPrintTotal(PrintFile $file):int{
        $data = $file->invoices()->get();
        $total=0;
        if($file->name==PrintNameEnums::BORDEREAU_REDUCTION){
            foreach ($data as $invoice){
                $total+=$invoice->reduce_amount;
            }
        }else{
            foreach ($data as $invoice){
                $total+=$invoice->amount;
            }
        }
        return $total;
    }

}
