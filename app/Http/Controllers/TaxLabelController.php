<?php

namespace App\Http\Controllers;

use App\Models\TaxLabel;
use Illuminate\Http\Request;

class TaxLabelsController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxLabelsDataTable $dataTable)
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
    public function show(TaxLabel $taxlabel)
    {
        return view('pages/taxlabels.show', compact('taxlabel'));
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
