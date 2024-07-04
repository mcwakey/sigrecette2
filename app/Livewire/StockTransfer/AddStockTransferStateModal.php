<?php

namespace App\Livewire\StockTransfer;

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
use PhpParser\Node\Stmt\Return_;

class AddStockTransferStateModal extends Component
{
    use WithFileUploads;

    public $stock_transfer_id;
    public $user_id;
    public $collector_id;
    public $tariff;
    public $qty=0;
    public $start_no;
    public $end_no;
    public $trans_no;
    public $trans_id;
    public $code;
    public $total;
    //public $tariff;

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
    public $remaining_qty=0;
    public $option_calculus;
    public $period_from;
    public $period_to;
    protected function rules()
    {
        $rules = [
            'collector_id' => 'required',
            //'code' => 'required',
            //'taxlabel_id' => 'required',
        ];

        return $rules;
    }

    protected $listeners = [
        'update_transfer' => 'updateTransfer',
    ];
    public function mount($id){
        $this->collector_id =$id;
    }
    public function render()
    {
        $this->user_id = Auth::id();



        $stock_requests= StockRequest::where('req_type','DEMANDE')->where('type','ACTIVE')->get();

       // dd($taxlabel_list);
        return view('livewire.stock_transfer.add-stock-transfer-state-modal', compact('stock_requests'));
    }


    public function submit()
    {
        $this->validate();
        DB::transaction(function () {

            $stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
            if ($this->edit_mode) {
                $total_sold = 0;
                if ($stock_transfers){

                    foreach ($stock_transfers as $stock_transfer) {

                        $stockTtransferData = [
                            'trans_no' => $stock_transfer->trans_no,
                            'trans_id' => $stock_transfer->trans_id,
                            'period_from' => $stock_transfer->period_from,
                            'period_to' =>  $stock_transfer->period_to,
                            //'qty' => $stock_transfer->qty,
                            'type' => 'ARCHIVED',
                            'end_no' => $stock_transfer->end_no,
                            'taxable_id' => $stock_transfer->taxable_id,
                            'trans_type' => 'RENDU',
                            'by_user_id' => $this->user_id,
                            'to_user_id' => $stock_transfer->to_user_id,
                        ];

                        $stock_transfer_new = StockTransfer::create($stockTtransferData);

                        // $stock_transfer_old = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
                        $stock_transfer_olds = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('taxable_id', $stock_transfer->taxable_id)->where('to_user_id', $this->collector_id)->orderBy('end_no', 'DESC')->get();

                        // dd($stock_transfer->end_no, $stock_transfer_olds->first()->end_no);
                        //dd($stock_transfer_olds->first()->last_no);

                        foreach ($stock_transfer_olds as $stock_transfer_old) {
                            $stock_transfer_new->qty += $stock_transfer_old->qty;
                            $stock_transfer_old->type = "ARCHIVED";
                            $stock_transfer_old->save();

                            $total_sold += $stock_transfer_old->qty * $stock_transfer_old->taxable->tariff;
                        }

                        $stock_transfer->type = 'ARCHIVED';
                        $stock_transfer->save();


                        $stock_transfer_new->qty = $stock_transfer->qty - $stock_transfer_new->qty;
                        if ($stock_transfer_olds->first()){
                            $stock_transfer_new->start_no = $stock_transfer_olds->first()->last_no ?? $stock_transfer->start_no ;
                        }else{
                            // $stock_transfer_new->qty = $stock_transfer->qty;
                            $stock_transfer_new->start_no = $stock_transfer->start_no ;
                            $stock_transfer_new->last_no = $stock_transfer->start_no;
                        }
                        $stock_transfer_new->save();

                        //dd($stock_transfer->type);
                    }

                    $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
                }

            }

                $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

                if ($this->deposit_mode) {
                    $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
                }
        });

        // Reset the form fields after successful submission
        //$this->reset();
        //$this->collector_id = "";
        $this->taxlabel_id = null;
        $this->code = null;
        $this->taxable_id = null;
       // $this->trans_no = null;
        $this->end_no = null;
        $this->start_no = null;
        $this->qty = null;
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        TaxpayerTaxable::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Asset successfully deleted');
    }



    public function updateTransfer($id)
    {
        $this->collector_id = $id;
        $this->taxlabel_id = null;
        $this->code = null;
        $this->taxable_id = null;
        $this->trans_no = null;
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        $this->edit_mode = true;
        $this->deposit_mode = false;

        if ($this->edit_mode == true) {
            //$this->stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
           $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
       }

        // dd($this->edit_mode,$this->deposit_mode);

        // $taxpayer = Taxpayer::find($id);
        // $stock_transfer = StockTransfer::find($id);
        // //dd($stock_transfer->taxable->tax_label);

        // $this->stock_transfer_id = $id;
        // $this->trans_no = $stock_transfer->trans_no;

        // $this->taxlabel_idd = $stock_transfer->taxable->tax_label->id;
        // $this->taxlabel_name = $stock_transfer->taxable->tax_label->name;

        // $this->taxable_idd = $stock_transfer->taxable_id;
        // $this->taxable_name = $stock_transfer->taxable->name;

        // $this->collector_idd = $stock_transfer->to_user_id;
        // //dd($stock_transfer);
        // $this->collector_name = $stock_transfer->user->name;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
