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
        //dd($this->validate());

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {

            // Prepare data for Invoice
            $data = [
                'order_no' => $this->orderno,
            ];

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);


            $this->invoice_id = $invoice->id;

            if ($invoice->order_no === null) {
                $role = Role::where('name', 'agent_delegation_du_receveur')->first();

                if ($role) {
                    $users = $role->users()->get();
                    Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_delegation_du_receveur"));
                }
            }

            foreach ($data as $k => $v) {
                $invoice->$k = $v;
            }
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
