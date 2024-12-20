<?php
namespace App\Http\Controllers;
use App\DataTables\InvoicesDataTable;
use App\DataTables\RecoveriesDataTable;
use App\DataTables\TaxpayerInvoicesDataTable;
use App\DataTables\TaxpayerInvoicesDataTableDataTableHtml;
use App\DataTables\TaxpayersDataTable;
use App\DataTables\TaxpayerTaxablesDataTable;
use App\Helpers\Constants;
use App\Imports\TaxpayerImport;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Taxpayer;
use App\Models\Town;
use App\Models\UserLogs;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
class TaxpayerController extends Controller
{
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
        return $dataTable->with(
            [
                'state' => $state,
                'disable' => $disable,
            ]
        )->render('pages/taxpayers.list', ['zones' => $zones, 'categories' => $categories, 'towns' => $towns, 'cantons' => $cantons, 'activities' => $activities]);
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
    public function show(Taxpayer $taxpayer, InvoicesDataTable $invoicesDataTable, RecoveriesDataTable $recoveriesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    {
        if($taxpayer->type ==Constants::INVOICE_TYPE_COMPTANT){
            return redirect()->back();

        }
        $taxpayerActionLog = UserLogs::where('taxpayer_id', $taxpayer->id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return $taxablesDataTable->with('id', $taxpayer->id)
            ->render('pages/taxpayers.show', [
                'taxpayer' => $taxpayer,
                'taxpayerActionLog' => $taxpayerActionLog,
                'invoicesDataTable' => $invoicesDataTable->with('id', $taxpayer->id)->html(),
                'recoveriesDataTable' => $recoveriesDataTable->with('id', $taxpayer->id)->html(),
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
