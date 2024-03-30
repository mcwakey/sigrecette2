<?php

namespace App\Livewire\StockTransfer;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Payment;
use App\Models\StockRequest;
use App\Models\StockTransfer;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddStockTransferModal extends Component
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

    public $taxable_id;
    public $taxlabel_id;
    
    public $authorisation;
    public $auth_reference;

    public $unit;
    
    public $length;
    public $width;


    public $taxables=[];
    public $taxlabels=[];
    public $stock_transfers=[];

    public $taxable_name;
    public $taxable_idd;
    public $taxlabel_name;
    public $taxlabel_idd;
    public $collector_name;
    public $collector_idd;
    // public $penalty_type;
    // public $tax_label_id;

    // public $longitude;
    // public $latitude;
    // public $canton;
    // public $town;
    // public $erea;
    // public $address;
    // public $zone_id;
    // public $avatar;
    // public $saved_avatar;


    public $edit_mode;
    public $deposit_mode;
    public $option_calculus;

    protected $rules = [
        'collector_id' => 'required',
        //'seize' => 'required',
        //'location' => 'required',
        //'taxable_id' => 'required',
        // 'taxpayer_id' => 'required',

        // 'penalty' => 'nullable',
        // 'penalty_type' => 'nullable',
        //'tax_label' => 'required',
        // 'tax_label_id' => 'required',

        //'longitude' => 'required',
        //'latitude' => 'required',
        // 'canton' => 'required',
        // 'town' => 'required',
        // 'erea' => 'required',
        // 'address' => 'required|string',
        // 'zone_id' => 'required',
        // 'avatar' => 'nullable|sometimes|image|max:1024',
    ];

    protected $listeners = [
        'delete_taxpayer' => 'deleteUser',
        'change_qty' => 'changeQty',
        'load_drop' => 'loadDrop',
        'add_transfer' => 'addTrnasfer',
        'update_transfer' => 'updateTrnasfer',
        'add_deposit' => 'addDeposit',
    ];

    public function render()
    {
        //$cantons = Canton::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        //$genders = Gender::all();
        // $collectors = User::where('town_id', $value)->get();

        $this->user_id = '1';

        $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
                            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                            ->where('roles.name', 'collecteur')
                            ->get();

        // $taxlabels = TaxLabel::select('tax_labels.*')
        //                         ->join('taxables', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //                         ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
        //                         ->distinct()
        //                         ->get();

        //$taxables = Taxable::all();

        // Assuming you have a public property $canton in your Livewire component

        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        return view('livewire.stock_transfer.add-stock-transfer-modal', compact('collectors'));
    }

    public function updatedTaxlabelId($value)
    {
        $this->taxable_id = "";
        $this->trans_no = "";

        //dd($this->edit_mode);
        // $this->taxables = Taxable::where('tax_label_id', $value)->get(); // Load taxables based on tax label ID
        // if ($this->deposit_mode == false) {
        $this->taxables = Taxable::select('taxables.*')
                                    ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                    ->where('tax_label_id', $value)
                                    ->get();
        // }else{
        //     $this->taxables = Taxable::select('taxables.*')
        //                             ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                             ->where('tax_label_id', $value)
        //                             ->where('to_user_id', $this->collector_id)
        //                             ->where('trans_type', "RECU")
        //                             ->get();
        // }

        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        if ($this->edit_mode == true) {
            $this->stock_transfers = StockTransfer::where('type', 'ACTIVE')->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

            // if ($this->deposit_mode) {
            //     $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'VENDU')->get();
            // }
        }

        //$this->reset('taxables');
        
        //$this->taxable_id = $taxpayer_taxable->taxable_id;

        //dd($this->taxables);
        // $this->loadTaxables($value); // Call the loadTaxables method when tax label ID is updated
    }

    public function updatedCollectorId($value)
    {
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        if ($this->deposit_mode) {
        //dd($value);
            $this->taxables = Taxable::select('taxables.*')
                                    ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                                    ->where('tax_label_id', $this->taxlabel_id)
                                    ->where('to_user_id', $value)
                                    ->where('trans_type', "RECU")
                                    ->get();
        }

        if ($this->edit_mode) {
            $this->taxlabels = TaxLabel::select('tax_labels.*')
                                ->join('taxables', 'taxables.tax_label_id', '=', 'tax_labels.id')
                                ->join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
                                ->where('to_user_id', $value)
                                ->distinct()
                                ->get();
        }else{
            $this->taxlabels = TaxLabel::select('tax_labels.*')
                                ->join('taxables', 'taxables.tax_label_id', '=', 'tax_labels.id')
                                ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                ->distinct()
                                ->get();
        }
    }

    public function updatedTaxableId($value)
    {
        // Debugging to ensure $value is valid
        //dd($value);

        // Assuming $value is valid, fetch taxables based on tax label ID
        // if ($this->deposit_mode == false) {
            $taxables = Taxable::select('taxables.*', 'req_no')
                                    ->join('stock_requests', 'stock_requests.taxable_id', '=', 'taxables.id')
                                    ->where('taxables.id', $value)
                                    ->get();

        $this->trans_no = $taxables->first()->req_no ?? "";

        // }else{
        //     $taxables = Taxable::join('stock_transfers', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //                             ->where('to_user_id', $this->collector_id)
        //                             ->where('taxables.id', $value)
        //                             ->where('trans_type', "RECU")
        //                             ->get();

        // $this->trans_no = $taxables->first()->trans_no ?? "";
                              

        //$this->ereas = Erea::where('town_id', $value)->get(); // Load taxables based on tax label ID
        //dd($taxable);
        // $this->unit = $taxables->unit ?? "";
        // $this->tariff = $taxables->tariff ?? "";
        // }
        
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();
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

    // public function updateCheckbox($id)
    // {
    //     // Find the taxpayer by ID
    //     //dd($id);
    //     $taxpayer_taxables = TaxpayerTaxable::findOrFail($id);

    //     // Update the invoice_id field based on the checkbox state
    //         //dd($taxpayer_taxables->billable);
    //     if ($taxpayer_taxables->billable == 0){
    //         $taxpayer_taxables->update([
    //             'billable' => '1'
    //         ]);
    //     }else {
    //         $taxpayer_taxables->update([
    //             'billable' => '0'
    //         ]);
    //     }

    //     //$taxpayer_taxables = TaxpayerTaxable::findOrFail($id);
    //     //    dd($taxpayer_taxables->billable);
    // }


    public function submit()
    {
        // Validate the form input data
        $this->validate();

        //dd($this->collector_id);

        DB::transaction(function () {

            if ($this->edit_mode) {
                $total_sold = 0;
                foreach ($this->stock_transfers as $stock_transfer) {
                    $stockTtransferData = [
                        'trans_no' => $stock_transfer->trans_no,
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

                    $stock_transfer_new->qty = $stock_transfer->end_no - $stock_transfer_olds->first()->end_no;
                    $stock_transfer_new->start_no = $stock_transfer_olds->first()->end_no + 1;
                    $stock_transfer_new->save();

                    foreach ($stock_transfer_olds as $stock_transfer_old) {
                        $stock_transfer_old->type = "ARCHIVED";
                        $stock_transfer_old->save();

                        $total_sold += $stock_transfer_old->qty * $stock_transfer_old->taxable->tariff;
                    }
                    
                    $stock_transfer->type = 'ARCHIVED';
                    $stock_transfer->save();
                }

                $paymentData = [
                    'amount' => $total_sold,
                    //'qty' => $stock_transfer->qty,
                    'payment_type' => 'CASH',
                    'reference' => "-",
                    'description' => "Etat de versement collecteur N°".$stock_transfer->to_user_id,
                    'user_id' => $this->user_id,
                    'to_user_id' => $stock_transfer->to_user_id,
                ];

                //dd($paymentData);

                $stock_transfer_new = Payment::create($paymentData);
                
                $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));

            } else {

                // Prepare the data for creating a new Taxable
                $data = [
                    'trans_no' => $this->trans_no,
                    'qty' => $this->qty,
                    'start_no' => $this->start_no,
                    'end_no' => $this->end_no,
                    'taxable_id' => $this->taxable_id,
                    'trans_type' => 'RECU',
                    'by_user_id' => $this->user_id,
                    'to_user_id' => $this->collector_id,
                ];
                
                if ($this->deposit_mode) {
                    $data['trans_type'] = 'VENDU';
                }

                $stock_transfer = StockTransfer::create($data);

                $stock_transfer->trans_id = $stock_transfer->id;
                $stock_transfer->save();

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
        $this->taxlabel_id = "";
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current Taxable
        // if ($id == Auth::id()) {
        //     $this->dispatch('error', 'Taxable cannot be deleted');
        //     return;
        // }

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

        // dd($this->edit_mode);
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

        // dd($this->edit_mode);
    }

    public function updateTrnasfer($id)
    {
        $this->collector_id = "";
        $this->taxlabel_id = "";
        $this->taxable_id = "";
        $this->trans_no = "";
        $this->stock_transfers = StockTransfer::where('trans_no', $this->trans_no)->where('trans_type', 'RECU')->where('to_user_id', $this->collector_id)->get();

        $this->edit_mode = true;
        // dd($this->edit_mode);
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

    // public function loadTaxable($id)
    // {
    //     $taxables = Taxable::find($id);


    //     return $this->taxlabel ? Taxable::where('taxlabel_id', $this->taxlabel)->get() : collect();
    // }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
