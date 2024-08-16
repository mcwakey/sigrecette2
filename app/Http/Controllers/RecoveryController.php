<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\DataTables\RecoveriesDataTable;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\TaxLabel;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RecoveryController extends Controller
{
    public function index(Request $request,RecoveriesDataTable $dataTable,InvoicesDataTable $invoicesDataTable)
    {
        $year = Year::getActiveYear()->name;
        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d H:i:s',
            'e_date' => 'nullable|date_format:Y-m-d H:i:s',
            'delivery' => ['nullable', 'string', Rule::in(Constants::INVOICE_DELIVERY_STATE_VALIDATION_MAP)],
            'to_paid' =>['nullable', 'integer', Rule::in([0,1])],
            'state' => ['nullable', 'string', Rule::in(array_keys( Constants::PAYMENT_STATE_VALIDATION_MAP))],
        ]);
        $state = isset($validatedData['state']) ? Constants::PAYMENT_STATE_VALIDATION_MAP[$validatedData['state']] : null;
        $delivery = isset($validatedData['delivery']) ? $validatedData['delivery']: null;
        $to_paid = isset($validatedData['to_paid']) ? $validatedData['to_paid']: null;
        $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
        $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();
        if($delivery==null){
            return $dataTable->with(
                [
                'state'=>$state,
                'startDate' => $startDate,
                'endDate' => $endDate,
                ]
            )->render('pages/recoveries.list', compact('zones', 'tax_labels'));

        }else{
            $year = Year::getActiveYear()->name;
            $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            $role = Role::where('name', 'agent_recouvrement')->first();
            $agent_recouvrements = $role->users()->get();

            return $invoicesDataTable->with(
                [
                    'delivery' => $delivery,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'startInvoiceId'=>null ,
                    'endInvoiceId'=>null,
                    'type'=>null,
                    'to_paid'=>$to_paid,
                ]
            )->render('pages/invoices.list', compact('zones', 'tax_labels','agent_recouvrements'));
        }
    }
}
