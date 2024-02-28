<?php

namespace App\Livewire\Payment;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
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
    public $taxpayer_id;

    public $name;
    public $tnif;
    public $zone;

    public $invoice_no;
    public $order_no;
    public $nic;

    public $qty;
    public $bill;
    
    public $paid;
    public $balance;
    public $s_amount;

    public $amount;
    public $payment_type;
    public $reference;

    public $edit_mode = false;

    protected $rules = [

        "amount" => "required",
        "payment_type" => "required",
        "reference" => "required",

        'taxpayer_id' => 'required',
        'invoice_id' => 'required',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_payment' => 'updatePayment',
        //'add_invoice' => 'addPayment',
        //'load_invoice' => 'loadPayment',
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

        //$invoiceitems = $this->invoice_id ? InvoiceItem::where('invoice_id', $this->invoice_id)->get() : collect();
        //$taxpayer_id = $this->taxpayer_id;

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        //return view('livewire.payment.add-payment-modal', ['taxpayer_id' => $this->taxpayer_id]);
        //dd($invoiceitems,$this->invoice_id);

        return view('livewire.payment.add-payment-modal', compact('taxpayers'));
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
                'invoice_id' => $this->invoice_id,
                'taxpayer_id' => $this->taxpayer_id,
                'amount' => $this->amount,
                'payment_type' => $this->payment_type,
                'reference' => $this->reference,
            ];

            //dd($paymentData);

            // Create or update Payment record
            $payment = Payment::find($this->payment_id) ?? Payment::create($paymentData);
            

            // $taxpayerTaxableData = [
            //     'pay_status' => $payment->id,
            // ];

            // $invoice = Invoice::find($this->invoice_id);
            //dd($this->bill,$this->amount);
            if ($this->amount >= $this->bill){
                $paystatus = "PAID";
            }else{
                $paystatus = "PART PAID";
            }

            $data = [
                'pay_status' => $paystatus,
            ];

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);
            
            $this->invoice_id = $invoice->id;

            foreach ($data as $k => $v) {
                $invoice->$k = $v;
            }
            $invoice->save();

            // foreach ($taxpayerTaxables as $taxpayerTaxable) {
            //     $taxpayerTaxable->update($taxpayerTaxableData);
            // }

            // // Prepare data for Payment_items
            // $paymentItemsData = [
            //     'payment_id' => $payment->id,
            //     'taxpayer_taxable_id' => $this->taxpayer_taxable_id,
            //     'qty' => $this->qty,
            //     'amount' => $this->s_amount,
            // ];

            //dd($paymentItemsData);

            // foreach ($this->taxpayer_taxable_id as $index => $taxpayer_taxable_id) {
            //     PaymentItem::create([
            //         'payment_id' => $payment->id,
            //         'taxpayer_taxable_id' => $taxpayer_taxable_id,
            //         'qty' => $this->qty,
            //         'amount' => $this->s_amount[$index],
            //     ]);
            // }

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
        
        $this->name = $invoice->taxpayer->name;
        $this->tnif = $invoice->taxpayer->id;
        $this->zone = $invoice->taxpayer->zone_id;

        $this->invoice_no = $invoice->id;
        $this->taxpayer_id = $invoice->taxpayer->id;
        $this->order_no = $invoice->order_no;
        $this->nic = $invoice->taxpayer->id.$invoice->id;
        
        $this->qty = $invoice->qty;
        $this->bill = $invoice->amount;
        

        //$payments = Payment::find($id);

        //foreach ($payments as $index => $payment) {
            //$this->s_amount += $payment->amount;
        //}

        //dd(($this->s_amount));

        //$this->paid = array_sum($this->s_amount);

        //$this->paid = $invoice->amount;
        $this->balance = $this->bill - $this->paid;

        //dd($this->invoice_id);
        
        // $invoiceitems = $this->invoiceitems = $this->invoice_id ? InvoiceItem::where('invoice_id', $id)->get() : collect();

        // //dd(($invoiceitems));
        
        // foreach ($invoiceitems as $index => $invoiceitem) {
        //     $this->taxpayer_taxable[$index] = $invoiceitem->name;


        // }
    }

    public function addPayment($id)
    {
        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
        $this->name = $taxpayer->name;
        $this->tnif = $taxpayer->id;
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
