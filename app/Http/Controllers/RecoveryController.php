<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\DataTables\RecoveriesDataTable;
use App\Helpers\Constants;
use App\Models\TaxLabel;
use App\Models\Year;
use App\Models\Zone;
use App\Traits\HandlesDateFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RecoveryController extends Controller
{
    use HandlesDateFilters;

    public function index(Request $request, RecoveriesDataTable $dataTable, InvoicesDataTable $invoicesDataTable)
    {
        $this->handleDateFilters($request);
        $validatedData = $request->validate([
            'delivery' => ['nullable', 'string', Rule::in(Constants::INVOICE_DELIVERY_STATE_VALIDATION_MAP)],
            'to_paid' => ['nullable', 'integer', Rule::in([0, 1])],
            'state' => ['nullable', 'string', Rule::in(array_keys(Constants::PAYMENT_STATE_VALIDATION_MAP))],
        ]);
        $state = isset($validatedData['state']) ? Constants::PAYMENT_STATE_VALIDATION_MAP[$validatedData['state']] : null;
        $delivery = isset($validatedData['delivery']) ? $validatedData['delivery'] : null;
        $to_paid = isset($validatedData['to_paid']) ? $validatedData['to_paid'] : null;
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();
        if ($delivery == null) {
            return $dataTable->with(
                [
                    'state' => $state,
                    'startDate' => $this->s_date,
                    'endDate' => $this->e_date,
                ]
            )->render('pages/recoveries.list', ['zones' => $zones, 'tax_labels' => $tax_labels]);
        } else {
            $role = Role::where('name', "=", 'agent_recouvrement')->first();
            $agent_recouvrements = $role->users()->get();
            return $invoicesDataTable->with(
                [
                    'delivery' => $delivery,
                    'startDate' => $this->s_date,
                    'endDate' => $this->e_date,
                    'startInvoiceId' => null,
                    'endInvoiceId' => null,
                    'type' => null,
                    'to_paid' => $to_paid,
                ]
            )->render('pages/invoices.list', ['zones' => $zones, 'tax_labels' => $tax_labels, 'agent_recouvrements' => $agent_recouvrements]);
        }
    }
}
