<?php

namespace App\Livewire\AccountantDeposit;

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

    // public $invoice_id;

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
        return view('livewire.accountant_deposit.add-refno-form');
    }


    public function submit()
    {
        $this->validate();

            DB::transaction(function () {


                $payments_olds = Payment::where('status',PaymentStatusEnums::DONE )->where('status',PaymentStatusEnums::CANCELED )->where('reference_deposit', null )->get();
                $payments_olds = Payment::where(function($query) {$query->where('status', PaymentStatusEnums::DONE)->orWhere('status', PaymentStatusEnums::CANCELED);})->where('reference_deposit', null)->get();
                


                     foreach ($payments_olds as $payments_old) {

                        // $paymentsData = [
                        //     'status' => 'DONE',
                        //     // 'trans_id' => $stock_transfer->trans_id,
                        //     // //'qty' => $stock_transfer->qty,
                        //     // 'type' => 'ARCHIVED',
                        //     // 'end_no' => $stock_transfer->end_no,
                        //     // 'taxable_id' => $stock_transfer->taxable_id,
                        //     // 'trans_type' => 'RENDU',
                        //     // 'payment_id' => $payment->id,
                        //     // 'by_user_id' => $this->user_id,
                        //     // 'to_user_id' => $stock_transfer->to_user_id,
                        // ];

                        // $stock_transfer_new = StockTransfer::create($stockTtransferData);
                        $payments_old->reference_deposit = $this->refno;
                        // $payments_old->status = 'DONE';
                        $payments_old->save();
                    }
            });
            $this->reset();
        // }else{
            $this->dispatchMessage('Numéro de quitance', 'update');

        // }




    }

    // public function updateInvoice($id)
    // {
    //     $this->invoice_id = $id;
    // }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
