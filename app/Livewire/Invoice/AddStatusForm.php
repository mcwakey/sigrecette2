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

            //  comment after
            //$data = ['status' => $this->status];

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);


            $this->invoice_id = $invoice->id;

            //foreach ($data as $k => $v) {$invoice->$k = $v;}
            //dd($invoice->getAvailableTransitions());
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
                $this->status = InvoiceStatusEnums::APPROVED_CANCELLATION;
            }

            $invoice->save();

            switch($this->status){
                case InvoiceStatusEnums::ACCEPTED:
                    $invoice->submitToState("submit_for_accepted");
                    break;
                case InvoiceStatusEnums::REJECTED_BY_OR:
                    $invoice->submitToState("submit_for_reject_by_ord");

                    break;
                case  InvoiceStatusEnums::PENDING:
                    $invoice->submitToState("submit_for_pending");
                    break;
                case InvoiceStatusEnums::REJECTED:
                    $invoice->submitToState("submit_for_rejected");
                    break;
                case   InvoiceStatusEnums::APPROVED:
                case     InvoiceStatusEnums::APPROVED_CANCELLATION:
                        if($invoice->type==Constants::INVOICE_TYPE_COMPTANT){
                            $invoice->delivery="DELIVERED";
                            $invoice->delivery_date=now();
                            $invoice->save();
                        }
                        if($this->status==InvoiceStatusEnums::APPROVED){
                            $invoice->submitToState( "submit_for_approved");
                        }else{
                            $invoice->submitToState("submit_for_approved_cancellation");
                        }
                    break;
                case InvoiceStatusEnums::CANCELED:
                    //dump(InvoiceStatusEnums::CANCELED);
                    break;
                case InvoiceStatusEnums::REDUCED:
                    //dump(InvoiceStatusEnums::REDUCED);
                    break;

                default :
                    // dump($place);
            }
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
