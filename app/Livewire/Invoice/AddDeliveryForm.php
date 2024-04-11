<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddDeliveryForm extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;
    public $invoice_id;

    //public $delivery;
    public $delivery_date;
    public $delivery_to;

    public $edit_mode = false;

    protected $rules = [
        //"delivery" =>"required",
        "delivery_date" =>"required|string",
        "delivery_to"=>"required|string",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_status' => 'updateStatus',
        //'add_invoice' => 'addInvoice',
    ];
    public function render()
    {
        return view('livewire.invoice.add-delivery-form');
    }

    public function submit()
    {
        //dd($this->status);

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {

            // Prepare data for Invoice
            $data = [
                'delivery' => "DELIVERED",
                'delivery_date' => $this->delivery_date,
                "delivery_to"=> $this->delivery_to

            ];

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);

            $this->invoice_id = $invoice->id;

            foreach ($data as $k => $v) {
                $invoice->$k = $v;
            }
            $invoice->save();
            $this->dispatchMessage('Avis', 'update');
        });

        // Reset form fields after successful submission
        $this->reset();
    }

// public function updateInvoice($id)
// {
//     $this->edit_mode = true;

//     $this->invoice_id = $invoice->id;
//     $this->tnif = $invoice->taxpayer->tnif;
//     $this->zone = $invoice->taxpayer->zone_id;
// }

    public function updateStatus($id)
    {

        $invoice = Invoice::find($id);

        $this->invoice_id = $invoice->id;
        $this->status = $invoice->status;

        //$this->$invoice = $invoice;

        //dd($this->invoice_id);

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}

