<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
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
        'current_month',
        'auto_switch'
    ];
    use HasFactory;

    /**
     * Get the active year.
     *
     * @return Year
     */
    public static function  getActiveYear():Year {
        $currentYear = date('Y');
        $current_mounth = Carbon::now()->format('m');
        $activeYear = Year::where('status', "ACTIVE")->first();


        if (!$activeYear) {
            $activeYear = Year::where('name', $currentYear)->first()?? Year::getCurrentYear();
            $activeYear->status = "ACTIVE";
            DB::transaction(function () use ($activeYear) {
                $activeYear->save();
            });
        }
        if($activeYear->auto_switch ==true){
            if (intval($activeYear->name) < intval($currentYear) ) {
                $activeYear= Year::autoUpdateActiveYear( $activeYear);
            }
            if(
                (intval($activeYear->name) == intval($currentYear))
                && $activeYear->current_month!=$current_mounth ){
                $activeYear = Year::autoUpdateOrCreateCurrentMonth($current_mounth,$activeYear);
            }
        }

        return $activeYear;
    }

    /**
     * Automatically updates the active year.
     *
     * @param Year $active_year
     * @return Year
     */
    public static function autoUpdateActiveYear(Year $active_year): Year{
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

    /**
     * Makes all years inactive.
     *
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

    /**
     * Met à jour ou crée le mois actuel dans la base de données.
     *
     * @param $current_mounth
     * @param Year $year
     * @return Year
     */
    public static function autoUpdateOrCreateCurrentMonth($current_mounth,Year $year):Year
    {
        $year->current_month = $current_mounth;
        DB::transaction(function () use ($year) {
            $year->save();
        });

        return $year;



    }

    /**
     * @return Year
     */
    private static function getCurrentYear():Year
    {
        $currentYear = date('Y');
        $data = [
            'name' => $currentYear,
            'status' => "INACTIVE",
        ];
        $year = Year::where('name', $currentYear)->first() ?? Year::create($data);
        $year->status = "ACTIVE";
        $year->auto_switch =true;
        DB::transaction(function () use ($year) {
            $year->save();
        });
        return $year;
    }
}
