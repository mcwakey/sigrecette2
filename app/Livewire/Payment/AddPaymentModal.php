<?php

namespace App\Livewire\Payment;

use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Helpers\Constants;
use App\Enums\PaymentStatusEnums;
use App\Models\User;
use App\Notifications\InvoicePaid;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

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
    public $s_amount = [];

    public $amount;
    public $payment_type;
    public $reference;
    public $remaining_amount;
    public $edit_mode = false;

    public $periodicity;
    public $code;
    public  $paidAndCodeArray;
    public $validCodes;

    public $edit_amount =true;
    protected function rules(){
        $rules = [

            "amount" => "required|numeric",
            "payment_type" => "required",
            "reference" => "required",
            'code'=>[
                'nullable',
                'sometimes',
                'numeric'
            ],


            //'taxpayer_id' => 'required',
            'invoice_id' => 'required',
        ];
        if($this->code!=null){
            $rules['code']=Rule::in($this->validCodes);
        }
        return $rules;
    }

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_payment' => 'updatePayment',
        'update_payment_amount'=>'updatePaymentAmount',
        'update_local_amount'=> 'updateLocalAmount',
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
        $invoice = Invoice::find($this->invoice_id);

        if ($invoice != null) {
            if( $invoice->type == Constants::INVOICE_TYPE_COMPTANT){
                $this->amount = $invoice->amount;
                $this->edit_amount=false;
            }
            $this->paidAndCodeArray=Invoice::returnPaidAndSumByCode($invoice)[0];
            $this->validCodes = array_keys($this->paidAndCodeArray);
        }

        $paidAndCodeArray =$this->paidAndCodeArray;


        return view('livewire.payment.add-payment-modal', compact('taxpayers','paidAndCodeArray','invoice'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();
        DB::transaction(function () {

            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);

            if (($this->paid + $this->amount) <= $invoice->amount  ) {

                if($this->code!=null){
                    if ($this->amount>=$this->paidAndCodeArray[ $this->code ]['amount']){
                        $this->amount=$this->paidAndCodeArray[ $this->code ]['amount'];
                    }
                }
                dd($this->amount,$this->code);
                $paymentData = [
                    // 'invoice_id' => $this->invoice_id,
                    'invoice_id' => $this->invoice_no,
                    'taxpayer_id' => ($this->taxpayer_id === "") ? null : $this->taxpayer_id,
                    'amount' => $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? $invoice->amount : $this->amount,
                    'payment_type' => $this->payment_type,
                    'reference' => $this->reference,
                    'code'=>$this->code,
                    'description' =>  $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? "Avis " . $this->invoice_no : "Avis " . $this->invoice_no . ", OR " . $this->order_no,
                    'remaining_amount' => $this->bill - ($this->amount + $this->paid),
                    'user_id' =>  Auth::id(),
                    'invoice_type' => $invoice->type

                ];

                $role = Role::where('name', 'regisseur')->first();
                if ($role) {
                    /**@var App\Models\User $user  */
                    $user = auth()->user();
                    if ($user->hasRole('regisseur')) {
                        $paymentData['status'] = PaymentStatusEnums::ACCOUNTED;
                    }
                }

                $payments = Invoice::getCode($this->invoice_no, $this->amount, $paymentData);
                $payment = Payment::find($this->payment_id);

                if ($payment == null) {
                    foreach ($payments as $payment) {
                        $tempPay = Payment::create($payment);

                        /**@var App\Models\User $user  */
                        $user = auth()->user();

                        if ($role && !$user->hasRole('regisseur')) {
                            $users = $role->users()->get();
                            Notification::send($users, new InvoicePaid($tempPay , Auth::user()));
                        }
                    }
                }


                // $taxpayerTaxableData = [
                //     'pay_status' => $payment->id,
                // ];

                // $invoice = Invoice::find($this->invoice_id);
                //dd($this->bill,$this->amount);

                if ($this->amount + $this->paid >= $this->bill) {
                    $paystatus = "PAID";
                } else {
                    $paystatus = "PART PAID";
                }
                $data = [
                    'pay_status' => $paystatus,
                ];

                //dd($invoiceData);

                // Create or update Invoice record


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


                // $role = Role::where('name', 'regisseur')->first();
                // /**@var App\Models\User $user  */
                // $user = auth()->user();

                // if ($role && !$user->hasRole('regisseur')) {
                //     $users = $role->users()->get();
                //     Notification::send($users, new InvoicePaid($payment, Auth::user()));
                // }

                if ($this->edit_mode) {
                    $this->dispatchMessage('Paiement', 'update');
                } else {
                    $this->dispatchMessage('Paiement');
                }
            } else {
                $this->dispatchMessage('Paiment', 'update', 'error', "Erreur lors de la mise à jour du paiement,Vous avez saisi des données de paiement incorrectes.");
            }
        });

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
        $this->paid = Invoice::getPaid($invoice->invoice_no);



        $this->periodicity = ' / '.$invoice->taxpayer->taxpayer_taxables->first()->taxable->periodicity;

        // dd($this->periodicity);

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

    public function updatePaymentAmount($code){

        //dump($code);
        if($code){
            $this->code = $code;
            $this->amount = $this->paidAndCodeArray[$code]['amount'];
        }

    }
    public function updateLocalPayment(){
        //dump($this->amount);
    }

    #[On('updateSharedInvoiceId')]
    public function updateSharedTaxpayerId($id){
        $this->updatePayment($id);
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
