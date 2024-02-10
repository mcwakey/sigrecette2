<?php

namespace App\Http\Controllers;

use App\Models\Taxpayer;
use App\DataTables\TaxpayersDataTable;
use App\DataTables\TaxpayerInvoicesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxpayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxpayersDataTable $dataTable)
    {
        return $dataTable->render('pages/taxpayers.list');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Taxpayer $taxpayer, TaxpayerInvoicesDataTable $dataTable)
    {
        // Assuming you want to pass the $taxpayer to the show view
        // return view('pages.taxpayers.show', compact('taxpayer'))
        //     ->with('dataTable', $dataTable->html());
        //return $dataTable->render('pages/taxpayers.show')-> with ('taxpayer', $taxpayer);
        
        return $dataTable->with('id', $taxpayer->id)
                ->render('pages/taxpayers.show', compact('taxpayer'));
    }   

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
}
