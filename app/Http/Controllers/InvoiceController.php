<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\Models\Taxpayer;
use App\Models\Invoice;
use App\Models\TaxLabel;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,InvoicesDataTable $dataTable)
    {


        $year = Year::getActiveYear()->name;
        $validatedData = $request->validate([
            'notDelivery'=>'nullable|integer',
            'startInvoiceId' => 'nullable|integer',
            'endInvoiceId' => 'nullable|integer',
            's_date'=> 'nullable',
            'e_date'=> 'nullable',
            'aucomptant'=>'nullable|integer'
        ]);
        $aucomptant =$validatedData['aucomptant']??null;
        $notDelivery = $validatedData['notDelivery'] ?? null;
        $startInvoiceId = $validatedData['startInvoiceId'] ?? null;
        $endInvoiceId = $validatedData['endInvoiceId'] ?? null;
        $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
        $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();
        $role = Role::where('name', 'agent_recouvrement')->first();
        $agent_recouvrements = $role->users()->get();
        return $dataTable->with(
            [
                'notDelivery' => $notDelivery,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'startInvoiceId'=>$startInvoiceId ,
                'endInvoiceId'=>$endInvoiceId,
                'aucomptant'=>$aucomptant
            ]
        )->render('pages/invoices.list', compact('zones', 'tax_labels','agent_recouvrements'));
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
