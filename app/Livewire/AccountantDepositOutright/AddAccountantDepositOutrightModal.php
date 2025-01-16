<?php
namespace App\Livewire\AccountantDepositOutright;
use App\Helpers\Constants;
use App\Models\Payment;
use App\Models\StockRequest;
use App\Models\StockTransfer;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\TaxpayerTaxable;
use App\Models\User;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
class AddAccountantDepositOutrightModal extends Component
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
    ];
    protected $listeners = [
        'change_qty' => 'changeQty',
        'load_drop' => 'loadDrop',
        'add_accountant_deposit_outright' => 'addAccountantDepositOutright',
        'update_transfer' => 'updateTransfer',
        'add_deposit' => 'addDeposit',
    ];
    public function render()
    {
        $this->user_id = Auth::id();
        $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'collecteur')
            ->get();
        return view('livewire.accountant_deposit_outright.add-accountant-deposit-outright-modal', ['collectors' => $collectors]);
    }
    public function updatedTaxableId($value)
    {
        if ($this->deposit_mode) {
            $taxables = Taxable::select('taxables.*', 'trans_no', 'trans_id', 'last_no', 'stock_transfers.id AS stock_transfers_id')
                ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                ->where('taxables.id', $value)
                ->orderBy('stock_transfers.id', 'DESC')
                ->get();
            $this->trans_no = $taxables->first()->trans_no ?? "";
            $this->trans_id = $taxables->first()->trans_id ?? "";
            $this->start_no = $taxables->first()->last_no ?? "";
            $this->stock_request_id = $taxables->first()->stock_transfers_id ?? "";
            $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();
        } else {
            $taxables = Taxable::select('taxables.*', 'req_no', 'last_no', 'stock_requests.id AS stock_request_id')
                ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                ->where('taxables.id', $value)
                ->get();
            $this->trans_no = $taxables->first()->req_no ?? "";
            $this->start_no = $taxables->first()->last_no ?? "";
            $this->stock_request_id = $taxables->first()->stock_request_id ?? "";
            $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        }
    }
    public function updatedCollectorId($value)
    {
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        if ($this->deposit_mode) {
            $this->taxables = Taxable::select('taxables.*')
                ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                ->where('tax_label_id', null)
                ->where('type', 'ACTIVE')
                ->where('to_user_id', $this->collector_id)
                ->distinct()
                ->get();
        } else {
            $this->taxables = Taxable::select('taxables.*')
                ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                ->where('tax_label_id', null)
                ->get();
        }
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        if ($this->edit_mode == true) {
            $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        }
    }
    public function updatedEndNo($value)
    {
        if ($this->start_no !== "" && $this->end_no !== "") {
            $this->qty = $this->end_no - $this->start_no + 1;
        }
    }
    public function updatedStartNo($value)
    {
        if ($this->start_no !== "" && $this->end_no !== "") {
            $this->qty = $this->end_no - $this->start_no + 1;
        }
    }
    public function updatedTransNo($value)
    {
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        $user = auth()->user();
        if (!$user->hasRole('regisseur')) {
            $this->dispatchMessage('Versement', 'update', 'error',"Action non authorize");
            $this->reset();
            abort(403, 'Accès interdit');
            return;
        }
        DB::transaction(function () {
            $paymentData = [
                'deposit' => $this->paid,
                'status' => "DONE",
                'payment_type' => $this->payment_type,
                'description' => "Versement",
                'user_id' => Auth::id(),
                'r_user_id' => null,
                'reference' => $this->reference,
            ];
            Payment::create($paymentData);
            $payments_olds = Payment::where('invoice_type', Constants::INVOICE_TYPE_COMPTANT)->where('status', "APROVED")->get();
            foreach ($payments_olds as $payments_old) {
                $payments_old->status = 'DONE';
                $payments_old->save();
            }
            $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
        }
        );
        $this->end_no = "";
        $this->qty = "";
    }
    public function addAccountantDepositOutright($type)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        $total_amount = Payment::selectRaw('SUM(amount) AS amount')->where('invoice_type', $type)->where('status', "APROVED")->groupBy('invoice_type')->first();
        $this->total_amount = $total_amount->amount ?? '';
        $this->paid = $this->total_amount;
        $this->edit_mode = false;
        $this->deposit_mode = false;
    }
    public function addDeposit($id)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        $this->deposit_mode = true;
        $this->edit_mode = false;
    }
    public function updateTransfer($id)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        $this->edit_mode = true;
        $this->deposit_mode = false;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
