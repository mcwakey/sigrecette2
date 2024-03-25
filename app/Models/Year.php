<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

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
            $activeYear->status = "ACTIVE";
            DB::transaction(function () use ($activeYear) {
                $activeYear->save();
            });
        }
        if ($activeYear->name != $currentYear) {
            return Year::AutoUpdateActiveYear();
        }

        return $activeYear;
    }
    public static function AutoUpdateActiveYear(): ?Year{
        $active_year=  Year::where('status', "ACTIVE")->first();
        if (!$active_year) {
            $active_year->status="INACTIVE";
            $next_year =intval( $active_year->name)+1;
            $data = [
                'name' => $next_year,
                'status' => "INACTIVE",
            ];
            Year::makeAllYearsInative();
            $year = Year::where('name',$next_year)->first() ?? Year::create($data);
            $year->status="ACTIVE";
            DB::transaction(function () use ($active_year, $year) {
                $year->save();
                $active_year->save();
            });
            return $year;
        }
       return null;

    }
    public static function makeAllYearsInative(){
        DB::transaction(function ()  {
            $activeYears = Year::where('status', "ACTIVE")->get();
            foreach ($activeYears as $year){
                $year->status = "INACTIVE";
                $year->save();
            }
        });

    }
}
