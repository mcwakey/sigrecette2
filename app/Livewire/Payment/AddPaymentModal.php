<?php

namespace App\Livewire\Payment;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddPaymentModal extends Component
{
    //use WithFileUploads;

    public $payment_id;
    public $invoice_id;

    public $name;
    public $tnif;
    public $zone;

    public $qty;
    public $s_amount = [];
    public $taxpayer_taxable_id = [];

    // public $qty=[];
    // public $s_amount=[];
    // public $taxpayer_taxable_id=[];
    //public $payment_id;
    //public $status;

    public $taxpayer_id;
    public $amount;

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

    public $edit_mode = false;

    protected $rules = [
        // 'payment_id' => 'required|string',
        // 'payment_no' => 'required',
        // 'order_no' => 'required',
        // 'nic' => 'required',
        // 'status' => 'required|string',

        "s_amount" => "required",
        "taxpayer_taxable_id" => "required",
        "qty" => "required",

        'taxpayer_id' => 'required',
        'amount' => 'required',

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
        'update_payment' => 'updatePayment',
        'add_invoice' => 'addPayment',
        'load_invoice' => 'loadPayment',
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
        $taxpayers = Taxpayer::all();
        //$taxpayer_taxables = TaxpayerTaxable::all();

        $taxpayer_taxables = $this->taxpayer_id ? TaxpayerTaxable::where('taxpayer_id', $this->taxpayer_id)->where('billable', 1)->get() : collect();
        //$taxpayer_id = $this->taxpayer_id;

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        //return view('livewire.payment.add-payment-modal', ['taxpayer_id' => $this->taxpayer_id]);

        return view('livewire.payment.add-payment-modal', compact('taxpayers', 'taxpayer_taxables'));
    }

    // public function submit()
    // {
    //     // Validate the form input data
    //     $this->validate();

    //     DB::transaction(function () {

    //         $data = [
    //             //save into Payment_items table
    //             "taxpayer_taxable_id" => $this->taxpayer_taxable_id,
    //             "qty" => $this->qty,
    //             "s_amount" => $this->s_amount,

    //             //save into Payment table
    //             'taxpayer_id' => $this->taxpayer_id,
    //             'amount' => $this->amount,

    //         ];

    //         $payment = Payment::find($this->payment_id) ?? Payment::create($data);

    //         if ($this->edit_mode) {
    //             foreach ($data as $k => $v) {
    //                 $payment->$k = $v;
    //             }
    //             $payment->save();
    //         }

    //         if ($this->edit_mode) {
    //             $this->dispatch('success', __('Payment updated'));
    //         } else {
    //             $this->dispatch('success', __('New Payment created'));
    //         }
    //     });

    //     $this->reset();
    // }

    public function submit()
    {


       

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {

            // Prepare data for Payment
            $paymentData = [
                'taxpayer_id' => $this->taxpayer_id,
                'amount' => $this->amount,
            ];

            //dd($paymentData);

            // Create or update Payment record
            $payment = Payment::find($this->payment_id) ?? Payment::create($paymentData);
            

            $taxpayerTaxableData = [
                'payment_id' => $payment->id,
                'billable' => '0',
            ];

            $taxpayerTaxables = TaxpayerTaxable::whereIn('id', $this->taxpayer_taxable_id)->get();

            foreach ($taxpayerTaxables as $taxpayerTaxable) {
                $taxpayerTaxable->update($taxpayerTaxableData);
            }

            // Prepare data for Payment_items
            $paymentItemsData = [
                'payment_id' => $payment->id,
                'taxpayer_taxable_id' => $this->taxpayer_taxable_id,
                'qty' => $this->qty,
                'amount' => $this->s_amount,
            ];

            //dd($paymentItemsData);

            foreach ($this->taxpayer_taxable_id as $index => $taxpayer_taxable_id) {
                PaymentItem::create([
                    'payment_id' => $payment->id,
                    'taxpayer_taxable_id' => $taxpayer_taxable_id,
                    'qty' => $this->qty,
                    'amount' => $this->s_amount[$index],
                ]);
            }

            // Dispatch success message
            if ($this->edit_mode) {
                $this->dispatch('success', __('Payment updated'));
            } else {
                $this->dispatch('success', __('New Payment created'));
            }
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
        Payment::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Payment successfully deleted');
    }

    public function updatePayment($id)
    {
        //dd($id);

        $this->edit_mode = true;

        $invoice = Invoice::find($id);
        $this->invoice_id = $invoice->id;
        
        $this->tnif = $invoice->taxpayer->tnif;
       
        $this->zone = $invoice->taxpayer->zone_id;
    }

    public function addPayment($id)
    {
        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
        $this->name = $taxpayer->name;
        $this->tnif = $taxpayer->tnif;
        $this->zone = $taxpayer->zone_id;
    }

    public function loadPayment($id, $value)
    {
        //dd($id, $value);
        //$taxpayer = Taxpayer::find($id);
        $taxpayer_taxables = $this->taxpayer_id ? TaxpayerTaxable::where('taxpayer_id', $id)->where('billable', 1)->get() : collect();

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
            //dd($taxable->taxable);

            if ($taxable->taxable->periodicity == "Mois"){
                $period = 1;
            } elseif ($taxable->taxable->periodicity == "Ans") {
                $period = 0.083333;
            }elseif ($taxable->taxable->periodicity == "Jours") {
                $period = 30;
            } else {
                $period = 1;
            }


            if ($taxable->taxable->tariff_type == "FIXED"){
                $this->s_amount[$index] = $taxable->seize * $taxable->taxable->tariff * $value * $period;
            } else {
                $this->s_amount[$index] = $taxable->seize * $taxable->taxable->tariff * $value * $period / 100;
            }
            //$this->qty[$index] = $taxable->seize;
            $this->taxpayer_taxable_id[$index] = $taxable->id;
        }
        
        $this->amount = array_sum($this->s_amount);
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
