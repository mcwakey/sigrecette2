<?php

namespace App\Livewire\Invoice;

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

    protected $rules = [
        "orderno" => "required",
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

    public function submit()
    {
        $this->validate();

        DB::transaction(function () {

            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);


            $this->invoice_id = $invoice->id;
            $invoice->order_no =$this->orderno;


            $invoice->submitToState("submit_for_pending");
            $invoice->save();
            $this->dispatchMessage('Avis', 'update');
        });

        // Reset form fields after successful submission
        $this->reset();
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
