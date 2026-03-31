<?php

namespace App\Http\Controllers;

use App\DataTables\InvoicesDataTable;
use App\Enums\InvoiceStatusEnums;
use App\Enums\TaxpayerStateEnums;
use App\Helpers\Constants;
use App\Models\Commune;
use App\Models\Invoice;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\Year;
use App\Models\Zone;
use App\Services\QrcodeGeneratorService;
use App\Traits\HandlesDateFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class InvoiceController extends Controller
{
    use HandlesDateFilters;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InvoicesDataTable $dataTable)
    {
        $this->authorize('viewAny', Invoice::class);
        $this->handleDateFilters($request);
        $validatedData = $request->validate([
            'delivery' => ['nullable', 'string', Rule::in(Constants::INVOICE_DELIVERY_STATE_VALIDATION_MAP)],
            'startInvoiceId' => 'nullable|integer',
            'endInvoiceId' => 'nullable|integer',
            'type' => ['nullable', 'string', Rule::in(array_keys(Constants::INVOICE_TYPE_VALIDATION_MAP))],
            'state' => ['nullable', 'string', Rule::in(array_keys(Constants::INVOICE_STATE_VALIDATION_MAP))],
            'to_paid' => ['nullable', 'integer', Rule::in([0, 1])],
            'invoice_id' => ['nullable', 'integer'],
        ]);
        $state = isset($validatedData['state']) ? Constants::INVOICE_STATE_VALIDATION_MAP[$validatedData['state']] : null;
        $to_paid = isset($validatedData['to_paid']) ? $validatedData['to_paid'] : null;
        $type = isset($validatedData['type']) ? Constants::INVOICE_TYPE_VALIDATION_MAP[$validatedData['type']] : null;
        $delivery = isset($validatedData['delivery']) ? $validatedData['delivery'] : null;
        $startInvoiceId = $validatedData['startInvoiceId'] ?? null;
        $endInvoiceId = $validatedData['endInvoiceId'] ?? null;
        $zones = cache()->remember('zones.all', 3600, fn() => Zone::all());
        $tax_labels = cache()->remember('tax_labels.all', 3600, fn() => TaxLabel::all());
        $role = cache()->remember('role.agent_recouvrement', 3600, fn() => Role::where('name', '=', 'agent_recouvrement')->first());
        $agent_recouvrements = $role?->users()->get() ?? collect();
        $invoice_id = isset($validatedData['invoice_id']) ? $validatedData['invoice_id'] : null;


        if ($invoice_id) {
            $invoice = Invoice::find($invoice_id);
            if ($invoice) {
                if ($invoice->status == InvoiceStatusEnums::PENDING->value) {
                    return redirect()->route('invoices.index', ['state' => Constants::INVOICE_STATE_DRAFT_KEY, 'type' => Constants::INVOICE_TYPE_TITRE_KEY]);
                } elseif ($invoice->status == InvoiceStatusEnums::ACCEPTED->value) {
                    return redirect()->route('invoices.index', ['state' => Constants::INVOICE_STATE_ACCEPTED_KEY, 'type' => Constants::INVOICE_TYPE_TITRE_KEY]);
                } elseif ($invoice->status == InvoiceStatusEnums::PENDING->value) {
                    return redirect()->route('invoices.index', ['state' => Constants::INVOICE_STATE_PENDING_KEY, 'type' => Constants::INVOICE_TYPE_TITRE_KEY]);
                }
            }
        }
        return $dataTable->with(
            [
                'delivery' => $delivery,
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
                'startInvoiceId' => $startInvoiceId,
                'endInvoiceId' => $endInvoiceId,
                'type' => $type,
                'state' => $state,
                'to_paid' => $to_paid,
            ]
        )->render('pages/invoices.list', ['zones' => $zones, 'tax_labels' => $tax_labels, 'agent_recouvrements' => $agent_recouvrements]);
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
    public function show(Invoice $invoice, QrcodeGeneratorService $qrcodeGeneratorService)
    {
        $this->authorize('view', $invoice);
        return view('exports/invoices', [
            'data' => $invoice,
            'action' => 1, "commune" => Commune::first(),
            'qrcodeSvg' => $qrcodeGeneratorService->generate(route('invoices.show', [ $invoice]))
        ]);
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
