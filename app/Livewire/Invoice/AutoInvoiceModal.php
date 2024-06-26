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
    //use WithFileUploads;
    use DispatchesMessages;

    public $invoice_id;

    // public $name;
    // public $tnif;
    // public $zone;

    // public $invoice_no;
    // public $periodicity;

    public $zone;
    public $taxlabel;
    public $qty=12;
    public $start_month;

    //public $s_amount = [];
    public $amount = 0;
    // public $s_seize = [];

    // public $s_amount_e = [];
    // public $s_tariff_e = [];
    // public $s_seize_e = [];

    // public $taxpayer_taxable_id = [];
    // public $taxpayer_taxables = [];

    // public $qty=[];
    // public $s_amount=[];
    // public $taxpayer_taxable_id=[];
    //public $invoice_id;
    //public $status;

    public $taxpayer_id;
    // public $taxpayer_taxable;

    // public $amount_ph;
    // public $amount_ph_e;
    // public $amount;
    // public $amount_e;

    // public $amount_red_e;


    // public $taxable_taxlabel;

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

    // public $cancel_reduct;

    // public $edit_mode = false;
    // public $view_mode = false;
    // public $button_mode = false;

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

    // public $taxpayer_id; // Define public property to hold taxpayer_id

    // // Constructor to accept taxpayer_id
    // public function mount($taxpayer_id)
    // {
    //     $this->taxpayer_id = $taxpayer_id;
    // }

    public function render()
    {
        //$cantons = Canton::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        //$genders = Gender::all();
        //$id_types = IdType::all();
        $zones = Zone::all();
        $tax_labels = TaxLabel::all();

        //$taxpayer_taxables = $this->taxpayer_id ? TaxpayerTaxable::where('taxpayer_id', $this->taxpayer_id)->where('billable', 1)->get() : collect();
        //$taxpayer_id = $this->taxpayer_id;

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        //return view('livewire.invoice.add-invoice-modal', ['taxpayer_id' => $this->taxpayer_id]);

        return view('livewire.invoice.auto-invoice-modal', compact('zones','tax_labels'));
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
        //$this->validate();

        DB::transaction(function () {

            //dd($this->qty, $this->start_month, $this->taxlabel, $this->zone);

            // $invoices = Invoice::where('validity', 'EXPIRED')->get();

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
                    'from_date' => date('Y-').$this->start_month."-01",
                    'to_date' => date('Y-').$this->start_month + $this->qty."-01",
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
                        $this->amount = $this->amount + ($invoiceitem->taxpayer_taxable->taxable->tariff * $this->qty * $invoiceitem->taxpayer_taxable->seize),
                    ];

                    InvoiceItem::create($invoiceItemsData);

                    // $taxpayer_taxableData = [
                    //     'invoice_id' => $created_invoice->id,
                    //     'bill_status' => 'BILLED',
                    // ];

                    $taxpayerTaxable = TaxpayerTaxable::find($invoiceitem->taxpayer_taxable_id);

                    // foreach ($taxpayerTaxables as $taxpayerTaxable) {
                        // $taxpayerTaxable->update($taxpayer_taxableData);

                        $taxpayerTaxable->invoice_id = $created_invoice->id;
                        $taxpayerTaxable->bill_status = 'BILLED';

                        $taxpayerTaxable->save();
                    // }

                }

                $created_invoice->invoice_no = $created_invoice->id;
                //$created_invoice->pay_status = $invoice->pay_status;
                $created_invoice->nic = $created_invoice->taxpayer_id. $created_invoice->id;

                $created_invoice->amount = $this->amount;

                $created_invoice->save();

                $invoice->validity = 'ARCHIVED';

                $invoice->save();
            }
                //$this->dispatch('success', __('New Invoice created'));
            $this->dispatchMessage('Avis');
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
       // $this->dispatch('success', 'Invoice successfully deleted');
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

            // } else {
            //     $this->s_amount[$index] = $taxable->seize * $taxable->taxable->tariff * $value * $period / 100;
            // }
            //$this->qty[$index] = $taxable->seize;
            //$this->taxpayer_taxable_id[$index] = $taxable->id;
        }

        $this->amount_ph = array_sum($this->s_amount)." FCFA";
        $this->amount_ph_e = array_sum($this->s_amount_e)." FCFA";

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

        // dd($this->edit_mode, 'loadInvoice');

        //$taxpayer_taxables = $id ? TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get() : collect();
        $this->taxpayer_taxables = $taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get();

        //dd($taxpayer_taxables);

        foreach ($taxpayer_taxables as $index => $taxable) {
            $this->taxpayer_taxable_id[$index] = $taxable->id;
            $this->taxpayer_taxable[$index] = $taxable->name;
            $this->s_seize[$index] = $taxable->seize;
            $this->s_tariff[$index] = $taxable->taxable->tariff;
            $this->s_amount[$index] = '';
        }


        $this->amount_ph = " FCFA";
        $this->amount = '';

        //dd($this->taxpayer_taxables);

        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
        $this->name = $taxpayer->name;
        $this->tnif = $taxpayer->id;
        $this->zone = $taxpayer->zone->name;
    }

    public function autoInvoice($value)
    {
        //$this->view_mode = true;

        $this->qty = $value;
        //dd( $value, $this->qty, "loadInvoice");
        //$taxpayer = Taxpayer::find($id);
        // $taxpayer_taxables = TaxpayerTaxable::where('taxpayer_id', $this->taxpayer_id)->where('billable', 1)->get();

        // //$this->s_amount[$id] = 10;
        // // $this->name = $taxpayer->name;
        // // $this->tnif = $taxpayer->tnif;
        // // $this->zone = $taxpayer->zone_id;

        // // foreach ($taxpayer_taxables as $taxable) {
        // //     // Update the values in the component properties
        // //     $this->s_amount[$taxable->id] = 10;
        // // }
        // foreach ($taxpayer_taxables as $index => $taxable) {
        //     // Update the value in the component properties using the loop index as the key
        //     // dd($taxable->taxable);

        //     if ($taxable->taxable->periodicity == "Mois"){
        //         $period = 1;
        //     } elseif ($taxable->taxable->periodicity == "Ans") {
        //         $period = 0.083333;
        //     // }elseif ($taxable->taxable->periodicity == "Jours") {
        //     //     $period = 30;
        //     } else {
        //         $period = 1;
        //     }

        //     $this->periodicity = $taxable->taxable->periodicity;

        //     $this->taxpayer_taxable_id[$index] = $taxable->id;
        //     $this->taxpayer_taxable[$index] = $taxable->name;

        //     $this->s_seize[$index] = $taxable->seize;
        //     $this->s_tariff[$index] = $taxable->taxable->tariff;

        //     if ($taxable->taxable->tariff_type == "FIXED"){
        //         $this->s_amount[$index] = $taxable->seize * $taxable->taxable->tariff * $this->qty * $period;
        //     } else {
        //         $this->s_amount[$index] = $taxable->seize * $taxable->taxable->tariff * $this->qty* $period / 100;
        //     }
        //     //$this->qty[$index] = $taxable->seize;
        //     $this->taxpayer_taxable_id[$index] = $taxable->id;
        // }

        // $this->amount_ph = array_sum($this->s_amount)." FCFA";
        // $this->amount = array_sum($this->s_amount);
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
