<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\Models\Taxpayer;
use App\Models\Invoice;
use App\Models\TaxLabel;
use App\Models\Zone;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InvoicesDataTable $dataTable)
    {
        //return view('pages/invoices.list');

        $zones = Zone::all();
        $tax_labels = TaxLabel::all();

        return $dataTable->render('pages/invoices.list', compact('zones', 'tax_labels'));
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
    public function show(Invoice $invoice, InvoicesDataTable $dataTable)
    {
        //return view('pages/invoices.show', compact('invoice'));
        //return $dataTable->render('pages/invoices.show');
        return $dataTable->render('pages/invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
