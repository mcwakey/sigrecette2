<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\Models\Taxpayer;
use App\DataTables\TaxpayersDataTable;
use App\DataTables\TaxpayerInvoicesDataTable;
use App\DataTables\TaxpayerTaxablesDataTable;
use App\Http\Controllers\Controller;
use App\Models\UserLogs;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Imports\TaxpayerImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TaxpayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxpayersDataTable $dataTable)
    {
        $zones = Zone::all();
        return $dataTable->render('pages/taxpayers.list', compact('zones'));
    }

    // public function index()
    // {
    //     // Instantiate both data tables
    //     $taxpayersDataTable = app()->make(TaxpayersDataTable::class);
    //     $invoicesDataTable = app()->make(InvoicesDataTable::class);

    //     // Render both data tables into variables
    //     $taxpayersDataTableHtml = $taxpayersDataTable->render('pages.taxpayers.list');
    //     //dd($taxpayersDataTableHtml);

    //     $invoicesDataTableHtml = $invoicesDataTable->render('pages.taxpayers.list');
    //     //dd($taxpayersDataTable);

    //     // Pass both data tables HTML to the view
    //     return view('pages.taxpayers.list', compact('taxpayersDataTableHtml', 'invoicesDataTableHtml'));
    // }



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
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Taxpayer $taxpayer, TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    {
        // Assuming you want to pass the $taxpayer to the show view
        // return view('pages.taxpayers.show', compact('taxpayer'))
        //     ->with('dataTable', $dataTable->html());
        //return $dataTable->render('pages/taxpayers.show')-> with ('taxpayer', $taxpayer);


        $taxpayerActionLog = UserLogs::where('taxpayer_id',$taxpayer->id)
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();

        return $taxablesDataTable->with('id', $taxpayer->id)
                ->render('pages/taxpayers.show', compact('taxpayer','taxpayerActionLog'));
    }

    /**
     * Display the specified resource.
     */
    // public function show(Taxpayer $taxpayer, TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    // {
    //     // Assuming you want to pass the $taxpayer to the show view

    //     // return view('pages.taxpayers.show', compact('taxpayer'))
    //     //     ->with('invoicesDataTable', $invoicesDataTable->with('id', $taxpayer->id)->html());
    //         // ->with('taxablesDataTable', $taxablesDataTable->with('id', $taxpayer->id)->html());

    //     //return $dataTable->render('pages/taxpayers.show')-> with ('taxpayer', $taxpayer);

    //     // return $dataTable->with('id', $taxpayer->id),

    //     $invoicesDataTable->with('id', $taxpayer->id)
    //             ->render('pages/taxpayers.show', compact('taxpayer'));
    // }

    // public function show(Taxpayer $taxpayer, TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    // {
    //     // Pass the $taxpayer object and its ID to both DataTables
    //     $invoicesDataTable->with('id', $taxpayer->id)->with('taxpayer', $taxpayer);
    //     $taxablesDataTable->with('id', $taxpayer->id)->with('taxpayer', $taxpayer);

    //     // Render the show view with both DataTables
    //     return view('pages.taxpayers.show', compact('taxpayer'))->with([
    //         'invoicesDataTable' => $invoicesDataTable->render(),
    //         'taxablesDataTable' => $taxablesDataTable->render(),
    //     ]);
    // }

    // public function show(Taxpayer $taxpayer, TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    // {
    //     // Pass the $taxpayer object and its ID to both DataTables
    //     $invoicesDataTable->with('id', $taxpayer->id)->with('taxpayer', $taxpayer);
    //     $taxablesDataTable->with('id', $taxpayer->id)->with('taxpayer', $taxpayer);

    //     // Get the HTML content of both DataTables
    //     //$invoicesDataTableHtml = $invoicesDataTable->render();
    //     //$taxablesDataTableHtml = $taxablesDataTable->render();

    //     // Render the show view with both DataTables HTML content and the $taxpayer object
    //     return view('pages.taxpayers.show', compact('taxpayer', 'invoicesDataTableHtml', 'taxablesDataTableHtml'));
    // }



//     public function show($identifier, TaxablesDataTable $dataTable)
// {
//     // Check if $identifier is numeric, then assume it's the taxpayer ID
//     if (is_numeric($identifier)) {
//         $taxpayer = Taxpayer::find($identifier);

//         if (!$taxpayer) {
//             // Handle case where taxpayer is not found (e.g., show an error page or redirect)
//             abort(404);
//         }

//         return view('pages.taxpayers.show', compact('taxpayer', 'dataTable'));
//     }

//     // If $identifier is not numeric, assume it's a DataTable request
//     return $dataTable->render('pages.taxpayers.show');
// }




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

        if($request->file('file')){
            Excel::queueImport(new TaxpayerImport,
                $request->file('file')->store('files'));
            return redirect()->back();
        }

        $filename= "data.xlsx";

        if (!Storage::missing("imports")) {
            $filePath = Storage::path('imports') . DIRECTORY_SEPARATOR . $filename;
            Excel::queueImport(new TaxpayerImport, $filePath);
        }

        return redirect()->back();
    }
}
