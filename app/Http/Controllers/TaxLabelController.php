<?php

namespace App\Http\Controllers;

use App\DataTables\TaxLabelsDataTable;
use App\Models\TaxLabel;
use Illuminate\Http\Request;

class TaxLabelController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxLabelsDataTable $dataTable)
    {
        return $dataTable->render('pages/tax_labels.list');
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
    public function show(TaxLabel $taxlabel)
    {
        return view('pages/tax_labels.show', compact('tax_label'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxLabel $taxlabel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxLabel $taxlabel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxLabel $taxlabel)
    {
        //
    }
}
