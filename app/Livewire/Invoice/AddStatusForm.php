<?php

namespace App\Livewire\Invoice;

use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TaxpayerTaxable;
use App\Notifications\InvoiceAccepted;
use App\Notifications\InvoiceApproved;
use App\Notifications\InvoiceCreated;
use App\Notifications\InvoiceRejected;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AddStatusForm extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;
    public $invoice_id;

    public $status;

    public $edit_mode = false;

    protected $rules = [
        "status" => "required",
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
            if ($this->status == InvoiceStatusEnums::APPROVED &&  $invoice->reduce_amount != '') {
                //Todo make cascade reduction
                $description_str = $invoice->reduce_amount == $invoice->amount ? Constants::ANNULATION : Constants::REDUCTION;
                $paymentData = [
                    'invoice_id' => $invoice->invoice_no,
                    'taxpayer_id' =>  $invoice->taxpayer_id,
                    'amount' => $invoice->reduce_amount,
                    'description' => $description_str,
                    'user_id' =>  Auth::id(),
                    'reference' =>  $description_str,
                    'invoice_type' => $description_str,
                    'status' => $description_str,
                    'payment_type' => $description_str,

                ];
                $payments = Invoice::getCode($invoice->invoice_no, $invoice->reduce_amount, $paymentData);
                foreach ($payments as $payment) {
                    Payment::create($payment);
                }
                if ($invoice->reduce_amount == $invoice->amount) {
                    $invoice->pay_status = "PAID";
                } else {
                    $invoice->pay_status = "PART PAID";
                }
                $invoice->status = InvoiceStatusEnums::APPROVED_CANCELLATION;
            }

            $invoice->save();
            $this->dispatchMessage('Avis', 'update');



           // agenst
            if ($this->status == InvoiceStatusEnums::PENDING) {
                $role = Role::where('name', 'agent_recette')->first();
                if ($role) {
                    $users = $role->users()->get();
                    Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_recette"));
                }
                $role_assiette = Role::where('name', 'agent_assiette')->first();
                if($role_assiette){
                    $users =  $role_assiette->users()->get();
                    Notification::send($users, new InvoiceAccepted($invoice, Auth::user(), "agent_assiette"));
                }
            } elseif ($this->status == InvoiceStatusEnums::APPROVED) {
                $role = Role::where('name', 'regisseur')->first();
                if ($role) {
                    $users = $role->users()->get();
                    Notification::send($users, new InvoiceApproved($invoice, Auth::user(), "regisseur"));
                }
            } elseif ($this->status == InvoiceStatusEnums::REJECTED_BY_OR) {
                $role = Role::where('name', 'agent_assiette')->first();
                if ($role) {
                    $users = $role->users()->get();
                    Notification::send($users, new InvoiceRejected($invoice, Auth::user(), "agent_assiette"));
                }
                foreach ( $invoice->taxpayer_taxables as $taxpayerTaxable){
                    $taxpayerTaxable->billable ='0';
                    $taxpayerTaxable->bill_status ="NOT BILLED";
                    $taxpayerTaxable->invoice_id = null;
                    $taxpayerTaxable->save();
                }
            }
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
