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
    // public $seize;
    // public $tariff;
    public $qty=1;
    public $start_month;

    public $s_amount = [];

    public $s_amount_e = [];
    public $s_tariff_e = [];
    public $s_seize_e = [];

    public $taxpayer_taxable_id = [];
    public $taxpayer_taxables = [];

    // public $qty=[];
    // public $s_amount=[];
    // public $taxpayer_taxable_id=[];
    //public $invoice_id;
    //public $status;

    public $taxpayer_id;

    public $amount_ph_e;
    public $amount_e;
    public $length;
    public $width;



    public $taxable_taxlabel;

    public $taxable_id;
    public $taxlabel_id;
    public $taxables=[];

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
        "name"=> "required|string",
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
        'id_type'=>"required",
        // 'notes' => 'required',

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
        'delete_user' => 'deleteUser',
        // 'update_invoice' => 'updateInvoice',
        'add_no_invoice' => 'addInvoice',

        'change_tarrif' => 'changeTarrif',
        'load_invoice_comptant' => 'loadInvoice',

        'add_taxable' => 'addTaxable',
        'load_drop' => 'loadDrop',
    ];

    // public $taxpayer_id; // Define public property to hold taxpayer_id

    // // Constructor to accept taxpayer_id
    // public function mount($taxpayer_id)
    // {
    //     $this->taxpayer_id = $taxpayer_id;
    // }
    private $tarisIsNull=null;
    public $option_calculus;


    public function render()
    {

        $taxpayers = Taxpayer::all();
        $taxlabels = TaxLabel::where('category', 'LIKE', '%CATEGORY 2%')->get();
        $genders = Gender::all();
        $id_types = IdType::all();
        $year= Year::getActiveYear();
        $months = [];
        $currentMonth = Carbon::now()->month;
        $monthName = Carbon::createFromFormat('m',$currentMonth)->monthName;
        $monthNumber = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
        $months[$monthNumber] = $monthName;
        $this->start_month = $monthNumber;


        return view('livewire.invoice.add-invoice-no-taxpayer-modal', compact('taxpayers','taxlabels','genders','id_types','months','year'));
    }

    // public function loadDrop($value)
    // {
    //     //dd($value);

    // }

    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::where('tax_label_id', $value)->get(); // Load taxables based on tax label ID
        //$this->reset('taxables');

        $taxlabels = TaxLabel::find( $value); // Load taxables based on tax label ID
        $this->taxable_id = null;
        $this->tariff = null;


        //$this->taxlabel_name = $taxlabels->name;

        //dd($this->taxables);
        // $this->loadTaxables($value); // Call the loadTaxables method when tax label ID is updated
    }

    public function updatedTaxableId($value)
    {
        // Debugging to ensure $value is valid
        //dd($value." TaxableId");

        // Assuming $value is valid, fetch taxables based on tax label ID
        $taxables = Taxable::find($value);

        $this->option_calculus = $taxables->unit_type;
        //if ($taxables)
            $this->tariff = $taxables->tariff;
            $this->s_tariff = $taxables->tariff;
            $this->unit = $taxables->unit;
            if ($taxables->tariff_type != 'FIXED'){
                $this->tariff_type = '%';
            }
            if( $taxables->tariff==0){
                $this->tarisIsNull=true;
            }else{
                $this->tarisIsNull=false;
            }
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



        // Validate the form input data
       $this->validate();
        DB::transaction(function () {
            $taxpayersData = [
                'name' => $this->fullname,
                'gender' => $this->gender,
                'id_type' => $this->id_type,
                'id_number' => $this->id_number,
                'mobilephone' => $this->mobilephone==null?0000:$this->mobilephone,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'type'=>Constants::INVOICE_TYPE_COMPTANT,
                'password'=>"",
            ];
            $taxpayer = Taxpayer::create($taxpayersData);
            $taxpayer->save();

            // dd($this->notes);

            $invoiceData = [
                'taxpayer_id' => $taxpayer->id,
                'amount' => $this->amount,
                'qty' => $this->qty,
                'from_date' => date('Y-').$this->start_month."-01",
                'to_date' => date('Y-').$this->start_month + $this->qty."-01",
                'status' => InvoiceStatusEnums::PENDING,
                'pay_status' => 'OWING',
                'type'=> Constants::INVOICE_TYPE_COMPTANT,
                'notes' => $this->notes
            ];


            // if ($this->edit_mode) {
            //     $invoiceData['amount'] = $this->amount_e;
            //     $invoiceData['status'] = 'PENDING';
            // }

            //dd($invoiceData);


            // Create or update Invoice record
            $invoice = Invoice::create($invoiceData);

            $invoice->processOnInvoicesByUser('regisseur');

            $invoice->invoice_no = $invoice->id;
            $invoice->nic = '00000'.$invoice->id;
            //$invoice->order_no = $this->order_no;
            $invoice->save();


            //$a = $this->invoice_id ?? $invoice->id;
            //$a = ($this->invoice_id ?? $invoice->id);

            //dd($a);

            $taxpayerTaxableData = [
                'name' => $this->name,
                'seize' => $this->seize,
                'taxable_id' => $this->taxpayer_taxable_id,
                'invoice_id' => $invoice->id,
                'bill_status' => 'BILLED',
                'taxpayer_id'=>$taxpayer->id

            ];

            $taxpayerTaxables = TaxpayerTaxable::create($taxpayerTaxableData);
            //$taxpayerTaxables = TaxpayerTaxable::whereIn('id', $this->taxpayer_taxable_id)->get();

            // foreach ($taxpayerTaxables as $taxpayerTaxable) {
            //     $taxpayerTaxable->update($taxpayerTaxableData);
            // }

            // Prepare data for Invoice_items
            // $invoiceItemsData = [
            //     'invoice_id' => $invoice->id,
            //     'taxpayer_taxable_id' => $this->taxpayer_taxable_id,
            //     'qty' => $this->qty,
            //     'amount' => $this->s_amount,
            // ];

            //dd($invoiceItemsData);

            // foreach ($this->taxpayer_taxable_id as $index => $taxpayer_taxable_id) {
                $invoiceItemsData = [
                    'invoice_id' => $invoice->id,
                    'taxpayer_taxable_id' => $taxpayerTaxables->id,
                    'qty' => $this->qty,
                    'amount' => $this->s_amount,
                    'ii_tariff' => $this->s_tariff,
                    'ii_seize' => $this->s_seize,
                ];

                // if ($this->edit_mode) {
                //     $invoiceItemsData['amount'] = $this->s_amount_e[$index];
                //     $invoiceItemsData['ii_tariff'] = $this->s_tariff_e[$index];
                //     $invoiceItemsData['ii_seize'] = $this->s_seize_e[$index];
                // }

                //dd($this->s_amount_e);

                InvoiceItem::create($invoiceItemsData);
            // }


            $paymentData = [
                'invoice_id' => $invoice->id,
                // 'taxpayer_id' => $this->taxpayer_id,
                'amount' => $this->amount,
                'payment_type' => $this->payment_type,
                'reference' => $this->reference,
            ];

            //dd($paymentData);

            // Create or update Payment record
            //Payment::create($paymentData);





            // $invoice_old = Invoice::find($this->invoice_id ?? $invoice->id);

            // if ($this->edit_mode) {

            //     // Save the invoice ID into the invoice_no column
            //     $invoice_old->status = "CANCELED";
            //     $invoice_old->validity = "CANCELED";
            //     $invoice_old->save();
            // }

            // $invoice->order_no = $invoice_old->order_no;
            // $invoice->pay_status = $invoice_old->pay_status;
            // $invoice->save();

            // Dispatch success message
            // if ($this->edit_mode) {
            //     $this->dispatch('success', __('Invoice updated'));
            // } else {
            $this->dispatchMessage('Avis au comptant');
            // }
        });

        // Reset form fields after successful submission
        $this->reset();
    }


    public function deleteUser($id)
    {
        // Prevent deletion of current Taxpayer
        // if ($id == Auth::id()) {
        //     $this->dispatch('error', 'Taxpayer cannot be deleted');
        //     return;
        // }

        // Delete the user record with the specified ID
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

        //$this->taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get();

        $this->taxpayer_taxables = $taxpayer_taxables = InvoiceItem::where('invoice_id', $id)->get();

        // $this->taxpayer_taxables = $taxpayer_taxables = InvoiceItem::join('taxpayer_taxables', 'taxpayer_taxables.id', '=', 'invoice_items.taxpayer_taxable_id')
        //                                     ->join('taxables', 'taxables.id', '=', 'taxpayer_taxables.taxable_id')
        //                                     ->select('*','taxpayer_taxables.name AS taxpayer_taxable_name','taxpayer_taxables.id AS taxpayer_taxable_id')
        //                                     ->where('invoice_items.invoice_id', $id)
        //                                     ->get();


        //dd($taxpayer_taxables->taxpayer_taxable);

        foreach ($taxpayer_taxables as $index => $invoice_item) {
            // Update the value in the component properties using the loop index as the key
            //dd($taxable->taxable);

            if ($invoice_item->taxpayer_taxable->taxable->periodicity == "Mois"){
                $period = 1;
            } elseif ($invoice_item->taxpayer_taxable->taxable->periodicity == "Ans") {
                $period = 0.083333;
            // }elseif ($taxable->taxable->periodicity == "Jours") {
            //     $period = 30;
            } else {
                $period = 1;
            }

            //dd($taxable->taxpayer_taxable->taxable->tax_label->name);
                $this->periodicity = $invoice_item->taxpayer_taxable->taxable->periodicity;

                $this->taxable_taxlabel = $invoice_item->taxpayer_taxable->taxable->tax_label->code.' : '.$invoice_item->taxpayer_taxable->taxable->name;

                $this->taxpayer_taxable_id[$index] = $invoice_item->taxpayer_taxable->id;
                $this->taxpayer_taxable[$index] = $invoice_item->taxpayer_taxable->name;

                $this->s_seize[$index] = $invoice_item->ii_seize;
                $this->s_seize_e[$index] = $invoice_item->taxpayer_taxable->seize;

                    //$this->s_tariff[$index] = $invoice_item->ii_tariff. ' %';
                    $this->s_tariff[$index] = $invoice_item->ii_tariff;
                    //$this->s_tariff_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff. ' %';
                    $this->s_tariff_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff;

                if ($invoice_item->taxpayer_taxable->taxable->tariff_type == "FIXED"){
                    $this->s_amount[$index] = $invoice_item->amount;
                    $this->s_amount_e[$index] =$invoice_item->taxpayer_taxable->taxable->tariff * $invoice_item->taxpayer_taxable->seize * $this->qty * $period;
                } else {
                    $this->s_amount[$index] = $invoice_item->amount / 100;
                    $this->s_amount_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff * $invoice_item->taxpayer_taxable->seize * $this->qty * $period / 100;
                }

        }

        $this->amount_ph = array_sum($this->s_amount)." FCFA";
        $this->amount_ph_e = array_sum($this->s_amount_e)." FCFA";

        $this->amount = array_sum($this->s_amount);
        $this->amount_e = array_sum($this->s_amount_e);

        $this->amount_red_e = $this->amount - $this->amount_e;
    }

    public function addInvoice($id)
    {
        // $this->edit_mode = false;
        // $this->view_mode = false;
        // $this->button_mode = true;

        // $this->invoice_id = '';

        // $this->qty = '';

        // // dd($this->edit_mode, 'loadInvoice');

        // //$taxpayer_taxables = $id ? TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get() : collect();
        // $this->taxpayer_taxables = $taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get();

        // //dd($taxpayer_taxables);

        // foreach ($taxpayer_taxables as $index => $taxable) {
        //     $this->taxpayer_taxable_id[$index] = $taxable->id;
        //     $this->taxpayer_taxable[$index] = $taxable->name;
        //     $this->s_seize[$index] = $taxable->seize;
        //     $this->s_tariff[$index] = $taxable->taxable->tariff;
        //     $this->s_amount[$index] = '';
        // }


        // $this->amount_ph = " FCFA";
        // $this->amount = '';

        // //dd($this->taxpayer_taxables);

        // $taxpayer = Taxpayer::find($id);

        // $this->taxpayer_id = $taxpayer->id;
        // $this->name = $taxpayer->name;
        // $this->tnif = $taxpayer->id;
        $this->zone = " ";
    }

    public function addTaxable($id)
    {
        //$taxpayer = Taxpayer::find($id);
        //dd($id);
        $this->taxpayer_taxable = $this->name;
        $this->s_seize = $this->seize;
    }

    public function changeTarrif($value)
    {
        $taxable =$this->taxpayer_taxable_id !=null? Taxable::find($this->taxpayer_taxable_id):null;
       if($taxable){
           if( $taxable->tariff==0){
               $this->tarisIsNull=true;
           }else{
               $this->tarisIsNull=false;
           }
       }
       if($this->tarisIsNull){
           $this->s_tariff = $this->tariff;
       }else{
           $this->tariff=$this->s_tariff;
       }
    if($this->taxpayer_taxable_id!=null){
        $taxable = Taxable::find($this->taxpayer_taxable_id);



    if ($taxable->periodicity == "Mois"){
        $period = 1;
    } elseif ($taxable->periodicity == "Ans") {
        $period = 0.083333;
        // }elseif ($taxable->taxable->periodicity == "Jours") {
        //     $period = 30;
    } else {
        $period = 1;
    }

    $this->periodicity = $taxable->periodicity;

    //$this->taxpayer_taxable_id = $taxable->id;
    $this->taxpayer_taxable = $this->name;

    $this->s_seize = $this->seize;
    //$this->s_tariff = $taxable->taxable->tariff;

    if ($taxable->tariff_type == "FIXED"){
        $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty * $period;
    } else {
        $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty* $period / 100;
    }
    //$this->qty[$index] = $taxable->seize;
    //$this->taxpayer_taxable_id = $taxable->id;
    //}

    $this->amount_ph = $this->s_amount." FCFA";
    $this->amount = $this->s_amount;
}


    }

    public function loadInvoice($value)
    {
        //$this->view_mode = true;

        $this->qty = $value;

        //dd($this->qty);

        //dd( $value, $this->qty, "loadInvoice");
        //$taxpayer = Taxpayer::find($id);
        $taxable = Taxable::find($this->taxpayer_taxable_id);

        //$this->s_amount[$id] = 10;
        // $this->name = $taxpayer->name;
        // $this->tnif = $taxpayer->tnif;
        // $this->zone = $taxpayer->zone_id;

        // foreach ($taxpayer_taxables as $taxable) {
        //     // Update the values in the component properties
        //     $this->s_amount[$taxable->id] = 10;
        // }
        //foreach ($taxpayer_taxables as $index => $taxable) {
            // Update the value in the component properties using the loop index as the key
            // dd($taxable->taxable);

            if ($taxable->periodicity == "Mois"){
                $period = 1;
            } elseif ($taxable->periodicity == "Ans") {
                $period = 0.083333;
            // }elseif ($taxable->taxable->periodicity == "Jours") {
            //     $period = 30;
            } else {
                $period = 1;
            }

            $this->periodicity = $taxable->periodicity;

            //$this->taxpayer_taxable_id = $taxable->id;
            $this->taxpayer_taxable = $this->name;

            $this->s_seize = $this->seize;
            //$this->s_tariff = $taxable->taxable->tariff;

            if ($taxable->tariff_type == "FIXED"){
                $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty * $period;
            } else {
                $this->s_amount = $this->s_seize * $this->s_tariff * $this->qty* $period / 100;
            }
            //$this->qty[$index] = $taxable->seize;
            //$this->taxpayer_taxable_id = $taxable->id;
        //}

        $this->amount_ph = $this->s_amount." FCFA";
        $this->amount = $this->s_amount;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
