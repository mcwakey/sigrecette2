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
    use DispatchesMessages;

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
        $user = auth()->user();
        if (!$user->hasRole('regisseur')) {
            $this->dispatchMessage('Quitance de paiement', 'update', 'error', "Action non authorize");
            $this->reset();
            abort(403, 'Accès interdit');
            return;
        }
        try {
            DB::transaction(function () {
                $payments_olds = Payment::where('status', PaymentStatusEnums::DONE)->where('status', PaymentStatusEnums::CANCELED)->where('reference_deposit', null)->get();
                $payments_olds = Payment::where(function ($query) {
                    $query->where('status', PaymentStatusEnums::DONE)->orWhere('status', PaymentStatusEnums::CANCELED);
                })->where('reference_deposit', null)->get();
                foreach ($payments_olds as $payments_old) {
                    $payments_old->reference_deposit = $this->refno;
                    $payments_old->save();
                }
            });
            $this->reset();
            $this->dispatchMessage('Numéro de quitance', 'update');
        }catch (\Exception $e) {
            $this->dispatchMessage('Quitance de paiement', 'update', 'error', "Action non enregistrer");
        }
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
