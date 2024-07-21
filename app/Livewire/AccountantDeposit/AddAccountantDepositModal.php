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
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddAccountantDepositModal extends Component
{
    use WithFileUploads;

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


    public $taxables=[];
    public $taxlabels=[];
    public $stock_transfers=[];

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
        'delete_taxpayer' => 'deleteUser',
        'change_qty' => 'changeQty',
        'load_drop' => 'loadDrop',
        'add_accountant_deposit' => 'addAccountantDeposit',
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

        return view('livewire.accountant_deposit.add-accountant-deposit-modal', compact('collectors'));
    }



    public function submit()
    {
        // Validate the form input data
        $this->validate();

        //dd($this->collector_id);

        DB::transaction(function () {

        //   $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('unit', $this->taxlabel_id)->where('to_user_id', $this->collector_id)->get();
        //         $stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_type', 'RECU')->where('unit', $this->taxlabel_id)->where('to_user_id', $this->collector_id)->get();

        //         dd($this->edit_mode, $stock_transfers,$this->stock_transfers);
            //$stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

                // $total_sold = 0;
                // if ($stock_transfers){

                    $paymentData = [
                        'deposit' => $this->paid,
                        'status' => 'DONE',
                        'payment_type' => $this->payment_type,
                        'description' => "Versement",
                        'user_id' => Auth::id(),
                        'r_user_id' => null,
                        'reference_deposit' => $this->reference,
                    ];

                    //dd($paymentData);

                    Payment::create($paymentData);

                    $payments_olds = Payment::whereIn('invoice_type', [Constants::INVOICE_TYPE_COMPTANT,Constants::INVOICE_TYPE_TITRE])->where('status',PaymentStatusEnums::ACCOUNTED )->get();
                     foreach ($payments_olds as $payments_old) {

                        // $paymentsData = [
                        //     'status' => 'DONE',
                        //     // 'trans_id' => $stock_transfer->trans_id,
                        //     // //'qty' => $stock_transfer->qty,
                        //     // 'type' => 'ARCHIVED',
                        //     // 'end_no' => $stock_transfer->end_no,
                        //     // 'taxable_id' => $stock_transfer->taxable_id,
                        //     // 'trans_type' => 'RENDU',
                        //     // 'payment_id' => $payment->id,
                        //     // 'by_user_id' => $this->user_id,
                        //     // 'to_user_id' => $stock_transfer->to_user_id,
                        // ];

                        // $stock_transfer_new = StockTransfer::create($stockTtransferData);
                        $payments_old->reference_deposit = $this->reference;
                        $payments_old->status = 'DONE';
                        $payments_old->save();
                    }

                    //     // $stock_transfer_old = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
                    //     $stock_transfer_olds = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('taxable_id', $stock_transfer->taxable_id)->where('to_user_id', $this->collector_id)->orderBy('end_no', 'DESC')->get();

                    //     // dd($stock_transfer->end_no, $stock_transfer_olds->first()->end_no);
                    //     //dd($stock_transfer_olds->first()->last_no);
                    //     if ($stock_transfer_olds->first()){
                    //         $stock_transfer_new->qty = $stock_transfer->end_no - $stock_transfer_olds->first()->end_no;
                    //         $stock_transfer_new->start_no = $stock_transfer_olds->first()->last_no ?? $stock_transfer->start_no;
                    //     }else{
                    //         $stock_transfer_new->qty = $stock_transfer->qty;
                    //         $stock_transfer_new->start_no = $stock_transfer->start_no;
                    //         $stock_transfer_new->last_no = $stock_transfer->start_no;
                    //     }
                    //     $stock_transfer_new->save();

                    //     foreach ($stock_transfer_olds as $stock_transfer_old) {
                    //         $stock_transfer_old->type = "ARCHIVED";
                    //         $stock_transfer_old->save();

                    //         $total_sold += $stock_transfer_old->qty * $stock_transfer_old->taxable->tariff;
                    //     }

                    //     $stock_transfer->type = 'ARCHIVED';
                    //     $stock_transfer->save();

                    //     //dd($stock_transfer->type);
                    // }

                    // $payment->amount = $total_sold;
                    // $payment->save();

                    $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
                }

            // } else {

            //     // Prepare the data for creating a new Taxable
            //     $data = [
            //         'trans_no' => $this->trans_no,
            //         'qty' => $this->qty,
            //         'start_no' => $this->start_no,
            //         'end_no' => $this->end_no,
            //         'last_no' => $this->start_no,
            //         'taxable_id' => $this->taxable_id,
            //         'trans_type' => 'RECU',
            //         'by_user_id' => $this->user_id,
            //         'to_user_id' => $this->collector_id,
            //     ];

            //     //dd($this->trans_id);

            //     if ($this->deposit_mode) {
            //         $data['trans_type'] = 'VENDU';
            //         $data['last_no'] = $this->end_no + 1;
            //         $data['trans_id'] = $this->trans_id;
            //         $data['code'] = $this->code;

            //         $this->start_no = $this->end_no + 1;
            //     }

            //     $stock_transfer = StockTransfer::create($data);

            //     if (!$this->deposit_mode) {
            //         $stock_transfer->trans_id = $stock_transfer->id;
            //         $stock_transfer->save();
            //     }

            //     if (!$this->deposit_mode) {
            //         $stock_request = StockRequest::find($this->stock_request_id);

            //         $stock_request->last_no = $this->end_no + 1;
            //         $stock_request->save();

            //         $this->start_no = $this->end_no + 1;
            //     }
            //     //dd($stock_request);
            //     //$this->stock_request_id

            //     // if ($this->edit_mode) {
            //     //     // Emit a success event with a message
            //     //     $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
            //     // }
            // }

            //     $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

            //     if ($this->deposit_mode) {
            //         $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
                // }
        // }
    );

        // Reset the form fields after successful submission
        //$this->reset();
        //$this->collector_id = "";
        //$this->taxlabel_id = "";
        //$this->taxable_id = "";
        //$this->trans_no = "";
        $this->end_no = "";
        $this->qty = "";
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        TaxpayerTaxable::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Asset successfully deleted');
    }

    public function addAccountantDeposit($type)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();


        $total_amount = Payment::selectRaw('SUM(amount) AS amount')->where('status', "ACCOUNTED")->groupBy('status')->first();
        $this->total_amount = $total_amount->amount ?? '';
        $this->paid = $this->total_amount;
        //dd($this->total_amount);
        // $this->stock_transfer_id = '';
        // $this->trans_no = '';
        $this->edit_mode = false;
        $this->deposit_mode = false;

        //   dd($this->edit_mode,$this->deposit_mode);
    }



    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
