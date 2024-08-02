<?php

namespace App\Livewire\Payment;

use App\Helpers\InvoiceHelper;
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
    public $paidAndCodeArray;
    public $validCodes;

    public $edit_amount =true;
    public $notes;

    protected function rules(){
        $rules = [

            "amount" => "required|numeric",
            "payment_type" => "required",
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
        'update_payment' => 'updatePayment',
        'update_payment_amount'=>'updatePaymentAmount',
        'update_local_amount'=> 'updateLocalAmount',

    ];



    public function render()
    {
        $taxpayers = Taxpayer::all();
        $invoice = Invoice::find($this->invoice_id);

        if ($invoice != null) {
            if( $invoice->type == Constants::INVOICE_TYPE_COMPTANT){
                $this->amount = $invoice->amount;
                $this->edit_amount=false;
            }
            $this->paidAndCodeArray= InvoiceHelper::returnPaidAndSumByCode($invoice)[0];
            $this->validCodes = array_keys($this->paidAndCodeArray);
        }

        $paidAndCodeArray =$this->paidAndCodeArray;


        return view('livewire.payment.add-payment-modal', compact('taxpayers','paidAndCodeArray','invoice'));
    }

    public function submit()
    {
        $is_regisseur=false;
        $role = Role::where('name', 'regisseur')->first();
        if ($role) {
            /**@var App\Models\User $user  */
            $user = auth()->user();
            if ($user->hasRole('regisseur')) {
                $is_regisseur =true;
            }
        }

        if ($is_regisseur) {
            $this->rules()["reference"] = "required";
        }
        $this->validate();
        DB::transaction(function () use ($role, $is_regisseur) {

            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);

            if (($this->paid + $this->amount) <= $invoice->amount  ) {

                if($this->code!=null){
                    if ($this->amount>=$this->paidAndCodeArray[ $this->code ]['amount']){
                        $this->amount=$this->paidAndCodeArray[ $this->code ]['amount'];
                    }
                }

                $paymentData = [
                    'invoice_id' => $this->invoice_no,
                    'taxpayer_id' => ($this->taxpayer_id === "") ? null : $this->taxpayer_id,
                    'amount' => $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? $invoice->amount : $this->amount,
                    'payment_type' => $this->payment_type,
                    'reference' => $this->reference,
                    'code'=>$this->code,
                    'description' =>  $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? "Avis " . $this->invoice_no : "Avis " . $this->invoice_no . ", OR " . $this->order_no,
                    'remaining_amount' => $this->bill - ($this->amount + $this->paid),
                    'user_id' =>  Auth::id(),
                    'invoice_type' => $invoice->type,
                    'notes'=>$this->notes

                ];


                if ($is_regisseur) {
                    $paymentData['status'] = PaymentStatusEnums::ACCOUNTED;
                }else{

                }

                $payments = InvoiceHelper::getCode($this->invoice_no, $this->amount, $paymentData);
                $payment = Payment::find($this->payment_id);

                // dd($payments);

                if ($payment == null) {
                    foreach ($payments as $payment) {
                        $tempPay = Payment::create($payment);
                        if ($is_regisseur) {
                            $users = $role->users()->get();
                            Notification::send($users, new InvoicePaid($tempPay , Auth::user()));
                        }
                    }
                }




                if ($this->amount + $this->paid >= $this->bill) {
                    $paystatus = "PAID";
                } else {
                    $paystatus = "PART PAID";
                }
                $data = [
                    'pay_status' => $paystatus,
                ];




                $this->invoice_id = $invoice->id;

                foreach ($data as $k => $v) {
                    $invoice->$k = $v;
                }
                $invoice->save();

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




    public function updatePayment($id)
    {


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
        $this->paid = Payment::getPaid($invoice->invoice_no);



        $this->periodicity = ' / '.$invoice->taxpayer->taxpayer_taxables->first()->taxable->periodicity;

        $this->balance = $this->bill - $this->paid;


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
    #[On('delete_payment')]
    public function deletePayment($id)
    {
        $payment = Payment::find($id);
        $invoice = Invoice::where('invoice_no',  $payment->invoice_id)
            ->where('validity', 'VALID')
            ->first();
        Payment::destroy($id);
        $paid= Payment::getPaid($invoice->invoice_no);
        if ($paid ==0) {
            $paystatus = PaymentStatusEnums::PENDING;
        } else {
            $paystatus =  "PART PAID";
        }
        $invoice->pay_status =$paystatus;
        $invoice->save();
        $this->dispatchMessage('Paiement', 'delete');
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
