<?php

namespace App\Livewire\Payment;

use App\Models\Invoice;
use App\Models\MobilePaymentTransaction;
use App\Models\Payment;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Helpers\Constants;
use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Models\User;
use App\Notifications\InvoicePaid;
use App\Services\MobilePayment\MobilePaymentService;
use App\Jobs\VerifyMobilePaymentJob;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class AddPaymentModal extends Component
{
    use DispatchesMessages;

    public $payment_id;
    public $invoice_id;
    public $taxpayer_id;
    public $name;
    public $tnif;
    public $zone;
    public $invoice_no;
    public $order_no;
    public $nic;
    public $qty;
    public $bill;
    public $paid;
    public $balance;
    public $s_amount = [];
    public $amount;
    public $payment_type;
    public $reference;
    public $remaining_amount;
    public $edit_mode = false;
    public $periodicity;
    public $code;
    public $paidAndCodeArray;
    public $validCodes;
    public $edit_amount = true;
    public $notes;

    // Mobile payment properties
    public $phone_number;
    public $provider;
    public $mobile_transaction_id;
    public $mobile_payment_status;
    public $mobile_payment_message;


    protected function rules()
    {
        $rules = [
            "amount" => "required|numeric",
            "payment_type" => "required",
            'code' => [
                'nullable',
                'sometimes',
                'numeric'
            ],
            //'taxpayer_id' => 'required',
            'invoice_id' => 'required',
        ];
        if ($this->code != null) {
            $rules['code'] = Rule::in($this->validCodes);
        }
        if ($this->payment_type === PaymentTypeEnums::DIGI) {
            $rules['phone_number'] = ['required', 'string'];
            $rules['provider'] = ['required', Rule::in(Constants::MOBILE_PROVIDERS)];
        }
        return $rules;
    }
    protected $listeners = [
        'update_payment' => 'updatePayment',
        'update_payment_amount' => 'updatePaymentAmount',
        'update_local_amount' => 'updateLocalAmount',
    ];
    public function mount()
    {
    }
    public function render()
    {
        $taxpayers = Taxpayer::all();
        $invoice = Invoice::find($this->invoice_id);
        if ($invoice != null) {
            if ($invoice->type == Constants::INVOICE_TYPE_COMPTANT) {
                $this->amount = $invoice->amount;
                $this->edit_amount = false;
            }
            $this->paidAndCodeArray = Invoice::returnPaidAndSumByCode($invoice)[0];
            $this->validCodes = array_keys($this->paidAndCodeArray);
        }
        $paidAndCodeArray = $this->paidAndCodeArray;
        return view('livewire.payment.add-payment-modal', ['taxpayers' => $taxpayers, 'paidAndCodeArray' => $paidAndCodeArray, 'invoice' => $invoice]);
    }
    public function submit()
    {
        if (!auth()->user()->hasPermissionTo('peut ajouter un paiement')) {
            abort(403, 'Accès interdit');
        }
        $is_regisseur = false;
        $role = Role::where('name', "=", 'regisseur')->first();
        if ($role) {
            /**@var App\Models\User $user */
            $user = auth()->user();
            if ($user->hasRole('regisseur')) {
                $is_regisseur = true;
            }
        }
        $this->validate();
        if ($is_regisseur) {
            $this->validateOnly('reference', [
                'reference' => 'required',
            ]);
        }

        // Branch for mobile (DIGI) payments
        if ($this->payment_type === PaymentTypeEnums::DIGI) {
            $this->submitMobilePayment();
            return;
        }

        try {
            DB::transaction(function () use ($role, $is_regisseur) {
                $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);
                if (($this->paid + $this->amount) <= $invoice->amount) {
                    if ($this->code != null && $this->amount >= $this->paidAndCodeArray[$this->code]['amount']) {
                        $this->amount = $this->paidAndCodeArray[$this->code]['amount'];
                    }
                    $paymentData = [
                        'invoice_id' => $this->invoice_no,
                        'taxpayer_id' => ($this->taxpayer_id === "") ? null : $this->taxpayer_id,
                        'amount' => $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? $invoice->amount : $this->amount,
                        'payment_type' => $this->payment_type,
                        'reference' => $this->reference,
                        'code' => $this->code,
                        'description' => $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? "Avis " . $this->invoice_no : "Avis " . $this->invoice_no . ", OR " . $this->order_no,
                        'remaining_amount' => $this->bill - ($this->amount + $this->paid),
                        'user_id' => Auth::id(),
                        'invoice_type' => $invoice->type,
                        'notes' => $this->notes
                    ];
                    if ($is_regisseur) {
                        $paymentData['status'] = PaymentStatusEnums::ACCOUNTED;
                    }
                    $payments = Invoice::getCode($this->invoice_no, $this->amount, $paymentData);
                    $payment = Payment::find($this->payment_id);
                    if ($payment == null) {
                        foreach ($payments as $payment) {
                            $tempPay = Payment::create($payment);
                            if (!$is_regisseur) {
                                $users = $role->users()->get();
                                Notification::send($users, new InvoicePaid($tempPay, Auth::user()));
                            }
                        }
                    }
                    $paystatus = $this->amount + $this->paid >= $this->bill ? "PAID" : "PART PAID";
                    $data = [
                        'pay_status' => $paystatus,
                    ];
                    $this->invoice_id = $invoice->id;
                    foreach ($data as $k => $v) {
                        $invoice->$k = $v;
                    }
                    $invoice->save();
                    if ($this->edit_mode) {
                        $this->dispatchMessage('Paiement', 'update');
                    } else {
                        $this->dispatchMessage('Paiement');
                    }
                } else {
                    $this->dispatchMessage('Paiment', 'update', 'error', "Erreur lors de la mise à jour du paiement,Vous avez saisi des données de paiement incorrectes.");
                }
            });
            $this->reset();
        }catch (\Throwable $th) {

        }

    }
    public function submitMobilePayment()
    {
        try {
            $invoice = Invoice::find($this->invoice_id);
            if (!$invoice) {
                $this->dispatchMessage('Paiment', 'update', 'error', "Avis non retrouvé.");
                return;
            }

            if (($this->paid + $this->amount) > $invoice->amount) {
                $this->dispatchMessage('Paiment', 'update', 'error', "Vous avez saisi des données de paiement incorrectes.");
                return;
            }

            $effectiveAmount = $invoice->type == Constants::INVOICE_TYPE_COMPTANT ? $invoice->amount : $this->amount;

            if ($this->code != null && isset($this->paidAndCodeArray[$this->code]) && $effectiveAmount >= $this->paidAndCodeArray[$this->code]['amount']) {
                $effectiveAmount = $this->paidAndCodeArray[$this->code]['amount'];
            }

            /** @var MobilePaymentService $service */
            $service = app(MobilePaymentService::class);

            $transaction = $service->initiate([
                'invoice_id' => $invoice->id,
                'taxpayer_id' => ($this->taxpayer_id === "") ? null : $this->taxpayer_id,
                'amount' => $effectiveAmount,
                'phone_number' => $this->phone_number,
                'provider' => $this->provider,
                'meta' => [
                    'code' => $this->code,
                    'invoice_no' => $this->invoice_no,
                    'order_no' => $this->order_no,
                    'invoice_type' => $invoice->type,
                    'notes' => $this->notes,
                ],
            ]);

            $this->mobile_transaction_id = $transaction->id;

            if ($transaction->status === 'failed') {
                $this->mobile_payment_status = 'failed';
                $this->mobile_payment_message = 'L\'initiation du paiement a échoué. Veuillez réessayer.';
                return;
            }

            $this->mobile_payment_status = 'verifying';
            $this->mobile_payment_message = 'Paiement initié. Veuillez confirmer sur votre téléphone...';

            VerifyMobilePaymentJob::dispatch($transaction);

        } catch (\Throwable $th) {
            $this->mobile_payment_status = 'failed';
            $this->mobile_payment_message = 'Erreur lors de l\'initiation du paiement mobile.';
        }
    }

    public function checkMobilePaymentStatus()
    {
        if (!$this->mobile_transaction_id) {
            return;
        }

        $transaction = MobilePaymentTransaction::find($this->mobile_transaction_id);

        if (!$transaction) {
            $this->mobile_payment_status = 'failed';
            $this->mobile_payment_message = 'Transaction introuvable.';
            return;
        }

        if ($transaction->isSuccess()) {
            $this->mobile_payment_status = 'success';
            $this->mobile_payment_message = 'Paiement confirmé avec succès!';
            $this->dispatch('refreshPayments');
            return;
        }

        if ($transaction->isFailed()) {
            $this->mobile_payment_status = 'failed';
            $this->mobile_payment_message = 'Le paiement a échoué. Veuillez réessayer.';
            return;
        }

        if ($transaction->isExpired()) {
            $this->mobile_payment_status = 'expired';
            $this->mobile_payment_message = 'Le délai de paiement a expiré. Veuillez réessayer.';
            return;
        }

        $this->mobile_payment_status = 'verifying';
        $this->mobile_payment_message = 'Vérification en cours... (tentative ' . $transaction->verification_attempts . ')';
    }

    public function resetMobilePayment()
    {
        $this->mobile_transaction_id = null;
        $this->mobile_payment_status = null;
        $this->mobile_payment_message = null;
    }

    public function updatePayment($id)
    {
        try {
            $this->edit_mode = true;
            $invoice = Invoice::where('invoice_no', $id)
                ->where('validity', 'VALID')
                ->first();
            $previousRoute = Route::getRoutes()->match(Request::create(url()->previous()));
            if ($invoice == null && $previousRoute->getName() == "taxpayers.show") {
                $invoice = Invoice::where('invoice_no', $id)
                    ->OrWhere('validity', 'ARCHIVED')
                    ->where('validity', 'EXPIRED')
                    ->first();
            }
            if (!$invoice) {
                $this->dispatchMessage('Paiment', 'update', 'error', "Erreur lors de la mise à jour du paiement,avis non retrouvé.");
                return;
            }
            $this->invoice_id = $invoice->id;
            $this->taxpayer_id = $invoice->taxpayer->id ?? "";
            $this->name = $invoice->taxpayer->name ?? "";
            $this->tnif = $invoice->taxpayer->id ?? "";
            $this->zone = $invoice->taxpayer->zone->name ?? "";
            $this->invoice_no = $invoice->invoice_no;
            $this->order_no = $invoice->order_no;
            $this->nic = $invoice->nic;
            $this->qty = $invoice->qty;
            $this->bill = $invoice->amount;
            $this->paid = Payment::getPaid($invoice->invoice_no);
            $this->periodicity = ' / ' . $invoice->taxpayer->taxpayer_taxables->first()->taxable->periodicity;
            $this->balance = $this->bill - $this->paid;
        }catch (\Throwable $th) {}

    }
    public function updatePaymentAmount($code)
    {
        if ($code) {
            $this->code = $code;
            $this->amount = $this->paidAndCodeArray[$code]['amount'];
        }
    }
    public function updateLocalPayment()
    {
    }
    #[On('updateSharedInvoiceId')]
    public function updateSharedTaxpayerId($id)
    {
        $this->updatePayment($id);
    }
    #[On('delete_payment')]
    public function deletePayment($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $invoice = Invoice::where('invoice_no', $payment?->invoice_id)
                ->where('validity', 'VALID')
                ->first();
            Payment::destroy($id);
            if ($invoice) {
                $paid = Payment::getPaid($invoice?->invoice_no);
                $paystatus = $paid == 0 ? PaymentStatusEnums::PENDING : "PART PAID";
                $invoice->pay_status = $paystatus;
                $invoice->save();
                $this->dispatchMessage('Paiement', 'delete');
            }
        }
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
