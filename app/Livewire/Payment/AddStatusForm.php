<?php

namespace App\Livewire\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddStatusForm extends Component
{
    //use WithFileUploads;

    public $payment_id;

    public $status;

    public $edit_mode = false;

    protected $rules = [
        "status" =>"required",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_status' => 'updateStatus',
        //'add_payment' => 'addPayment',
    ];
    public function render()
    {
        return view('livewire.payment.add-status-form');
    }

    public function submit()
    {
        //dd($this->status);

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {

            // Prepare data for Payment
            $data = [
                'status' => $this->status,
                'r_user_id'=>  Auth::id()
            ];

            //dd($paymentData);

            // Create or update Payment record
            $payment = Payment::find($this->payment_id); //?? Payment::create($payment_id);


            $this->payment_id = $payment->id;

            foreach ($data as $k => $v) {
                $payment->$k = $v;
            }
            $payment->save();
                $this->dispatch('success', __('Payment updated'));
        });

        // Reset form fields after successful submission
        $this->reset();
    }

// public function updatePayment($id)
// {
//     $this->edit_mode = true;

//     $this->payment_id = $payment->id;
//     $this->tnif = $payment->taxpayer->tnif;
//     $this->zone = $payment->taxpayer->zone_id;
// }

    public function updateStatus($id)
    {

        $payment = Payment::find($id);

        $this->payment_id = $payment->id;
        $this->status = $payment->status;

        //$this->$payment = $payment;

        //dd($this->payment_id);

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}

