<?php

namespace App\Livewire\AccountantDeposit;

use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Helpers\Constants;
use App\Models\Payment;
use App\Models\StockRequest;
use App\Models\StockTransfer;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\TaxpayerTaxable;
use App\Models\User;
use App\Models\Year;
use App\Traits\DispatchesMessages;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddAccountantDepositModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $stock_transfer_id;
    public $user_id;
    public $collector_id;
    public $tariff;
    public $qty;
    public $start_no;
    public $end_no;
    public $trans_no;
    public $trans_id;
    public $code;
    public $taxable_id;
    public $taxlabel_id;
    public $taxables = [];
    public $taxlabels = [];
    public $stock_transfers = [];
    public $taxable_name;
    public $taxable_idd;
    public $taxlabel_name;
    public $taxlabel_idd;
    public $collector_name;
    public $collector_idd;
    public $stock_request_id;
    public $edit_mode;
    public $deposit_mode;
    public $option_calculus;
    public $total_amount;
    public $paid;
    public $payment_type;
    public $reference;
    protected $rules = [
        'total_amount' => 'required',
        'payment_type' => 'required',
    ];
    protected $listeners = [
        'change_qty' => 'changeQty',
        'load_drop' => 'loadDrop',
        'add_accountant_deposit' => 'addAccountantDeposit',
        'update_transfer' => 'updateTransfer',
        'add_deposit' => 'addDeposit',
    ];
    public function render()
    {
        $this->user_id = Auth::id();
        return view('livewire.accountant_deposit.add-accountant-deposit-modal');
    }
    public function submit()
    {
        $this->validate();
        $user = auth()->user();
        if (!$user->hasRole('regisseur')) {
            $this->dispatchMessage('Compatilite', 'update', 'error', "Action non authorize");
            $this->reset();
            abort(403, 'Accès interdit');
        }
        DB::transaction(function () {
            $paymentData = [
                'deposit' => $this->paid,
                'status' => 'DONE',
                'payment_type' => $this->payment_type,
                'description' => "Versement",
                'user_id' => Auth::id(),
                'r_user_id' => null,
                'reference' => $this->reference,
                'invoice_type' => 'VERSEMENT',
            ];
            Payment::create($paymentData);
            $payments_olds = Payment::whereIn('invoice_type', [Constants::INVOICE_TYPE_COMPTANT, Constants::INVOICE_TYPE_TITRE])->where('status', PaymentStatusEnums::ACCOUNTED)->get();
            foreach ($payments_olds as $payments_old) {
                $payments_old->reference_deposit = $this->reference;
                $payments_old->status = 'DONE';
                $payments_old->save();
            }
            $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
        });
        $this->end_no = "";
        $this->qty = "";
    }
    public function addAccountantDeposit($type)
    {
        $year = Year::getActiveYear()?->name;
        $s_date = Carbon::parse("{$year}-01-01 00:00:00");
        $e_date = Carbon::parse("{$year}-12-31 23:59:59");
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";




        $this->total_amount = Payment::selectRaw('SUM(amount) AS amount')
            ->whereBetween('created_at', [$s_date, $e_date])
            ->where('status', "ACCOUNTED")
            ->first()
            ->amount ?? 0;
        ;
        $this->paid = $this->total_amount;
        $this->edit_mode = false;
        $this->deposit_mode = false;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
