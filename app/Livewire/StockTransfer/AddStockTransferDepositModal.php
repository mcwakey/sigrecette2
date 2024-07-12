<?php

namespace App\Livewire\StockTransfer;

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
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Return_;

class AddStockTransferDepositModal extends Component
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
    public $request_nos=[];
    // public $stock_requests=[];

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
    public $stock_transfers_v;
    public $select_transfer;

    public function rules()
    {
        $rules = [
            'collector_id' => 'required',
            'trans_no' => 'required',
        ];

        if ($this->deposit_mode) {
            $rules['code'] = 'required';
            // $rules['taxlabel_id'] = 'required';
            $rules['start_no'] = 'nullable|numeric|min:' . $this->select_transfer->start_no . '|max:' . ($this->select_transfer->end_no-1);
            $rules['end_no'] = 'nullable|numeric|min:' . ( $this->select_transfer->start_no + 1) . '|max:' .$this->select_transfer->end_no;
        }


        return $rules;
    }

    public function validateData()
    {
        $this->validate();
        if ($this->deposit_mode ) {
            if( $this->start_no &&  $this->end_no==null || $this->start_no==null &&  $this->end_no){
                if( $this->start_no==null){
                    $this->addError('start_no', 'Le numéro de début est obligaoire quand le numéro de fin est défini');

                }else{
                    $this->addError('end_no', 'Le numéro de fin est obligaoire quand le numéro de debut est défini ');
                }
            }
            if( $this->start_no !== null && $this->end_no !== null && $this->select_transfer!=null){
                if ($this->start_no >= $this->end_no) {
                    $this->addError('end_no', 'Le numéro de fin doit être supérieur au numéro de début.');
                }
                if ($this->start_no <  $this->select_transfer->start_no || $this->end_no >$this->select_transfer->end_no) {
                    $this->addError('start_no', 'Le numéro de début et le numéro de fin doivent se situer dans la plage des  allouée.');
                }
                $overlapExists = StockTransfer::where('type', '=', 'ACTIVE')
                    ->where('stock_request_id', '=', $this->stock_request_id)
                    ->where('to_user_id', '=', $this->collector_id)
                    ->where('trans_type', '=', 'VENDU')
                    ->where(function ($query) {
                        $query->whereBetween('start_no', [$this->start_no, $this->end_no])
                            ->orWhereBetween('end_no', [$this->start_no, $this->end_no])
                            ->orWhere(function ($query) {
                                $query->where('start_no', '<=', $this->start_no)
                                    ->where('end_no', '>=', $this->end_no);
                            });
                    })
                    ->exists();
                if ($overlapExists) {
                    $this->addError('start_no', 'Le plage de valeurs chevauche les ventes existantes.');
                    $this->addError('end_no', 'Le plage de valeurs chevauche les ventes existantes.');
                }
            }


        }
    }


    protected $listeners = [
        'delete_taxpayer' => 'deleteUser',
        'change_qty' => 'changeQty',
        'load_drop' => 'loadDrop',
        'add_transfer' => 'addTrnasfer',
        'update_transfer' => 'updateTransfer',
        'add_deposit' => 'addDeposit',
    ];

    public function mount($id){
       $this->collector_id =$id;
    }

    public function render()
    {

        $this->user_id = Auth::id();


        $taxlabel_list = TaxLabel::where('category', 'CATEGORY 3')->get();
        $stock_requests= StockRequest::where('req_type','DEMANDE')->where('type','ACTIVE')->get();

        $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
                            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('roles.name', 'collecteur')
                            ->get();

        $this->request_nos = StockTransfer::select('trans_no')->groupBy('trans_no')->where('to_user_id', $this->collector_id)->get();

        return view('livewire.stock_transfer.add-stock-transfer-deposit-modal', compact('collectors', 'stock_requests', 'taxlabel_list'));
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


    public function updatedTransNo($value)
    {
        // $this->stock_requests= StockRequest::where('req_type','DEMANDE')->where('type','ACTIVE')->where('req_no', $value)->get();


        $this->stock_transfers = StockTransfer::where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->where('trans_no', $value)->where('type','ACTIVE')->get();

        $this->stock_transfers_v = StockTransfer::
        where('trans_type', 'VENDU')
            ->where('type','ACTIVE')
            ->where('to_user_id', $this->collector_id)->get();
    }

    public function updatedStockTransferId($value)
    {
        if($value==null){
            return;
        }
        $select_transfer= StockTransfer::find($value);
        $this->select_transfer = $select_transfer;
        // $this->trans_no =  $select_transfer?->stock_request->req_no;
        $this->stock_request_id=  $select_transfer?->stock_request->id;
        $this->taxable_id= $select_transfer?->taxable->id;
        $value= $select_transfer?->taxable->id;
        $this->tariff = $select_transfer?->taxable->tariff;
        $this->remaining_qty = 0;

        // $this->start_no = $select_transfer?->trans_id;

        //     $qty=0;
        //     $qty_r=0;
        $stock_transfers_r = StockTransfer:: where("trans_id", $select_transfer?->trans_id)
                                    ->where('type', 'ACTIVE')
                                    // ->where('trans_type', 'RECU')
                                    ->where('to_user_id', $this->collector_id)
                                    ->orderBy('stock_transfers.id', 'DESC')
                                    ->get();

                                    if($this->collector_id < 1){
                                        return;
                                    }else{
                                        $stock_transfers_v = $stock_transfers_r->first()->trans_type ?? "";
                                        if($stock_transfers_v=="RECU"){
                                            $this->start_no =  $select_transfer?->start_no;
                                        }else{
                                            if($stock_transfers_v !=""){
                                            $this->start_no = $stock_transfers_r->first()->end_no + 1;
                                            }
                                        }
                                    }

                                    // $this->start_no = $stock_transfers_r->first()->last_no ?? "";

                                    foreach ($stock_transfers_r as $transfer){
                                        // $qty+=$transfer->qty;
                                        if($transfer->trans_type=="RECU"){
                                            $this->remaining_qty += $transfer->qty;
                                        }else{
                                            $this->remaining_qty -= $transfer->qty;
                                        }
                                    }


        // $stock_transfers_r = StockTransfer:: where("trans_id", $select_transfer?->trans_id)
        //                             // ->where('type', 'ACTIVE')
        //                             // ->where('trans_type', 'RECU')
        //                             // ->where('to_user_id', $this->collector_id)
        //                             ->orderBy('stock_transfers.id', 'DESC')
        //                             ->get();

        $this->trans_id = $this->stock_transfer_id;
        // }
    //     $stock_transfers_v = StockTransfer::
    //     where("stock_request_id", $select_transfer?->stock_request->id)
    //         ->where('type', 'ACTIVE')
    //         ->where('trans_type', 'VENDU')
    //         ->where('to_user_id', $this->collector_id)->get();
    //    foreach ($stock_transfers_v as $transfer){
    //        $qty+=$transfer->qty;
    //    }
        // $this->remaining_qty =  $qty_r -$qty;
       // dd($qty, $request->id);
        // if ($this->deposit_mode) {
        //     $taxables = Taxable::select('taxables.*', 'trans_no', 'trans_id', 'last_no', 'stock_transfers.id AS stock_transfers_id')
        //                             ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                             ->where('taxables.id', $value)
        //                             ->whereNot('unit', 'AUTRE')
        //                             ->orderBy('stock_transfers.id', 'DESC')
        //                             ->get();

        //    // $this->trans_no = $taxables->first()->trans_no ?? "";
        //     $this->trans_id = $taxables->first()->trans_id ?? "";

            //dd($taxables->first());

            // $this->start_no = $taxables->first()->last_no ?? "";
            // if (!$this->start_no > 0) {
            //     $this->start_no = null;
            // }
           // $this->stock_request_id = $taxables->first()->stock_transfers_id ?? "";


        //    $taxables = Taxable::select('taxables.*', 'trans_no', 'trans_id', 'last_no', 'stock_transfers.id AS stock_transfers_id')
        //                     ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                     ->where('taxables.id', $value)
        //                     ->whereNot('unit', 'AUTRE')
        //                     ->orderBy('stock_transfers.id', 'DESC')
        //                     ->get();

        //     // $this->trans_no = $taxables->first()->trans_no ?? "";
        //     $this->trans_id = $taxables->first()->trans_id ?? "";

            // $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'VENDU')->where('to_user_id', $this->collector_id)->get();
            // $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();

        // }else{
        //     $taxables = Taxable::select('taxables.*', 'req_no', 'last_no', 'stock_requests.id AS stock_request_id')
        //                             ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
        //                             ->where('taxables.id', $value)
        //                             ->whereNot('unit', 'AUTRE')
        //                             ->get();

        //   //  $this->trans_no = $taxables->first()->req_no ?? "";
        //     // $this->trans_id = $taxables->first()->trans_id ?? "";

        //     $this->start_no = $taxables->first()->last_no ?? "";
        //     if (!$this->start_no > 0) {
        //         $this->start_no = null;
        //     }
        //    // $this->stock_request_id = $taxables->first()->stock_request_id ?? "";

        //     $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        // }


        //dd($this->start_no);

        // }
        //dd($this->tariff);

    }

    public function updatedCollectorId($value)
    {
        $this->taxlabel_id = null;
        $this->taxable_id = null;
        $this->trans_no = null;
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        $this->request_nos = StockTransfer::select('trans_no')->groupBy('trans_no')->where('to_user_id', $this->collector_id)->get();

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

        $this->stock_transfers_v = StockTransfer::
        where('trans_type', 'VENDU')
            ->where('type','ACTIVE')
            ->where('to_user_id', $this->collector_id)->get();

        if ($this->deposit_mode) {
            $this->taxables = Taxable::select('taxables.*')
                                        ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                                        ->where('tax_label_id', null)
                                        ->whereNot('unit', 'AUTRE')
                                        //->where('unit', $value)
                                        ->where('type', 'ACTIVE')
                                        ->where('to_user_id', $this->collector_id)
                                        ->distinct()
                                        ->get();
        }else{
            $this->taxables = Taxable::select('taxables.*')
                                        ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                        ->where('tax_label_id', null)
                                        ->whereNot('unit', 'AUTRE')
                                        //->where('unit', $value)
                                        ->get();

        }

        // $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('unit', $value)->where('to_user_id', $this->collector_id)->get();
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('type', 'ACTIVE')->where('to_user_id', $this->collector_id)->get();

        if ($this->edit_mode == true) {
             //$this->stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
            $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
        }
    }

    public function updatedEndNo($value)
    {
        $this-> makeStartNoAndEndNoCalcul();
    }

    public function updatedStartNo($value)
    {
        $this-> makeStartNoAndEndNoCalcul();
    }
    public function makeStartNoAndEndNoCalcul(){
        if($this->stock_request_id!=null && $this->start_no < $this->end_no){
                $this->qty = $this->end_no - $this->start_no + 1;
            $this->updatedQty("");

        }else{
            $this->end_no=null;
        }
    }
    public function updatedQty($value)
    {

        if($this->qty==null && !is_numeric($this->qty)){
            return;
        }
        elseif($this->qty >$this->remaining_qty){
            $this->qty=$this->remaining_qty;
        }
        $this->total = $this->qty * $this->tariff;
    }

    public function submit()
    {
      $this->validateData();

        if ($this->getErrorBag()->isEmpty()) {
            DB::transaction(function () {

                //   $this->stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('unit', $this->taxlabel_id)->where('to_user_id', $this->collector_id)->get();
                //         $stock_transfers = StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')->where('trans_type', 'RECU')->where('unit', $this->taxlabel_id)->where('to_user_id', $this->collector_id)->get();

                //         dd($this->edit_mode, $stock_transfers,$this->stock_transfers);
                $stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();


                if ($this->edit_mode) {
                    $total_sold = 0;
                    if ($stock_transfers){

                        foreach ($stock_transfers as $stock_transfer) {

                            $stockTtransferData = [
                                'trans_no' => $stock_transfer->trans_no,
                                'trans_id' => $stock_transfer->trans_id,
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
                            $stock_transfer_olds = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'VENDU')->where('taxable_id', $stock_transfer->taxable_id)->where('trans_id', $stock_transfer->trans_id)->where('to_user_id', $this->collector_id)->orderBy('end_no', 'DESC')->get();

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

                } else {

                    // if ($this->deposit_mode) {
                    // }

                    // dd($this->start_no);
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
                        'stock_request_id'=>$this->stock_request_id
                    ];

                    //dd($this->trans_id);

                    if ($this->deposit_mode) {
                        $paymentData = [
                            'amount' => $this->total,
                            'code' => $this->code,
                            'invoice_type' => Constants::INVOICE_TYPE_COMPTANT,
                            'payment_type' => 'CASH',
                            'status' => PaymentStatusEnums::ACCOUNTED,
                            'description' => "Etat de versement collecteur ".User::find($this->collector_id)?->name,
                            'user_id' => $this->user_id,
                            'r_user_id' => $this->collector_id,
                            'reference' => $this->taxlabel_id ?? " ",
                        ];

                        //dd($paymentData);

                        $payment = Payment::create($paymentData);

                        $data['payment_id'] = $payment->id;
                        $data['trans_type'] = 'VENDU';

                        $data['period_from'] =  $stock_transfers->first()->period_from;
                        $data['period_to'] =  $stock_transfers->first()->period_to;

                        if ($this->end_no > 0) {
                            $data['last_no'] = $this->end_no + 1;
                        }else{
                            $data['last_no'] = null;
                        }
                        $data['trans_id'] = $this->trans_id;
                        $data['code'] = $this->code;

                        if ($this->start_no > 0) {
                            $this->start_no = $this->end_no + 1;
                        }
                    }

                    $stock_transfer = StockTransfer::create($data);
                    ///reduce remaining qty
                    $this->remaining_qty-=$this->qty;
                    if (!$this->deposit_mode) {
                        $stock_transfer->trans_id = $stock_transfer->id;
                        $stock_transfer->save();
                    }

                    if (!$this->deposit_mode) {
                        $stock_request = StockRequest::find($this->stock_request_id);

                        $stock_request->last_no = $this->end_no + 1;
                        $stock_request->save();

                        if ($this->start_no > 0) {
                            $this->start_no = $this->end_no + 1;
                        }
                    }
                    //dd($stock_request);
                    //$this->stock_request_id

                    // if ($this->edit_mode) {
                    //     // Emit a success event with a message
                    //     $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
                    // }

                    //dd($data);
                }

                $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('type','ACTIVE')->where('to_user_id', $this->collector_id)->get();
                // $this->stock_transfers = StockTransfer::where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->where('trans_no', $this->trans_no)->where('type','ACTIVE')->get();


                // if ($this->deposit_mode) {
                //     $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('type','ACTIVE')->where('to_user_id', $this->collector_id)->get();
                // }
            });

        }

        $this->stock_transfers_v = StockTransfer::
        where('trans_type', 'VENDU')
            ->where('type','ACTIVE')
            ->where('to_user_id', $this->collector_id)->get();

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

    public function addTrnasfer($id)
    {
        $this->collector_id = null;
        $this->taxlabel_id = null;
        $this->code = null;
        $this->taxable_id = null;
        // $this->trans_no = null;
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        // $this->stock_transfer_id = '';
        // $this->trans_no = '';
        $this->edit_mode = false;
        $this->deposit_mode = false;

        //   dd($this->edit_mode,$this->deposit_mode);
    }

    public function addDeposit($id)
    {
        //dd($id);
        $this->collector_id = $id;
        $this->taxlabel_id = null;
        $this->code = null;
        $this->taxable_id = null;
        // $this->trans_no = null;
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

    //     $this->edit_mode = false;

    //     //$this->updateRequest($id);
    //     //$this->addRequest($id);
        $this->deposit_mode = true;
        $this->edit_mode = false;

        // dd($this->edit_mode,$this->deposit_mode);

        if ($this->deposit_mode) {
            $this->taxables = Taxable::select('taxables.*')
                                        ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                                        ->where('tax_label_id', null)
                                        ->whereNot('unit', 'AUTRE')
                                        //->where('unit', $value)
                                        ->where('type', 'ACTIVE')
                                        ->where('to_user_id', $this->collector_id)
                                        ->distinct()
                                        ->get();
        }else{
            $this->taxables = Taxable::select('taxables.*')
                                        ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                        ->where('tax_label_id', null)
                                        ->whereNot('unit', 'AUTRE')
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

    public function updateTransfer($id)
    {
        $this->collector_id = $id;
        $this->taxlabel_id = null;
        $this->code = null;
        $this->taxable_id = null;
        // $this->trans_no = null;
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
    }public function deleteStockRequest($id)
{
    StockTransfer::destroy($id);

    // $this->dispatchMessage('line', 'delete');
}

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
