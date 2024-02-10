<?php

namespace App\Http\Controllers;

use App\DataTables\TaxablesDataTable;
use App\Models\Taxable;
use Illuminate\Http\Request;

class TaxableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxablesDataTable $dataTable)
    {
        //return view('pages/taxables.list');
        return $dataTable->render('pages/taxables.list');
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
    public function show(Taxable $taxable)
    {
        return view('pages/taxables.show', compact('taxable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taxable $taxable)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Taxable $taxable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taxable $taxable)
    {
        //
    }
}
