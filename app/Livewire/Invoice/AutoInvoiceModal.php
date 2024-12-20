<?php
namespace App\Livewire\Invoice;
use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\Zone;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
class AutoInvoiceModal extends Component
{
    use DispatchesMessages;
    public $invoice_id;
    public $zone;
    public $taxlabel;
    public $qty = 12;
    public $start_month;
    public $amount = 0;
    public $taxpayer_id;
    protected $rules = [
        // 'invoice_id' => 'required|string',
        // 'invoice_no' => 'required',
        // 'order_no' => 'required',
        // 'nic' => 'required',
        // 'status' => 'required|string',
        "s_amount" => "required|numeric",
        "taxpayer_taxable_id" => "required|int",
        "qty" => "required|numeric",
        "start_month" => "required|string",
        'taxpayer_id' => 'required|int',
        'amount' => 'required|numeric',
        //'cancel_reduct' => 'required',
        // 'telephone' => 'required|string|min:10|max:10',
        // 'longitude' => 'nullable',
        // 'latitude' => 'nullable',
        // 'canton' => 'required',
        // 'town' => 'required',
        // 'erea' => 'required',
        // 'address' => 'required|string',
        // 'zone_id' => 'required',
        // 'avatar' => 'nullable|sometimes|image|max:1024',
    ];
    protected $listeners = [
        // 'delete_user' => 'deleteUser',
        // 'update_invoice' => 'updateInvoice',
        'auto_invoice' => 'autoInvoice',
        // 'view_invoice' => 'viewInvoice',
        //'load_invoice' => 'loadInvoice',
    ];
    public function render()
    {
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();
        return view('livewire.invoice.auto-invoice-modal', ['zones' => $zones, 'tax_labels' => $tax_labels]);
    }
    public function mount()
    {
        if (!auth()->user()->hasPermissionTo('peut générer automatiquement les avis sur titre')) {
            abort(403, 'Accès interdit');
        }
    }
    public function submit()
    {
        if (!auth()->user()->hasPermissionTo('peut générer automatiquement les avis sur titre')) {
            abort(403, 'Accès interdit');
        }
        DB::transaction(function () {
            $invoices = Invoice::join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->join('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
                ->join('taxpayer_taxables', 'taxpayer_taxables.id', '=', 'invoice_items.taxpayer_taxable_id')
                ->join('taxables', 'taxables.id', '=', 'taxpayer_taxables.taxable_id')
                ->where('taxpayers.zone_id', 'LIKE', '%' . ($this->zone ?? '') . '%')
                ->where('taxables.tax_label_id', 'LIKE', '%' . ($this->taxlabel ?? '') . '%')
                ->where('invoices.validity', 'EXPIRED')
                ->select('invoices.*')
                ->get();
            foreach ($invoices as $invoice) {
                $invoiceData = [
                    'taxpayer_id' => $invoice->taxpayer_id,
                    // 'status' => 'PENDING',
                    'from_date' => date('Y-') . $this->start_month . "-01",
                    'to_date' => date('Y-') . $this->start_month + $this->qty . "-01",
                    'qty' => $this->qty,
                    'amount' => '0',
                ];
                $created_invoice = Invoice::create($invoiceData);
                foreach ($invoice->invoiceitems as $invoiceitem) {
                    $invoiceItemsData = [
                        'invoice_id' => $created_invoice->id,
                        'taxpayer_taxable_id' => $invoiceitem->taxpayer_taxable_id,
                        'qty' => $this->qty,
                        //'qty' => "6",
                        'ii_tariff' => $invoiceitem->taxpayer_taxable->taxable->tariff,
                        'ii_seize' => $invoiceitem->taxpayer_taxable->seize,
                        'amount' => $invoiceitem->taxpayer_taxable->taxable->tariff * $this->qty * $invoiceitem->taxpayer_taxable->seize,
                        $this->amount += $invoiceitem->taxpayer_taxable->taxable->tariff * $this->qty * $invoiceitem->taxpayer_taxable->seize,
                    ];
                    InvoiceItem::create($invoiceItemsData);
                    $taxpayerTaxable = TaxpayerTaxable::find($invoiceitem->taxpayer_taxable_id);
                    $taxpayerTaxable->invoice_id = $created_invoice->id;
                    $taxpayerTaxable->bill_status = 'BILLED';
                    $taxpayerTaxable->save();
                }
                $created_invoice->invoice_no = $created_invoice->id;
                $created_invoice->nic = $created_invoice->taxpayer_id . $created_invoice->id;
                $created_invoice->amount = $this->amount;
                $created_invoice->save();
                $invoice->validity = 'ARCHIVED';
                $invoice->save();
            }
            $this->dispatchMessage('Avis');
        });
        // Reset form fields after successful submission
        $this->reset();
    }
    public function deleteUser($id)
    {
        Invoice::destroy($id);
        $this->dispatchMessage('Avis', 'delete');
    }
    public function viewInvoice($id)
    {
        $this->updateInvoice($id);
        $this->view_mode = false;
        $this->button_mode = false;
    }
    public function updateInvoice($id)
    {
        $this->view_mode = true;
        $this->edit_mode = true;
        $this->button_mode = true;
        $invoice = Invoice::find($id);
        $this->taxpayer_id = $invoice->taxpayer->id;
        $this->invoice_id = $invoice->id;
        $this->qty = $invoice->qty;
        $this->name = $invoice->taxpayer->name;
        $this->tnif = $invoice->taxpayer->id;
        $this->zone = $invoice->taxpayer->zone->name;
        $this->taxpayer_taxables = $taxpayer_taxables = InvoiceItem::where('invoice_id', $id)->get();
        foreach ($taxpayer_taxables as $index => $invoice_item) {
            if ($invoice_item->taxpayer_taxable->taxable->periodicity == "Mois") {
                $period = 1;
            } elseif ($invoice_item->taxpayer_taxable->taxable->periodicity == "Ans") {
                $period = 0.083333;
            } else {
                $period = 1;
            }
            $this->periodicity = $invoice_item->taxpayer_taxable->taxable->periodicity;
            $this->taxable_taxlabel = $invoice_item->taxpayer_taxable->taxable->tax_label->code . ' : ' . $invoice_item->taxpayer_taxable->taxable->name;
            $this->taxpayer_taxable_id[$index] = $invoice_item->taxpayer_taxable->id;
            $this->taxpayer_taxable[$index] = $invoice_item->taxpayer_taxable->name;
            $this->s_seize[$index] = $invoice_item->ii_seize;
            $this->s_seize_e[$index] = $invoice_item->taxpayer_taxable->seize;
            $this->s_tariff[$index] = $invoice_item->ii_tariff;
            $this->s_tariff_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff;
            if ($invoice_item->taxpayer_taxable->taxable->tariff_type == "FIXED") {
                $this->s_amount[$index] = $invoice_item->amount;
                $this->s_amount_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff * $invoice_item->taxpayer_taxable->seize * $this->qty * $period;
            } else {
                $this->s_amount[$index] = $invoice_item->amount / 100;
                $this->s_amount_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff * $invoice_item->taxpayer_taxable->seize * $this->qty * $period / 100;
            }
        }
        $this->amount_ph = array_sum($this->s_amount) . " FCFA";
        $this->amount_ph_e = array_sum($this->s_amount_e) . " FCFA";
        $this->amount = array_sum($this->s_amount);
        $this->amount_e = array_sum($this->s_amount_e);
        $this->amount_red_e = $this->amount - $this->amount_e;
    }
    public function addInvoice($id)
    {
        $this->edit_mode = false;
        $this->view_mode = false;
        $this->button_mode = true;
        $this->invoice_id = '';
        $this->qty = '';
        $this->taxpayer_taxables = $taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get();
        foreach ($taxpayer_taxables as $index => $taxable) {
            $this->taxpayer_taxable_id[$index] = $taxable->id;
            $this->taxpayer_taxable[$index] = $taxable->name;
            $this->s_seize[$index] = $taxable->seize;
            $this->s_tariff[$index] = $taxable->taxable->tariff;
            $this->s_amount[$index] = '';
        }
        $this->amount_ph = " FCFA";
        $this->amount = '';
        $taxpayer = Taxpayer::find($id);
        $this->taxpayer_id = $taxpayer->id;
        $this->name = $taxpayer->name;
        $this->tnif = $taxpayer->id;
        $this->zone = $taxpayer->zone->name;
    }
    public function autoInvoice($value)
    {
        $this->qty = $value;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
