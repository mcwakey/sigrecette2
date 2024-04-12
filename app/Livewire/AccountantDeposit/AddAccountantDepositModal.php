<?php

namespace App\Livewire\AccountantDeposit;

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

    protected $rules = [
        'collector_id' => 'required',
    ];

    protected $listeners = [
        'delete_taxpayer' => 'deleteUser',
        'change_qty' => 'changeQty',
        'load_drop' => 'loadDrop',
        'add_accountant_deposit' => 'addTrnasfer',
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

    // public function updatedTaxlabelId($value)
    // {
        // $this->taxable_id = "";
        // $this->trans_no = "";

        // if ($this->deposit_mode) {
        //     $this->taxables = Taxable::select('taxables.*')
        //                                 ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                                 ->where('tax_label_id', null)
        //                                 ->where('unit', $value)
        //                                 ->where('type', 'ACTIVE')
        //                                 ->where('to_user_id', $this->collector_id)
        //                                 ->distinct()
        //                                 ->get();
        // }else{
        //     $this->taxables = Taxable::select('taxables.*')
        //                                 ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
        //                                 ->where('tax_label_id', null)
        //                                 ->where('unit', $value)
        //                                 ->get();

        // }

        // // $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();
        // $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        // if ($this->edit_mode == true) {
        //      //$this->stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        //     $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();
        // }
    // }

    public function updatedTaxableId($value)
    {
        // Debugging to ensure $value is valid
        //dd($value);

        // Assuming $value is valid, fetch taxables based on tax label ID
        // if ($this->deposit_mode == false) {

        if ($this->deposit_mode) {
            $taxables = Taxable::select('taxables.*', 'trans_no', 'trans_id', 'last_no', 'stock_transfers.id AS stock_transfers_id')
                                    ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                                    ->where('taxables.id', $value)
                                    ->orderBy('stock_transfers.id', 'DESC')
                                    ->get();

            $this->trans_no = $taxables->first()->trans_no ?? "";
            $this->trans_id = $taxables->first()->trans_id ?? "";

            //dd($taxables->first());

            $this->start_no = $taxables->first()->last_no ?? "";
            $this->stock_request_id = $taxables->first()->stock_transfers_id ?? "";
            
            // $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
            $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();
       
        }else{
            $taxables = Taxable::select('taxables.*', 'req_no', 'last_no', 'stock_requests.id AS stock_request_id')
                                    ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                    ->where('taxables.id', $value)
                                    ->get();

            $this->trans_no = $taxables->first()->req_no ?? "";
            // $this->trans_id = $taxables->first()->trans_id ?? "";

            $this->start_no = $taxables->first()->last_no ?? "";
            $this->stock_request_id = $taxables->first()->stock_request_id ?? "";

            $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        }
        // }
        //dd($taxables->first());
        
    }

    public function updatedCollectorId($value)
    {
        // $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        //  //dd($this->deposit_mode);
        //     $this->taxlabels = TaxLabel::select('tax_labels.*')
        //                         ->join('taxables', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //                         ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
        //                         ->distinct()
        //                         ->get();

        // if ($this->edit_mode) {
        //     $this->taxlabels = TaxLabel::select('tax_labels.*')
        //                         ->join('taxables', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //                         ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                         ->where('to_user_id', $value)
        //                         ->distinct()
        //                         ->get();
        // }

        // if ($this->deposit_mode) {
        //     $this->taxlabels = TaxLabel::select('tax_labels.*')
        //                         ->join('taxables', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //                         ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                         ->where('to_user_id', $value)
        //                         ->distinct()
        //                         ->get();

        // //dd($this->deposit_mode, $this->taxlabels);
        // }

        // // if ($this->deposit_mode) {
        // //     $this->taxables = Taxable::select('taxables.*')
        // //                             ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        // //                             ->where('tax_label_id', $this->taxlabel_id)
        // //                             ->where('to_user_id', $value)
        // //                             ->where('trans_type', "RECU")
        // //                             ->get();
        // // }

        // $this->taxable_id = "";
        // $this->trans_no = "";

        if ($this->deposit_mode) {
            $this->taxables = Taxable::select('taxables.*')
                                        ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                                        ->where('tax_label_id', null)
                                        //->where('unit', $value)
                                        ->where('type', 'ACTIVE')
                                        ->where('to_user_id', $this->collector_id)
                                        ->distinct()
                                        ->get();
        }else{
            $this->taxables = Taxable::select('taxables.*')
                                        ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                        ->where('tax_label_id', null)
                                        //->where('unit', $value)
                                        ->get();

        }

        // $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        if ($this->edit_mode == true) {
             //$this->stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
            $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        }
    }

    public function updatedEndNo($value)
    {
        //dd($this->start_no,$this->end_no);

        if ($this->start_no !== "" && $this->end_no !== "")
        {
            $this->qty = $this->end_no - $this->start_no + 1;
        }
    }

    public function updatedStartNo($value)
    {
        //dd($this->start_no,$this->end_no);

        if ($this->start_no !== "" && $this->end_no !== "")
        {
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

        //dd($this->collector_id);

        DB::transaction(function () {

        //   $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('unit', $this->taxlabel_id)->where('to_user_id', $this->collector_id)->get();
        //         $stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_type', 'RECU')->where('unit', $this->taxlabel_id)->where('to_user_id', $this->collector_id)->get();
       
        //         dd($this->edit_mode, $stock_transfers,$this->stock_transfers);
            $stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
          
            if ($this->edit_mode) {
                $total_sold = 0;
                if ($stock_transfers){

                    $paymentData = [
                        'amount' => $total_sold,
                        //'qty' => $stock_transfer->qty,
                        'payment_type' => 'CASH',
                        'reference' => "-",
                        'description' => "Etat de versement collecteur N°".$this->collector_id,
                        'user_id' => $this->user_id,
                        'to_user_id' => $this->collector_id,
                        'reference' => $this->taxlabel_id,
                    ];

                    //dd($paymentData);

                    $payment = Payment::create($paymentData);

                    foreach ($stock_transfers as $stock_transfer) {

                        $stockTtransferData = [
                            'trans_no' => $stock_transfer->trans_no,
                            'trans_id' => $stock_transfer->trans_id,
                            //'qty' => $stock_transfer->qty,
                            'type' => 'ARCHIVED',
                            'end_no' => $stock_transfer->end_no,
                            'taxable_id' => $stock_transfer->taxable_id,
                            'trans_type' => 'RENDU',
                            'payment_id' => $payment->id,
                            'by_user_id' => $this->user_id,
                            'to_user_id' => $stock_transfer->to_user_id,
                        ];
        
                        $stock_transfer_new = StockTransfer::create($stockTtransferData);

                        // $stock_transfer_old = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
                        $stock_transfer_olds = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('taxable_id', $stock_transfer->taxable_id)->where('to_user_id', $this->collector_id)->orderBy('end_no', 'DESC')->get();

                        // dd($stock_transfer->end_no, $stock_transfer_olds->first()->end_no);
                        //dd($stock_transfer_olds->first()->last_no);
                        if ($stock_transfer_olds->first()){
                            $stock_transfer_new->qty = $stock_transfer->end_no - $stock_transfer_olds->first()->end_no;
                            $stock_transfer_new->start_no = $stock_transfer_olds->first()->last_no ?? $stock_transfer->start_no;
                        }else{
                            $stock_transfer_new->qty = $stock_transfer->qty;
                            $stock_transfer_new->start_no = $stock_transfer->start_no;
                            $stock_transfer_new->last_no = $stock_transfer->start_no;
                        }
                        $stock_transfer_new->save();

                        foreach ($stock_transfer_olds as $stock_transfer_old) {
                            $stock_transfer_old->type = "ARCHIVED";
                            $stock_transfer_old->save();

                            $total_sold += $stock_transfer_old->qty * $stock_transfer_old->taxable->tariff;
                        }

                        $stock_transfer->type = 'ARCHIVED';
                        $stock_transfer->save();
                        
                        //dd($stock_transfer->type);
                    }

                    $payment->amount = $total_sold;
                    $payment->save();
                    
                    $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
                }

            } else {

                // Prepare the data for creating a new Taxable
                $data = [
                    'trans_no' => $this->trans_no,
                    'qty' => $this->qty,
                    'start_no' => $this->start_no,
                    'end_no' => $this->end_no,
                    'last_no' => $this->start_no,
                    'taxable_id' => $this->taxable_id,
                    'trans_type' => 'RECU',
                    'by_user_id' => $this->user_id,
                    'to_user_id' => $this->collector_id,
                ];

                //dd($this->trans_id);
                
                if ($this->deposit_mode) {
                    $data['trans_type'] = 'VENDU';
                    $data['last_no'] = $this->end_no + 1;
                    $data['trans_id'] = $this->trans_id;
                    $data['code'] = $this->code;

                    $this->start_no = $this->end_no + 1;
                }

                $stock_transfer = StockTransfer::create($data);

                if (!$this->deposit_mode) {
                    $stock_transfer->trans_id = $stock_transfer->id;
                    $stock_transfer->save();
                }

                if (!$this->deposit_mode) {
                    $stock_request = StockRequest::find($this->stock_request_id);
                    
                    $stock_request->last_no = $this->end_no + 1;
                    $stock_request->save();

                    $this->start_no = $this->end_no + 1;
                }
                //dd($stock_request);
                //$this->stock_request_id

                // if ($this->edit_mode) {
                //     // Emit a success event with a message
                //     $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
                // }
            }

                $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

                if ($this->deposit_mode) {
                    $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
                }
        });

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

    public function addTrnasfer($id)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        // $this->stock_transfer_id = '';
        // $this->trans_no = '';
        $this->edit_mode = false;
        $this->deposit_mode = false;

        //   dd($this->edit_mode,$this->deposit_mode);
    }

    public function addDeposit($id)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

    //     $this->edit_mode = false;

    //     //$this->updateRequest($id);
    //     //$this->addRequest($id);
        $this->deposit_mode = true;
        $this->edit_mode = false;

        // dd($this->edit_mode,$this->deposit_mode);
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
