<?php

namespace App\Livewire\StockRequest;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\StockRequest;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddStockTransferModal extends Component
{
    use WithFileUploads;

    public $stock_request_id;
    public $tariff;
    public $qty;
    public $start_no;
    public $end_no;
    public $req_no;

    public $taxable_id;
    public $taxlabel_id;
    public $taxpayer_id;
    
    public $authorisation;
    public $auth_reference;

    public $unit;
    
    public $length;
    public $width;


    public $taxables=[];
    public $stock_requests=[];
    public $taxable_name;
    public $taxlabel_name;
    public $taxable_idd;
    public $taxlabel_idd;
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


    public $edit_mode = false;
    public $option_calculus;

    protected $rules = [
        'req_no' => 'required|string',
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
        'load_drop' => 'load_drop',
        'add_request' => 'addRequest',
        'update_request' => 'updateRequest',
    ];

    public function render()
    {
        //$cantons = Canton::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        //$genders = Gender::all();
        $taxlabels = TaxLabel::all();
        //$taxables = Taxable::all();

        // Assuming you have a public property $canton in your Livewire component

        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        return view('livewire.stock_transfer.add-stock-transfer-modal', compact('taxlabels'));
    }

    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::where('tax_label_id', $value)->get(); // Load taxables based on tax label ID
        //$this->reset('taxables');
        
        //$this->taxable_id = $taxpayer_taxable->taxable_id;

        //dd($this->taxables);
        // $this->loadTaxables($value); // Call the loadTaxables method when tax label ID is updated
    }

    public function updatedTaxableId($value)
    {
        // Debugging to ensure $value is valid
        //dd($value." TaxableId");

        // Assuming $value is valid, fetch taxables based on tax label ID
        $taxables = Taxable::find($value);
        //$this->ereas = Erea::where('town_id', $value)->get(); // Load taxables based on tax label ID
        //dd($taxables);

        $this->option_calculus = $taxables->unit_type ?? "";
        $this->unit = $taxables->unit ?? "";
        $this->tariff = $taxables->tariff ?? "";
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

    public function updatedReqNo($value)
    {
        $this->stock_requests = StockRequest::where('req_no', $this->req_no)->where('req_type', 'DEMANDE')->get();
    }

    public function updateCheckbox($id)
    {
        // Find the taxpayer by ID
        //dd($id);
        $taxpayer_taxables = TaxpayerTaxable::findOrFail($id);

        // Update the invoice_id field based on the checkbox state
            //dd($taxpayer_taxables->billable);
        if ($taxpayer_taxables->billable == 0){
            $taxpayer_taxables->update([
                'billable' => '1'
            ]);
        }else {
            $taxpayer_taxables->update([
                'billable' => '0'
            ]);
        }

        //$taxpayer_taxables = TaxpayerTaxable::findOrFail($id);
        //    dd($taxpayer_taxables->billable);
    }


    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'req_no' => $this->req_no,
                //'req_id' => $this->seize,
                'req_desc' => 'Demande d’approvisionnement N°'.$this->req_no,
                'qty' => $this->qty,
                'start_no' => $this->start_no,
                'end_no' => $this->end_no,
                'taxable_id' => $this->taxable_id,
                'req_type' => 'DEMANDE',
            ];

            if ($this->edit_mode) {
                $data['taxable_id'] = $this->taxable_idd;
                $data['req_desc'] = 'Etat de comptabilité des VI N°'.$this->req_no;
                $data['req_type'] = 'COMPTABILITE';
            }

            $stock_request = StockRequest::create($data);

            //$invoice_old = Invoice::find($this->invoice_id ?? $invoice->id);

            $stock_request->req_id = $stock_request->id;

            if ($this->edit_mode) {
                // Save the invoice ID into the invoice_no column
                $stock_request->req_id = $this->stock_request_id;
            }
            $stock_request->save();

            $this->stock_requests = StockRequest::where('req_no', $this->req_no)->where('req_type', 'DEMANDE')->get();

            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatch('success', __('Etat de comptabilité mis a jour avec succès'));
            }
            // }
        });

        // Reset the form fields after successful submission
        //$this->reset();
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

    public function addRequest($id)
    {
        $this->edit_mode = false;
        $this->stock_request_id = '';
        $this->req_no = '';
    }

    public function updateRequest($id)
    {
        $this->edit_mode = true;
        //dd($id);
        // $taxpayer = Taxpayer::find($id);
        $stock_request = StockRequest::find($id);
        //dd($stock_request->taxable->tax_label);

        $this->stock_request_id = $id;
        $this->req_no = $stock_request->req_no;
        
        $this->taxlabel_idd = $stock_request->taxable->tax_label->id;
        $this->taxlabel_name = $stock_request->taxable->tax_label->name;

        $this->taxable_idd = $stock_request->taxable_id;
        $this->taxable_name = $stock_request->taxable->name;
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