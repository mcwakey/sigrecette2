<?php
namespace App\Http\Controllers;
use App\Models\Year;
use App\Services\StatisticsService;
use App\Traits\HandlesDateFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    use  HandlesDateFilters;
    protected $statisticsService;

    public function index(Request $request)
    {
        $this->handleDateFilters($request);
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        $this->statisticsService = new StatisticsService($this->s_date, $this->e_date);
       // dd($this->s_date, $this->e_date);
        //dd($this->statisticsService->getInvoiceByCreatedAt($startDate, $endDate)[0]);
        return view('pages/dashboards.index', [
            'stats' => $this->statisticsService->getStats(),
            'taxpayer_by_created_at' => $this->statisticsService->getTaxpayerByCreatedAt(),
            'invoice_by_created_at'=>$this->statisticsService->getInvoiceByCreatedAt(),
            'payments_data'=>$this->statisticsService->getPaymentsEvolution(),
        ]);
    }
}
