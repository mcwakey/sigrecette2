<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddStatusForm extends Component
{
    //use WithFileUploads;

    public $invoice_id;

    public $status;

    public $edit_mode = false;

    protected $rules = [
        "status" =>"required",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_status' => 'updateStatus',
        //'add_invoice' => 'addInvoice',
    ];
    public function render()
    {
        return view('livewire.invoice.add-status-form');
    }

    public function submit()
    {
        //dd($this->status);

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {

            // Prepare data for Invoice
            $data = [
                'status' => $this->status,
            ];

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);

            
            $this->invoice_id = $invoice->id;

            foreach ($data as $k => $v) {
                $invoice->$k = $v;
            }
            $invoice->save();
                $this->dispatch('success', __('Invoice updated'));
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

