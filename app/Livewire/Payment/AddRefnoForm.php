<?php

namespace App\Livewire\payment;

use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvoiceAccepted;
use Illuminate\Support\Facades\Notification;


class AddRefnoForm extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;

    public $payment_id;

    public $refno;

    public $edit_mode = false;

    protected $rules = [
        "refno" => "required|string",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_invoice' => 'updateInvoice',
        //'add_invoice' => 'addInvoice',
    ];
    public function render()
    {
        return view('livewire.payment.add-refno-form');
    }


    public function submit()
    {
        $this->validate();

            DB::transaction(function () {

                // Prepare data for Payment
                $data = [
                    'reference' => $this->refno,
                    // 'r_user_id'=>  Auth::id()
                ];
    
                //dd($paymentData);
    
                // Create or update Payment record
                $payment = Payment::find($this->payment_id); //?? Payment::create($payment_id);
    
    
                $this->payment_id = $payment->id;
    
                foreach ($data as $k => $v) {
                    $payment->$k = $v;
                }
                $payment->save();
                    //$this->dispatch('success', __('Payment updated'));
                // $this->dispatchMessage('Paiement', 'update');
            });

            $this->reset();
        // }else{
            $this->dispatchMessage('Numéro de quitance', 'update');

        // }




    }

    public function updateInvoice($id)
    {
        $this->payment_id = $id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
