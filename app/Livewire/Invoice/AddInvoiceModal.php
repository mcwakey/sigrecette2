<?php

namespace App\Livewire\Invoice;

use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Notifications\InvoiceCreated;
use App\Notifications\InvoicePaid;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class AddInvoiceModal extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;

    public $invoice_id;

    public $name;
    public $tnif;
    public $zone;
    public $notes;

    public $invoice_no;
    public $periodicity;
    public $seize;
    public $tariff;
    public $qty;
    public $start_month;
    public $s_amount = [];
    public $s_tariff = [];
    public $s_seize = [];

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
    public $taxpayer_taxable;

    public $amount_ph;
    public $amount_ph_e;
    public $amount;
    public $amount_e;

    public $reduce_amount;


    public $taxable_taxlabel;

    // public function reduce($index)
    // {
    //     $product_id = $this->inputs[$index]['id'];
    //     $this->inputs[$index]['qty'] -= 1;
    //     $this->updateCart($product_id, $this->inputs[$index]['qty']);
    // }
    //public $created_at;
    // public $mobilephone;
    // public $email;
    // public $latitude;
    // public $canton;
    // public $town;
    // public $erea;
    // public $address;
    // public $zone_id;

    //public $avatar;
    //public $saved_avatar;

    // public $selectedTaxpayerId;

    // public function selectTaxpayer($taxpayerId)
    // {
    //     $this->selectedTaxpayerId = $taxpayerId;

    //     dd($this->selectedTaxpayerId);
    // }

    public $cancel_reduct;

    public $edit_mode = false;
    public $view_mode = false;
    public $button_mode = false;

    protected $rules = [
        "s_amount" => "required|numeric",
        "taxpayer_taxable_id" => "required|int",
        "qty" => "required|numeric",
        "start_month" => "required|string",

        'taxpayer_id' => 'required|int',
        'amount' => 'required|numeric',
        //'cancel_reduct' => 'required|string',


    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_invoice' => 'updateInvoice',
        'add_invoice' => 'addInvoice',
        'view_invoice' => 'viewInvoice',
        'load_invoice' => 'loadInvoice',
    ];

    // public $taxpayer_id; // Define public property to hold taxpayer_id

    // // Constructor to accept taxpayer_id
    // public function mount($taxpayer_id)
    // {
    //     $this->taxpayer_id = $taxpayer_id;
    // }

    public function render()
    {

        $taxpayers = Taxpayer::all();


        $year= Year::getActiveYear();
        $months = Constants::getMonths();

        return view('livewire.invoice.add-invoice-modal', compact('taxpayers','months','year'));
    }
    public function mount($id=null){
        $this->taxpayer_id=$id;
    }

    // public function submit()
    // {
    //     // Validate the form input data
    //     $this->validate();

    //     DB::transaction(function () {

    //         $data = [
    //             //save into Invoice_items table
    //             "taxpayer_taxable_id" => $this->taxpayer_taxable_id,
    //             "qty" => $this->qty,
    //             "s_amount" => $this->s_amount,

    //             //save into Invoice table
    //             'taxpayer_id' => $this->taxpayer_id,
    //             'amount' => $this->amount,

    //         ];

    //         $invoice = Invoice::find($this->invoice_id) ?? Invoice::create($data);

    //         if ($this->edit_mode) {
    //             foreach ($data as $k => $v) {
    //                 $invoice->$k = $v;
    //             }
    //             $invoice->save();
    //         }

    //         if ($this->edit_mode) {
    //             $this->dispatch('success', __('Invoice updated'));
    //         } else {
    //             $this->dispatch('success', __('New Invoice created'));
    //         }
    //     });

    //     $this->reset();
    // }

    public function submit()
    {


        // Validate the form input data

        DB::transaction(function () {

            if (!$this->edit_mode) {
                $this->invoice_id = null;
            }

            $from_date = Carbon::createFromDate(date('Y'), $this->start_month, 1);

            $to_date = $from_date->copy()->addMonths($this->qty - 1)->endOfMonth();
            $invoiceData = [
                'taxpayer_id' => $this->taxpayer_id,
                'amount' => $this->amount,
                'qty' => $this->qty,
                'from_date' => $from_date->toDateString(),
                'to_date' => $to_date->toDateString(),
                'notes'=>$this->notes
                // 'pay_status' => 'DRAFT',
            ];

            if ($this->edit_mode) {
                $invoiceData['amount'] = $this->amount_e;
                $invoiceData['reduce_amount'] = $this->reduce_amount;
                //FIX CANCEL INVOICE BUG
                if ($this->cancel_reduct== InvoiceStatusEnums::CANCELED) {
                    $invoiceData['reduce_amount'] = $this->amount_e;
                    $invoice= Invoice::find($this->invoice_id);
                    foreach ($invoice->taxpayer_taxables()->get() as $item){
                        $item->invoice_id=null;
                        $item->bill_status="NOT BILLED";
                        $item->save();
                    }
                }
                $invoiceData['status'] = 'DRAFT';
            }

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::create($invoiceData);

            // Save the invoice ID into the invoice_no column

            $invoice->invoice_no = $this->invoice_id ?? $invoice->id;
            $invoice->nic = $this->taxpayer_id . ($this->invoice_id ?? $invoice->id);
            //$invoice->order_no = $this->order_no;
            $invoice->save();

            //$a = $this->invoice_id ?? $invoice->id;
            //$a = ($this->invoice_id ?? $invoice->id);

            //dd($a);

            $taxpayerTaxableData = [
                'invoice_id' => $this->invoice_id ?? $invoice->id,
                'bill_status' => 'BILLED',
                'billable' => '0',
            ];

            $taxpayerTaxables = TaxpayerTaxable::whereIn('id', $this->taxpayer_taxable_id)->get();

            foreach ($taxpayerTaxables as $taxpayerTaxable) {
                $taxpayerTaxable->update($taxpayerTaxableData);
            }

            // Prepare data for Invoice_items
            // $invoiceItemsData = [
            //     'invoice_id' => $invoice->id,
            //     'taxpayer_taxable_id' => $this->taxpayer_taxable_id,
            //     'qty' => $this->qty,
            //     'amount' => $this->s_amount,
            // ];

            //dd($invoiceItemsData);

            foreach ($this->taxpayer_taxable_id as $index => $taxpayer_taxable_id) {
                $invoiceItemsData = [
                    'invoice_id' => $invoice->id,
                    'taxpayer_taxable_id' => $taxpayer_taxable_id,
                    'qty' => $this->qty,
                    'amount' => $this->s_amount[$index],
                    'ii_tariff' => $this->s_tariff[$index],
                    'ii_seize' => $this->s_seize[$index],
                ];

                if ($this->edit_mode) {

                    $invoiceItemsData['amount'] = $this->s_amount_e[$index];
                    $invoiceItemsData['ii_tariff'] = $this->s_tariff_e[$index];
                    $invoiceItemsData['ii_seize'] = $this->s_seize_e[$index];
                }

                //dd($this->s_amount_e);

                InvoiceItem::create($invoiceItemsData);
            }

            $invoice_old = Invoice::find($this->invoice_id ?? $invoice->id);

            if ($this->edit_mode) {
                // Save the invoice ID into the invoice_no column
                $invoice_old->status = $this->cancel_reduct;
                //$invoice_old->status = "CANCELED";
                $invoice_old->validity = "CANCELED";
                $invoice->type=$invoice_old->type;
               if($invoice_old->type==Constants::INVOICE_TYPE_COMPTANT){
                   $invoice->status= InvoiceStatusEnums::PENDING;
                   $invoice->processOnInvoicesByUser('regisseur');
               }
                $invoice_old->save();
            }

            //$invoice->order_no = $invoice_old->order_no;
            $invoice->pay_status = $invoice_old->pay_status;
            $invoice->save();

            if(!$this->edit_mode){
                $permissions = ['peut émettre un avis sur titre', 'peut accepter un avis sur titre', 'peut rejeter un avis sur titre (agent par délégation de l\'ordonateur)'];
                $users = Constants::getUserWithPermission($permissions);
               // dump($users);
                if ($users && count($users)>0) {

                    Notification::send($users, new InvoiceCreated($invoice,Auth::user(),'agent_delegation'));
                }

            }

            if ($this->edit_mode) {
                $this->dispatchMessage('Avis', 'update');
            } else {
                $this->dispatchMessage('Avis');
            }
        });

        // Reset form fields after successful submission
        $this->reset();
        //$this->redirectRoute('invoices.index', ['state' =>  Constants::INVOICE_STATE_DRAFT_KEY,'type' => Constants::INVOICE_TYPE_TITRE_KEY]);
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

        $this->qty = intval($invoice->qty);
        // dd($this->qty);

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

            // if ($invoice_item->taxpayer_taxable->taxable->periodicity == "Mois") {
            //     $period = 1;
            // } elseif ($invoice_item->taxpayer_taxable->taxable->periodicity == "Ans") {
            //     $period = 0.083333;
            //     // }elseif ($taxable->taxable->periodicity == "Jours") {
            //     //     $period = 30;
            // } else {
                $period = 1;
            // }

            //dd($taxable->taxpayer_taxable->taxable->tax_label->name);
            $this->periodicity = $invoice_item->taxpayer_taxable->taxable->periodicity;

            $this->taxable_taxlabel = $invoice_item->taxpayer_taxable->taxable->tax_label->code . ' : ' . $invoice_item->taxpayer_taxable->taxable->name;

            $this->taxpayer_taxable_id[$index] = $invoice_item->taxpayer_taxable->id;
            $this->taxpayer_taxable[$index] = $invoice_item->taxpayer_taxable->name;

            $this->s_seize[$index] = $invoice_item->ii_seize;
            $this->s_seize_e[$index] = $invoice_item->taxpayer_taxable->seize;

            //$this->s_tariff[$index] = $invoice_item->ii_tariff. ' %';
            $this->s_tariff[$index] = $invoice_item->ii_tariff;
            //$this->s_tariff_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff. ' %';
            $this->s_tariff_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff;

            $temp_seize =$invoice_item->taxpayer_taxable->seize;
            if($invoice_item->taxpayer_taxable->taxable->use_second_formula){
                $temp_seize =1;
            }
            if ($invoice_item->taxpayer_taxable->taxable->tariff_type == "FIXED") {
                $this->s_amount[$index] = $invoice_item->amount;
                $this->s_amount_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff *  $temp_seize  * $this->qty * $period;
            } else {
                $this->s_amount[$index] = $invoice_item->amount / 100;
                $this->s_amount_e[$index] = $invoice_item->taxpayer_taxable->taxable->tariff *  $temp_seize * $this->qty * $period / 100;
            }

            // } else {
            //     $this->s_amount[$index] = $taxable->seize * $taxable->taxable->tariff * $value * $period / 100;
            // }
            //$this->qty[$index] = $taxable->seize;
            //$this->taxpayer_taxable_id[$index] = $taxable->id;
        }

        $this->amount_ph = array_sum($this->s_amount);
        $this->amount_ph_e = array_sum($this->s_amount_e);

        $this->amount = array_sum($this->s_amount);
        $this->amount_e = array_sum($this->s_amount_e);

        $this->reduce_amount = $this->amount - $this->amount_e;
        //$this->reduce_amount = - $this->amount_e;
    }

    public function addInvoice($id)
    {
        $this->edit_mode = false;
        $this->view_mode = false;
        $this->button_mode = true;

        $this->invoice_id = '';

        if ($this->periodicity == "Mois") {
            $this->qty = 12;
        }else {
            $this->qty = 1;
        }

        // dd($this->edit_mode, 'loadInvoice');

        //$taxpayer_taxables = $id ? TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get() : collect();
        $this->taxpayer_taxables = $taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get();

        //dd($taxpayer_taxables);

        foreach ($taxpayer_taxables as $index => $taxable) {

            // $this->unit_type = $taxable->taxable->unit_type;

            $this->taxpayer_taxable_id[$index] = $taxable->id;
            $this->taxpayer_taxable[$index] = $taxable->name;
            $this->s_seize[$index] = $taxable->seize;
            $this->s_tariff[$index] = $taxable->taxable->tariff;
            $this->s_amount[$index] = '';
        }


        $this->amount_ph = '';
        $this->amount = '';

        //dd($this->taxpayer_taxables);

        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
        $this->name = $taxpayer->name;
        $this->tnif = $taxpayer->id;
        $this->zone = $taxpayer->zone->name;

        $this->loadInvoice($this->qty);
    }

    public function loadInvoice($value)
    {
        //$this->view_mode = true;

        $this->qty = $value;
        //dd( $value, $this->qty, "loadInvoice");
        //$taxpayer = Taxpayer::find($id);
        $taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $this->taxpayer_id)->where('billable', 1)->get();

        //$this->s_amount[$id] = 10;
        // $this->name = $taxpayer->name;
        // $this->tnif = $taxpayer->tnif;
        // $this->zone = $taxpayer->zone_id;

        // foreach ($taxpayer_taxables as $taxable) {
        //     // Update the values in the component properties
        //     $this->s_amount[$taxable->id] = 10;
        // }
        foreach ($taxpayer_taxables as $index => $taxable) {
            // Update the value in the component properties using the loop index as the key
            // dd($taxable->taxable);

            // if ($taxable->taxable->periodicity == "Mois") {
            //     $period = 1;
            // } elseif ($taxable->taxable->periodicity == "Ans") {
            //     $period = 0.083333;
            //     // }elseif ($taxable->taxable->periodicity == "Jours") {
            //     //     $period = 30;
            // } else {
                $period = 1;
            // }

            $this->periodicity = $taxable->taxable->periodicity;
            // $this->unit_type = $taxable->taxable->unit_type;

            $this->taxpayer_taxable_id[$index] = $taxable->id;
            $this->taxpayer_taxable[$index] = $taxable->name;


            $this->s_seize[$index] = $taxable->seize;
            $this->s_tariff[$index] = $taxable->taxable->tariff;

            $temp_seize =$taxable->seize;
            if($taxable->taxable->use_second_formula){
                $temp_seize =1;
            }
            if ($taxable->taxable->tariff_type == "FIXED") {
                $this->s_amount[$index] =$temp_seize * $taxable->taxable->tariff * $this->qty * $period;
            } else {
                $this->s_amount[$index] = $temp_seize * $taxable->taxable->tariff * $this->qty * $period / 100;
            }
            //$this->qty[$index] = $taxable->seize;
            $this->taxpayer_taxable_id[$index] = $taxable->id;
        }

        $this->amount_ph = array_sum($this->s_amount);
        $this->amount = array_sum($this->s_amount);
    }
    #[On('updateSharedTaxpayerId')]
    public function updateSharedTaxpayerId($id){
        $taxpayer = Taxpayer::findOrFail($id);

        if($taxpayer instanceof Taxpayer){
            $this->taxpayer_id = $taxpayer->id;
            $this->addInvoice($id);
        }

    }
    #[On('updateSharedInvoiceId')]
    public function updateSharedInvoiceId($id){
        $this->updateInvoice($id);

    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
