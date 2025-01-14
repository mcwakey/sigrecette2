<?php
namespace App\Http\Controllers;
use App\DataTables\CommuneDataTable;
use App\DataTables\TaxpayerInvoicesDataTable;
use App\DataTables\TaxpayerTaxablesDataTable;
use App\Models\Commune;
use Illuminate\Http\Request;
class CommunesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CommuneDataTable $dataTable)
    {
        return $dataTable->render('pages/communes.list',['commune'=>Commune::first()]);
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
    public function show(Commune $commune, TaxpayerInvoicesDataTable $invoicesDataTable, TaxpayerTaxablesDataTable $taxablesDataTable)
    {
        return $taxablesDataTable->with('id', $commune->id)
            ->render('pages/communes.show', ['commune' => $commune]);
    }
    /**
     * Display the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commune $commune)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commune $commune)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commune $commune)
    {
        //
    }
}
