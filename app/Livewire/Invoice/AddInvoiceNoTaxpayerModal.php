<?php
namespace App\Livewire\Invoice;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Helpers\Constants;
use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\Year;
use App\Traits\DispatchesMessages;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
class AddInvoiceNoTaxpayerModal extends Component
{
    //TODO Add backend validation on tarif
    use DispatchesMessages;
    public $invoice_id;
    public $name;
    public $tnif;
    public $zone;
    public $notes;
    public $invoice_no;
    public $periodicity;
    public $qty = 1;
    public $start_month;
    public $s_amount = [];
    public $s_amount_e = [];
    public $s_tariff_e = [];
    public $s_seize_e = [];
    public $taxpayer_taxable_id = [];
    public $taxpayer_taxables = [];
    public $taxpayer_id;
    public $amount_ph_e;
    public $amount_e;
    public $length;
    public $width;
    public $taxable_taxlabel;
    public $taxable_id;
    public $taxlabel_id;
    public $taxables = [];
    public $taxlabel_name;
    public $taxpayer_taxable;
    public $unit;
    public $seize;
    public $tariff_type;
    public $tariff;
    public $s_tariff;
    public $s_seize;
    public $amount_ph;
    public $amount;
    public $payment_type;
    public $reference;
    public $fullname;
    public $gender;
    public $id_type;
    public $id_number;
    public $telephone;
    public $mobilephone;
    public $email;
    public $cancel_reduct;
    public $edit_mode = false;
    public $view_mode = false;
    public $button_mode = false;
    protected $rules = [
        "name" => "required|string",
        "s_amount" => 'required|numeric',
        "taxpayer_taxable_id" => "required|int",
        "taxlabel_id" => "required|int",
        "taxable_id" => "required|int",
        "qty" => "required|numeric",
        "fullname" => "required|string",
        "gender" => "required|string",
        "tariff" => "required",
        "seize" => "required|numeric",
        // 'taxpayer_id' => 'required',
        'amount' => 'required|numeric',
        'id_type' => "required",
    ];
    protected $listeners = [
        'delete_user' => 'deleteUser',
        // 'update_invoice' => 'updateInvoice',
        'add_no_invoice' => 'addInvoice',
        'change_tarrif' => 'changeTarrif',
        'load_invoice_comptant' => 'loadInvoice',
        'add_taxable' => 'addTaxable',
        'load_drop' => 'loadDrop',
    ];
    private $tarisIsNull;
    public $option_calculus;
    public function mount()
    {

    }
    public function render()
    {
        $taxpayers = Taxpayer::all();
        $taxlabels = TaxLabel::where('category', 'LIKE', '%CATEGORY 2%')->get();
        $genders = Gender::all();
        $id_types = IdType::all();
        $year = Year::getActiveYear();
        $months = [];
        $currentMonth = Carbon::now()->month;
        $monthName = Carbon::createFromFormat('m', $currentMonth)->monthName;
        $monthNumber = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
        $months[$monthNumber] = $monthName;
        $this->start_month = $monthNumber;
        return view('livewire.invoice.add-invoice-no-taxpayer-modal', ['taxpayers' => $taxpayers, 'taxlabels' => $taxlabels, 'genders' => $genders, 'id_types' => $id_types, 'months' => $months, 'year' => $year]);
    }
    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::where('tax_label_id',"=", $value)
            ->where('status', '=', 'ACTIVE')->get(); // Load taxables based on tax label ID
        TaxLabel::find($value); // Load taxables based on tax label ID
        $this->taxable_id = null;
        $this->tariff = null;
    }
    public function updatedTaxableId($value)
    {
        $taxables = Taxable::find($value);
        $this->option_calculus = $taxables->unit_type;
        $this->tariff = $taxables->tariff;
        $this->s_tariff = $taxables->tariff;
        $this->unit = $taxables->unit;
        if ($taxables->tariff_type != 'FIXED') {
            $this->tariff_type = '%';
        }
        $this->tarisIsNull = $taxables->tariff == 0;
        $this->taxpayer_taxable_id = $taxables->id;
        $this->taxlabel_name = $taxables->name;
        $this->loadInvoice($this->qty);
    }
    public function updatedLength($value)
    {
        $this->seize = intval($this->length) * intval($this->width);
    }
    public function updatedWidth($value)
    {
        $this->seize = intval($this->length) * intval($this->width);
    }
    public function submit()
    {
        if (!auth()->user()->hasPermissionTo('peut émettre un avis au comptant')) {
            abort(403, 'Accès interdit');
        }
        $this->validate();
        DB::transaction(function () {
            $taxpayersData = [
                'name' => $this->fullname,
                'gender' => $this->gender,
                'id_type' => $this->id_type,
                'id_number' => $this->id_number,
                'mobilephone' => $this->mobilephone == null ? 0000 : $this->mobilephone,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'type' => Constants::INVOICE_TYPE_COMPTANT,
                'password' => "",
            ];
            $taxpayer = Taxpayer::create($taxpayersData);
            $taxpayer->save();
            $invoiceData = [
                'taxpayer_id' => $taxpayer->id,
                'amount' => $this->amount,
                'qty' => $this->qty,
                'from_date' => date('Y-') . $this->start_month . "-01",
                'to_date' => date('Y-') . $this->start_month + $this->qty . "-01",
                'status' => InvoiceStatusEnums::PENDING,
                'pay_status' => 'OWING',
                'type' => Constants::INVOICE_TYPE_COMPTANT,
                'notes' => $this->notes
            ];
            $invoice = Invoice::create($invoiceData);
            $invoice->processOnInvoicesByUser('regisseur');
            $invoice->invoice_no = $invoice->id;
            $invoice->nic = '00000' . $invoice->id;
            $invoice->save();
            $taxpayerTaxableData = [
                'name' => $this->name,
                'seize' => $this->seize,
                'taxable_id' => $this->taxpayer_taxable_id,
                'invoice_id' => $invoice->id,
                'bill_status' => 'BILLED',
                'taxpayer_id' => $taxpayer->id
            ];
            $taxpayerTaxables = TaxpayerTaxable::create($taxpayerTaxableData);
            $invoiceItemsData = [
                'invoice_id' => $invoice->id,
                'taxpayer_taxable_id' => $taxpayerTaxables->id,
                'qty' => $this->qty,
                'amount' => $this->s_amount,
                'ii_tariff' => $this->s_tariff,
                'ii_seize' => $this->s_seize,
            ];
            InvoiceItem::create($invoiceItemsData);
           // $paymentData = ['invoice_id' => $invoice->id, 'amount' => $this->amount, 'payment_type' => $this->payment_type, 'reference' => $this->reference,];
            $this->dispatchMessage('Avis au comptant');
        });
        // Reset form fields after successful submission
        $this->reset();
    }
    public function deleteUser($id)
    {
        Invoice::destroy($id);
        // Emit a success event with a message
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
        $this->taxpayer_id = $invoice->taxpayer->id ?? '';
        $this->invoice_id = $invoice->id;
        $this->qty = $invoice->qty;
        $this->name = $invoice->taxpayer->name ?? '';
        $this->tnif = $invoice->taxpayer->id ?? '';
        $this->zone = $invoice->taxpayer->zone->name ?? '';
        $this->taxpayer_taxables = $taxpayer_taxables = InvoiceItem::where('invoice_id',"=", $id)->get();
        foreach ($taxpayer_taxables as $index => $invoice_item) {

            //if ($invoice_item->taxpayer_taxable->taxable->periodicity == "Mois") {$period = 1;} elseif ($invoice_item->taxpayer_taxable->taxable->periodicity == "Ans") {$period = 0.083333;} else {$period = 1;}
            $period = 1;
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
        $this->zone = " ";
    }
    public function addTaxable($id)
    {
        $this->taxpayer_taxable = $this->name;
        $this->s_seize = $this->seize;
    }
    public function changeTarrif($value)
    {
        $taxable = $this->taxpayer_taxable_id != null ? Taxable::find($this->taxpayer_taxable_id) : null;
        if ($taxable) {
            $this->tarisIsNull = $taxable->tariff == 0;
        }
        if ($this->tarisIsNull) {
            $this->s_tariff = $this->tariff;
        } else {
            $this->tariff = $this->s_tariff;
        }
        if ($this->taxpayer_taxable_id != null) {
            $taxable = Taxable::find($this->taxpayer_taxable_id);
            if ($taxable->periodicity == "Mois") {
                $period = 1;
            } elseif ($taxable->periodicity == "Ans") {
                $period = 0.083333;
            } else {
                $period = 1;
            }
            $this->periodicity = $taxable->periodicity;
            $this->taxpayer_taxable = $this->name;
            $this->s_seize = $this->seize;
            if ($taxable->tariff_type == "FIXED") {
                $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty * $period;
            } else {
                $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty * $period / 100;
            }
            $this->amount_ph = $this->s_amount . " FCFA";
            $this->amount = $this->s_amount;
        }
    }
    public function loadInvoice($value)
    {
        $this->qty = $value;
        $taxable = Taxable::find($this->taxpayer_taxable_id);
        if ($taxable->periodicity == "Mois") {
            $period = 1;
        } elseif ($taxable->periodicity == "Ans") {
            $period = 0.083333;
        } else {
            $period = 1;
        }
        $this->periodicity = $taxable->periodicity;
        $this->taxpayer_taxable = $this->name;
        $this->s_seize = $this->seize;
        if ($taxable->tariff_type == "FIXED") {
            $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty * $period;
        } else {
            $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty * $period / 100;
        }
        $this->amount_ph = $this->s_amount . " FCFA";
        $this->amount = $this->s_amount;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
