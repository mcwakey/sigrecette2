<?php

namespace App\Livewire\Invoice;

use App\Enums\InvoicePayStatusEnums;
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
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AddStatusForm extends Component
{
    use DispatchesMessages;

    public $invoice_id;
    public $status;
    public $type;
    public $edit_mode = false;
    public $reason_for_reject;
    public $bypass_print_check = false;
    public function rules()
    {
        return [
            'status' => ['required', 'string', Rule::in(
                InvoiceStatusEnums::ACCEPTED->value,
                InvoiceStatusEnums::REJECTED_BY_OR->value,
                InvoiceStatusEnums::PENDING->value,
                InvoiceStatusEnums::REJECTED->value,
                InvoiceStatusEnums::APPROVED->value,
                InvoiceStatusEnums::APPROVED_CANCELLATION->value,
                InvoiceStatusEnums::CANCELED->value,
                InvoiceStatusEnums::REDUCED->value
            )],
        ];
    }
    private $error_message;
    protected $listeners = [
        'update_status' => 'updateStatus',
    ];
    public function render()
    {
        return view('livewire.invoice.add-status-form');
    }
    public function validateData()
    {
        $this->validate();
        $invoice = Invoice::find($this->invoice_id);
        if (!$this->bypass_print_check && $invoice && $invoice->reduce_amount == '' && ($this->status == InvoiceStatusEnums::APPROVED->value || $this->status == InvoiceStatusEnums::APPROVED_CANCELLATION->value || $this->status == InvoiceStatusEnums::REJECTED->value) && ($invoice->type == Constants::INVOICE_TYPE_TITRE && $invoice->edition_state != "bPRINT")) {
            if (!$invoice->edition_state) {
                $this->error_message = "Veuillez au préalable imprimer l'avis.";
            } elseif ($invoice->edition_state == "PRINT") {
                $this->error_message = "Veuillez au préalable ajouter l'avis à un bordereau.";
            } else {
                $this->error_message = "Veuillez au préalable imprimer l'avis.";
            }
            $this->addError('status', $this->error_message);
        }
    }
    public function submit()
    {
        $this->validateData();
        try {
            if ($this->getErrorBag()->isEmpty()) {
                DB::transaction(function () {
                    $invoice = Invoice::find($this->invoice_id);
                    $this->invoice_id = $invoice->id;
                    if ($invoice->type == Constants::INVOICE_TYPE_TITRE && $this->status == InvoiceStatusEnums::REJECTED->value) {
                        $invoice->reason_for_reject = $this->reason_for_reject;
                    }
                    if ($this->status == InvoiceStatusEnums::APPROVED->value && $invoice->reduce_amount != '') {
                        //Todo make cascade reduction
                        $description_str = $invoice->reduce_amount == $invoice->amount ? Constants::ANNULATION : Constants::REDUCTION;
                        $paymentData = [
                            'invoice_id' => $invoice->invoice_no,
                            'taxpayer_id' => $invoice->taxpayer_id,
                            'amount' => $invoice->reduce_amount,
                            'description' => $description_str,
                            'user_id' => Auth::id(),
                            'reference' => $description_str,
                            'invoice_type' => $description_str,
                            'status' => $description_str,
                            'payment_type' => $description_str,
                            'code' => null
                        ];
                        $payments = Invoice::getCode($invoice->invoice_no, $invoice->reduce_amount, $paymentData);
                        foreach ($payments as $payment) {
                            Payment::create($payment);
                        }
                        $invoice->pay_status = $invoice->reduce_amount == $invoice->amount ? InvoicePayStatusEnums::PAID->value : InvoicePayStatusEnums::PART_PAID->value;
                        $this->status = InvoiceStatusEnums::APPROVED_CANCELLATION->value;
                    }
                    $invoice->save();
                    switch ($this->status) {
                        case InvoiceStatusEnums::ACCEPTED->value:
                            $invoice->submitToState("submit_for_accepted");
                            break;
                        case InvoiceStatusEnums::REJECTED_BY_OR->value:
                            $invoice->submitToState("submit_for_reject_by_ord");
                            break;
                        case InvoiceStatusEnums::PENDING->value:
                            $invoice->submitToState("submit_for_pending");
                            break;
                        case InvoiceStatusEnums::REJECTED->value:
                            $invoice->submitToState("submit_for_rejected");
                            break;
                        case InvoiceStatusEnums::APPROVED->value:
                        case InvoiceStatusEnums::APPROVED_CANCELLATION->value:
                            if ($invoice->type == Constants::INVOICE_TYPE_COMPTANT) {
                                $invoice->setDeliveryToNow($this->status);
                                $invoice->save();
                            } elseif ($this->status == InvoiceStatusEnums::APPROVED->value) {
                                $invoice->submitToState("submit_for_approved");
                            } else {
                                $invoice->submitToState("submit_for_approved_cancellation");
                            }
                            break;
                        case InvoiceStatusEnums::CANCELED->value:
                        case InvoiceStatusEnums::REDUCED->value:
                            break;
                        default:
                    }
                    $this->dispatchMessage('Avis', 'update');
                });
                $this->reset();
            } else {
                $this->dispatchMessage('Avis', 'update', 'error', $this->error_message);
            }
        }catch (\Throwable $th) {}

    }
    public function updateStatus($id)
    {
        $invoice = Invoice::find($id);
        $this->invoice_id = $invoice->id;
        $this->status = $invoice->status;
        $this->type = $invoice->type;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
