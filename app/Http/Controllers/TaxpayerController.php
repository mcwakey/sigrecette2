<?php
namespace App\Http\Controllers;
use App\DataTables\InvoicesDataTable;
use App\DataTables\RecoveriesDataTable;
use App\DataTables\TaxpayersDataTable;
use App\DataTables\TaxpayerTaxablesDataTable;
use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\TaxpayerStateEnums;
use App\Helpers\Constants;
use App\Imports\TaxpayerImport;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Taxpayer;
use App\Models\Town;
use App\Models\UserLogs;
use App\Models\Year;
use App\Models\Zone;
use App\Services\StatisticsService;
use App\Traits\HandlesDateFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
class TaxpayerController extends Controller
{
    use  HandlesDateFilters;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaxpayersDataTable $dataTable)
    {
        $validatedData = $request->validate([
            'disable' => ['nullable', 'integer', Rule::in(1)],
            'state' => ['nullable', 'string', Rule::in('at')],
        ]);
        $disable = $validatedData['disable'] ?? null;
        $state = $validatedData['state'] ?? null;
        $zones = Zone::all();
        $categories = Category::all();
        $towns = Town::all();
        $cantons = Canton::all();
        $activities = Activity::all();
       // Taxpayer::merge();
        return $dataTable->with(
            [
                'state' => $state,
                'disable' => $disable,
            ]
        )->render('pages/taxpayers.list', ['zones' => $zones, 'categories' => $categories,
            'towns' => $towns,
            'cantons' => $cantons, 'activities' => $activities]);
    }
    public function r_report(Request $request)
    {
        $this->handleDateFilters($request);
       // $result= Taxpayer::taxpayersWithMultipleInvoice($this->s_date, $this->e_date);dump('Mutiple',count($result));foreach ($result as $key => $value) {dump($value->id,$value->name);}dd(Taxpayer::taxpayersWithoutInvoice());

        $taxpayersByZone = Taxpayer::select('zone_id', DB::raw('COUNT(*) as total'))
            ->where('type','=',Constants::TITRE)
            ->where(function ($q) {
                $q->whereNotIn('taxpayers.from_mobile_and_validate_state', [
                    TaxpayerStateEnums::REJECTED,
                    TaxpayerStateEnums::PENDING
                ])->orWhereNull('taxpayers.from_mobile_and_validate_state');
            })
            ->where(function ($q) {
                $q->whereBetween('created_at', [$this->s_date,  $this->e_date])
                    ->orWhereBetween('updated_at', [$this->s_date, $this->e_date]);
            })
            ->groupBy('zone_id')
            ->with('zone')
            ->newQuery()
            ->get();
        $genderCounts = Taxpayer::where('type','=',Constants::TITRE)
            ->selectRaw('gender, count(*) as count')
            ->where(function ($q) {
                $q->whereNotIn('taxpayers.from_mobile_and_validate_state', [ 
                    TaxpayerStateEnums::REJECTED,
                    TaxpayerStateEnums::PENDING
                ])->orWhereNull('taxpayers.from_mobile_and_validate_state');
            })
            ->whereBetween('created_at', [$this->s_date,  $this->e_date])
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();
        if ($taxpayersByZone->isEmpty()) {
            $taxpayersByZone = Taxpayer::select('zone_id', DB::raw('COUNT(*) as total'))
                ->whereHas('invoices', function ($query) {
                    $query->whereBetween('created_at', [$this->s_date, $this->e_date])
                        ->where('type','=',Constants::TITRE);
                })
                ->groupBy('zone_id')
                ->with('zone')
                ->newQuery()
                ->get();
            $genderCounts = Taxpayer::selectRaw('gender, count(*) as count')
                ->whereHas('invoices', function ($query) {
                    $query->whereBetween('created_at', [$this->s_date, $this->e_date])
                        ->where('type','=',Constants::TITRE);
                })->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();
        }


        $zoneLabels = $taxpayersByZone->pluck('zone.name');
        $zoneTotals = $taxpayersByZone->pluck('total');
        $genderLabels = array_keys($genderCounts);
        $genderTotals = array_values($genderCounts);
        $statisticsService = new StatisticsService($this->s_date, $this->e_date);
        [$labels, $taxables,
            $invoices_total,
            $taxpayer_count,
            $taxables_count,
            $invoice_count,] = $statisticsService->c_capacity_data();
        return view('pages/taxpayers/r_taxpayers.show', compact('labels', 'taxables',
            'taxpayer_count','invoices_total','taxpayer_count','invoice_count','taxables_count',
            'zoneLabels','zoneTotals','genderLabels','genderTotals'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    /**
     * Display the specified resource.
     */
    public function show(Taxpayer $taxpayer, InvoicesDataTable $invoicesDataTable,Request $request,
                         RecoveriesDataTable $recoveriesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    {
        if($taxpayer->type ==Constants::INVOICE_TYPE_COMPTANT){
            return redirect()->back();

        }
        $this->handleDateFilters($request);
        $statisticsService = new StatisticsService($this->s_date, $this->e_date);
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        $taxpayerActionLog = UserLogs::where('taxpayer_id',"=", $taxpayer->id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        $superData = $statisticsService->getPaymentStats($taxpayer->id);
        return $taxablesDataTable->with('id', $taxpayer->id)
            ->render('pages/taxpayers.show', [
                'taxpayer' => $taxpayer,
                'taxpayerActionLog' => $taxpayerActionLog,
                'invoicesDataTable' => $invoicesDataTable->with('id', $taxpayer->id)->html(),
                'recoveriesDataTable' => $recoveriesDataTable->with('id', $taxpayer->id)->html(),
                'payments' => $superData['payments'],
                'totalMonthlyPayments' => $superData['totalMonthlyPayments'],
                'totalOwing' => $superData['totalOwing'],
                'paidPercentage' =>$superData['paidPercentage'],
            ]);
    }
    /**
     * Display the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taxpayer $taxpayer)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Taxpayer $taxpayer)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taxpayer $taxpayer)
    {
        //
    }
    public function showImportPage()
    {
        return view('pages/taxpayers/import.show');
    }
    public function import(Request $request)
    {
        if ($request->file('file')) {
            Excel::queueImport(new TaxpayerImport,
                $request->file('file')->store('files'));
            return redirect()->back();
        }
        $filename = "data.xlsx";
        if (!Storage::missing("imports")) {
            $filePath = Storage::path('imports') . DIRECTORY_SEPARATOR . $filename;
            Excel::queueImport(new TaxpayerImport, $filePath);
        }
        return redirect()->back();
    }
}
