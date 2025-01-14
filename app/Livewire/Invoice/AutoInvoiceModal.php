<?php
namespace App\Livewire\Invoice;
use App\Helpers\Constants;
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
use App\Models\Year;
use App\Models\Zone;
use App\Traits\DispatchesMessages;
use Carbon\Carbon;
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
    public $start_month=01;
    public $amount = 0;
    public $taxpayer_id;
    protected $rules = [
        "taxpayer_taxable_id" => "required|int",
        "start_month" => "required|string",
    ];
    protected $listeners = [
        'auto_invoice' => 'autoInvoice',
    ];
    public function render()
    {
        $zones = Zone::all();
        $tax_labels = TaxLabel::where('category', 'LIKE', '%CATEGORY 1%')->get();
        $year = Year::getActiveYear();
        $months = Constants::getMonths();
        return view('livewire.invoice.auto-invoice-modal', ['zones' => $zones, 'tax_labels' => $tax_labels,'months' => $months, 'year' => $year]);
    }
    public function mount()
    {

    }
    public function submit()
    {
        if (!auth()->user()->hasPermissionTo('peut générer automatiquement les avis sur titre')) {
            abort(403, 'Accès interdit');
        }
        DB::transaction(function () {
            $year=Year::getBeforeCurrentYear()?->name;
            if($year){
                $s_date = Carbon::parse("{$year}-01-01 00:00:00");
                $e_date = Carbon::parse("{$year}-12-31 23:59:59");
                $invoices = Invoice::join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                    ->join('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
                    ->join('taxpayer_taxables', 'taxpayer_taxables.id', '=', 'invoice_items.taxpayer_taxable_id')
                    ->join('taxables', 'taxables.id', '=', 'taxpayer_taxables.taxable_id')
                    ->where('taxpayers.zone_id', 'LIKE', '%' . ($this->zone ?? '') . '%')
                    ->where('taxables.tax_label_id', 'LIKE', '%' . ($this->taxlabel ?? '') . '%')
                    ->where('invoices.validity', "=",'EXPIRED')
                    ->where('invoices.type', '=', Constants::TITRE)
                    ->whereBetween('invoices.created_at', [$s_date, $e_date])
                    ->select('invoices.*')
                    ->get();
            }
            else{
                $invoices=[];
            }


            //dd($request->all());
           //dd($invoices);
            $from_date = Carbon::createFromDate(date('Y'), $this->start_month, 1);
            $to_date = $from_date->copy()->addMonths($this->qty - 1)->endOfMonth();
            foreach ($invoices as $invoice) {
                $invoiceData = [
                    'taxpayer_id' => $invoice->taxpayer_id,
                    'from_date' => $from_date->toDateString(),
                    'to_date' => $to_date->toDateString(),
                    'qty' => $this->qty,
                    'amount' => '0',
                    'notes'=>Invoice::saveNotes($invoice->id,$invoice->get_remains_to_be_paid(),''),
                ];
                $created_invoice = Invoice::create($invoiceData);
                $totalAmount = 0;
                foreach ($invoice->invoiceitems as $invoiceitem) {
                    $period = 1;
                    $periodicity = $invoiceitem->taxpayer_taxable->taxable->periodicity;
                    $qty = $periodicity == "Mois" ? 12 : 1;
                    $created_invoice->qty=$qty;
                    $temp_seize = $invoiceitem->taxpayer_taxable->seize;
                    if ($invoiceitem->taxpayer_taxable->taxable->use_second_formula) {
                        $temp_seize = 1;
                    }
                    if($invoiceitem->taxpayer_taxable->taxable->tariff_type == "FIXED"){
                        $itemAmount = $invoiceitem->taxpayer_taxable->taxable->tariff * $qty * $temp_seize* $period;

                    }else{
                        $itemAmount = $invoiceitem->taxpayer_taxable->taxable->tariff * $qty * $temp_seize* $period / 100;

                    }

                    $invoiceItemsData = [
                        'invoice_id' => $created_invoice->id,
                        'taxpayer_taxable_id' => $invoiceitem->taxpayer_taxable_id,
                        'qty' => $this->qty,
                        'ii_tariff' => $invoiceitem->taxpayer_taxable->taxable->tariff,
                        'ii_seize' => $invoiceitem->taxpayer_taxable->seize,
                        'amount' => $itemAmount,
                    ];
                    InvoiceItem::create($invoiceItemsData);
                    $taxpayerTaxable = TaxpayerTaxable::find($invoiceitem->taxpayer_taxable_id);
                    $taxpayerTaxable->invoice_id = $created_invoice->id;
                    $taxpayerTaxable->bill_status = 'BILLED';
                    $taxpayerTaxable->save();
                    $totalAmount += $itemAmount;

                }
                $created_invoice->invoice_no = $created_invoice->id;
                $created_invoice->nic = $created_invoice->taxpayer_id . $created_invoice->id;
                $created_invoice->amount = $totalAmount;;
                $created_invoice->save();

                $invoice->validity = 'ARCHIVED';
                $invoice->save();
            }
            $this->dispatchMessage('Avis');
        });
        // Reset form fields after successful submission
        $this->reset();
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
