<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\Models\Commune;
use App\Models\Taxpayer;
use App\DataTables\CommuneDataTable;
use App\DataTables\TaxpayerInvoicesDataTable;
use App\DataTables\TaxpayerTaxablesDataTable;
use App\Http\Controllers\Controller;
use App\Models\UserLogs;
use Illuminate\Http\Request;
use App\Imports\TaxpayerImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CommunesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CommuneDataTable $dataTable)
    {
        return $dataTable->render('pages/communes.list');
    }

    // public function index()
    // {
    //     // Instantiate both data tables
    //     $CommuneDataTable = app()->make(CommuneDataTable::class);
    //     $invoicesDataTable = app()->make(InvoicesDataTable::class);

    //     // Render both data tables into variables
    //     $CommuneDataTableHtml = $CommuneDataTable->render('pages.commune.list');
    //     //dd($CommuneDataTableHtml);

    //     $invoicesDataTableHtml = $invoicesDataTable->render('pages.commune.list');
    //     //dd($CommuneDataTable);

    //     // Pass both data tables HTML to the view
    //     return view('pages.commune.list', compact('CommuneDataTableHtml', 'invoicesDataTableHtml'));
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
    public function show(Commune $commune , TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    {
        // Assuming you want to pass the $commune  to the show view
        // return view('pages.commune.show', compact('taxpayer'))
        //     ->with('dataTable', $dataTable->html());
        //return $dataTable->render('pages/commune.show')-> with ('taxpayer', $commune );



        return $taxablesDataTable->with('id', $commune ->id)
                ->render('pages/communes.show', compact('commune'));
    }

    /**
     * Display the specified resource.
     */
    // public function show(Commune $commune , TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    // {
    //     // Assuming you want to pass the $commune  to the show view

    //     // return view('pages.commune.show', compact('taxpayer'))
    //     //     ->with('invoicesDataTable', $invoicesDataTable->with('id', $commune ->id)->html());
    //         // ->with('taxablesDataTable', $taxablesDataTable->with('id', $commune ->id)->html());

    //     //return $dataTable->render('pages/commune.show')-> with ('taxpayer', $commune );

    //     // return $dataTable->with('id', $commune ->id),

    //     $invoicesDataTable->with('id', $commune ->id)
    //             ->render('pages/commune.show', compact('taxpayer'));
    // }

    // public function show(Commune $commune , TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    // {
    //     // Pass the $commune  object and its ID to both DataTables
    //     $invoicesDataTable->with('id', $commune ->id)->with('taxpayer', $commune );
    //     $taxablesDataTable->with('id', $commune ->id)->with('taxpayer', $commune );

    //     // Render the show view with both DataTables
    //     return view('pages.commune.show', compact('taxpayer'))->with([
    //         'invoicesDataTable' => $invoicesDataTable->render(),
    //         'taxablesDataTable' => $taxablesDataTable->render(),
    //     ]);
    // }

    // public function show(Commune $commune , TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    // {
    //     // Pass the $commune  object and its ID to both DataTables
    //     $invoicesDataTable->with('id', $commune ->id)->with('taxpayer', $commune );
    //     $taxablesDataTable->with('id', $commune ->id)->with('taxpayer', $commune );

    //     // Get the HTML content of both DataTables
    //     //$invoicesDataTableHtml = $invoicesDataTable->render();
    //     //$taxablesDataTableHtml = $taxablesDataTable->render();

    //     // Render the show view with both DataTables HTML content and the $commune  object
    //     return view('pages.commune.show', compact('taxpayer', 'invoicesDataTableHtml', 'taxablesDataTableHtml'));
    // }



//     public function show($identifier, TaxablesDataTable $dataTable)
// {
//     // Check if $identifier is numeric, then assume it's the taxpayer ID
//     if (is_numeric($identifier)) {
//         $commune  = Taxpayer::find($identifier);

//         if (!$commune ) {
//             // Handle case where taxpayer is not found (e.g., show an error page or redirect)
//             abort(404);
//         }

//         return view('pages.commune.show', compact('taxpayer', 'dataTable'));
//     }

//     // If $identifier is not numeric, assume it's a DataTable request
//     return $dataTable->render('pages.commune.show');
// }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commune $commune )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commune $commune )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commune $commune )
    {
        //
    }


}
