<?php
namespace App\Traits;

use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait HandlesDateFilters
{
    protected $s_date;
    protected $e_date;

    public function handleDateFilters(Request $request)
    {

        if ($request->has('reset_filters')) {
            session()->forget(['s_date', 'e_date']);
        }

        $request->merge([
            's_date' => $request->s_date ? Carbon::parse($request->s_date)->format('Y-m-d') : null,
            'e_date' => $request->e_date ? Carbon::parse($request->e_date)->format('Y-m-d') : null,
        ]);

        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d',
            'e_date' => 'nullable|date_format:Y-m-d|after_or_equal:s_date',
        ]);


        if($validatedData['s_date']&& $validatedData['e_date']){
            $this->s_date = $validatedData['s_date'] ;
            $this->e_date = $validatedData['e_date'] ;
            session()->put('s_date', $this->s_date );
            session()->put('e_date', $this->e_date);
        }elseif (session()->get('s_date',null)&& session()->get('e_date',null)){
            $this->s_date = session()->get('s_date');
            $this->e_date = session()->get('e_date');
        }else{
            ['s_date' => $this->s_date, 'e_date' => $this->e_date] = $this->getDefaultDateRange();
        }


        view()->share('s_date', is_string($this->s_date)?$this->s_date :$this->s_date->toDateString());
        view()->share('e_date',  is_string($this->e_date)?$this->e_date:$this->e_date->toDateString());
    }
    protected function getDefaultDateRange()
    {
        $year = Year::getActiveYear()->name;
        return [
            's_date' => Carbon::parse("{$year}-01-01 00:00:00"),
            'e_date' => Carbon::parse("{$year}-12-31 23:59:59"),
        ];
    }

}
