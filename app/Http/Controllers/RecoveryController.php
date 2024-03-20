<?php

namespace App\Http\Controllers;

use App\DataTables\RecoveriesDataTable;
use App\Models\Invoice;
use App\Models\TaxLabel;
use App\Models\Zone;
use Illuminate\Http\Request;

class RecoveryController extends Controller
{
    public function index(RecoveriesDataTable $dataTable)
    {
        //return view('pages/invoices.show', compact('invoice'));
        //return $dataTable->render('pages/invoices.show');
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();
        return $dataTable->render('pages/recoveries.list', compact('zones', 'tax_labels'));
    }
}
