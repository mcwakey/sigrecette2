<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Services\StatisticsService;

class DashboardController extends Controller
{
    protected $statisticsService;

    public function __construct()
    {
        $this->statisticsService = new StatisticsService();
    }
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        return view('pages/dashboards.index',['stats'=>$this->statisticsService->getStats()]);
    }
}
