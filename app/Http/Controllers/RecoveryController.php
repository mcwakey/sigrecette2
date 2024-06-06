<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\DataTables\RecoveriesDataTable;
use App\Models\Invoice;
use App\Models\TaxLabel;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RecoveryController extends Controller
{
    public function index(Request $request,RecoveriesDataTable $dataTable,InvoicesDataTable $invoicesDataTable)
    {

        $validatedData = $request->validate([
            'notDelivery'=>'nullable|integer'
        ]);
        $notDelivery = $validatedData['notDelivery'] ?? null;
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();
        if($notDelivery==null){
            return $dataTable->render('pages/recoveries.list', compact('zones', 'tax_labels'));

        }else{
            $year = Year::getActiveYear()->name;
            $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            $role = Role::where('name', 'agent_recouvrement')->first();
            $agent_recouvrements = $role->users()->get();
            return $invoicesDataTable->with(
                [
                    'notDelivery' => $notDelivery,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'startInvoiceId'=>null ,
                    'endInvoiceId'=>null,
                    'type'=>null,
                    'to_paid'=>true,
                ]
            )->render('pages/invoices.list', compact('zones', 'tax_labels','agent_recouvrements'));
        }
    }
}
