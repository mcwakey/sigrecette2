<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\Year;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{

    protected $statisticsService;

    public function index(Request $request)
    {
        $year = Year::getActiveYear()->name;
        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d H:i:s',
            'e_date' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        $startDate = $validatedData['s_date'] ?? Carbon::parse("{$year}-01-01 00:00:00");
        $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        $this->statisticsService = new StatisticsService();

        return view('pages/dashboards.index',[
            'stats'=>$this->statisticsService->getStats(),
            's_date'=>$startDate,
            'e_date'=>$endDate

        ]);
    }
}
