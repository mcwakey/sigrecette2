<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Throwable;

class Year extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
            ];
    use HasFactory;

    /**
     * Get the active year.
     *
     * @return Year|null
     * @throws \Throwable
     */
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
        //&&  !(Date::today())->isSameAs('12-31')
        if (intval($activeYear->name) < intval($currentYear)) {
            return Year::autoUpdateActiveYear();
        }

        return $activeYear;
    }
    /**
     * Automatically updates the active year.
     *
     * @return Year|null
     * @throws Throwable
     */
    public static function autoUpdateActiveYear(): Year{
        $active_year=  Year::where('status', "ACTIVE")->first();
        if (!$active_year) {
            $currentYear = date('Y');
            $activeYear = Year::where('name', $currentYear)->first();
            $activeYear->status = "ACTIVE";
            DB::transaction(function () use ($activeYear) {
                $activeYear->save();
            });
        }
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
    public static function activeCurrentYear():Year{
        $currentYear = date('Y');
        $activeYear = Year::where('name', $currentYear)->first();
        $activeYear->status = "ACTIVE";
        DB::transaction(function () use ($activeYear) {
            $activeYear->save();
        });
        return $activeYear;
    }
    /**
     * Makes all years inactive.
     *
     * @throws Throwable
     */
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
