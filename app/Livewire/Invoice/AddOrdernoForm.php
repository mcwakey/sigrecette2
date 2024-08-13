<?php

namespace App\Livewire\Invoice;

use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use Livewire\Component;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvoiceAccepted;
use Illuminate\Support\Facades\Notification;


class AddOrdernoForm extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;

    public $invoice_id;

    public $orderno;

    public $edit_mode = false;
    private $error_message;
    protected $rules = [
        "orderno" => "required|string",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_invoice' => 'updateInvoice',
        //'add_invoice' => 'addInvoice',
    ];
    public function render()
    {
        return view('livewire.invoice.add-orderno-form');
    }
    public function validateData(){
        $this->validate();
        //$invoice = Invoice::find($this->invoice_id);
        //if($invoice && $invoice->reduce_amount == ''){
        //    if ($invoice) {
        //        if($invoice->type ==Constants::INVOICE_TYPE_TITRE && $invoice->edition_state != "PRINT"){

         //           $this->addError('orderno', $this->error_message);}}}

    }
    public function submit()
    {
        $this->validateData();
        if ($this->getErrorBag()->isEmpty()) {
            DB::transaction(function () {

                $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);


                $this->invoice_id = $invoice->id;
                $invoice->order_no =$this->orderno;


                $invoice->submitToState("submit_for_pending");
                $invoice->save();
                $this->dispatchMessage('Avis', 'update');
            });
            $this->reset();
        }else{
            $this->dispatchMessage('Avis', 'update', 'error',$this->error_message);

        }




    }

    public function updateInvoice($id)
    {
        $this->invoice_id = $id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
