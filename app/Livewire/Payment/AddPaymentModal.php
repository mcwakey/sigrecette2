<?php

namespace App\Livewire\Payment;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Notification;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class AddPaymentModal extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;

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
    public $s_amount =[];

    public $amount;
    public $payment_type;
    public $reference;
    public $remaining_amount;
    public $edit_mode = false;

    protected $rules = [

        "amount" => "required",
        "payment_type" => "required",
        "reference" => "required",

        //'taxpayer_id' => 'required',
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
                // 'invoice_id' => $this->invoice_id,
                'invoice_id' => $this->invoice_id,
                'taxpayer_id' => ($this->taxpayer_id === "") ? null : $this->taxpayer_id,
                'amount' => $this->amount,
                'payment_type' => $this->payment_type,
                'reference' => $this->reference,
                'remaining_amount' =>$this->bill-($this->amount + $this->paid),
                'user_id'=>  Auth::id(),

            ];

            //dd($paymentData);

            // Create or update Payment record
            $payment = Payment::find($this->payment_id) ?? Payment::create($paymentData);


            // $taxpayerTaxableData = [
            //     'pay_status' => $payment->id,
            // ];

            // $invoice = Invoice::find($this->invoice_id);
            //dd($this->bill,$this->amount);

            if ($this->amount + $this->paid >= $this->bill){
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

            $role = Role::where('name', 'regisseur')->first();

            if ($role) {
                $users = $role->users()->get();
                Notification::send($users, new InvoicePaid($payment,Auth::user()));

            }

            if ($this->edit_mode) {
                $this->dispatchMessage('Paiement', 'update');
            } else {
                $this->dispatchMessage('Paiement');
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
       // $this->dispatch('success', 'Payment successfully deleted');
        $this->dispatchMessage('Paiement', 'delete');
    }

    public function updatePayment($id)
    {
        //dd($id);

        $this->edit_mode = true;

        $invoice = Invoice::where('invoice_no', $id)
                    ->where('validity', 'VALID')
                    ->first();


                            //dd($invoice);

        $this->invoice_id = $invoice->id;
        $this->taxpayer_id = $invoice->taxpayer->id ?? "";

        $this->name = $invoice->taxpayer->name ?? "";
        $this->tnif = $invoice->taxpayer->id ?? "";
        $this->zone = $invoice->taxpayer->zone->name ?? "";

        $this->invoice_no = $invoice->invoice_no;
        $this->order_no = $invoice->order_no;
        $this->nic = $invoice->nic;

        $this->qty = $invoice->qty;
        $this->bill = $invoice->amount;

        $payments = Payment::where('invoice_id', $invoice->id)->get();
        $this->s_amount = []; // Initialize as an empty array

        foreach ($payments as $index => $payment) {
            $this->s_amount[$index] = $payment->amount;
        }

        $this->paid = array_sum($this->s_amount) ?? 0;


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

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
