<?php

namespace App\Livewire\StockRequest;

use App\Models\StockRequest;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\TaxpayerTaxable;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddStockRequestModal extends Component
{
    use WithFileUploads;

    public $stock_request_id;
    public $user_id;
    public $tariff;
    public $qty;
    public $start_no;
    public $end_no;
    public $req_no;

    public $taxable_id;
    public $taxlabel_id;

    public $taxables=[];
    public $stock_requests=[];
    public $taxable_name;
    public $taxlabel_name;
    public $taxable_idd;
    public $taxlabel_idd;

    public $edit_mode = false;
    //public $option_calculus;

    protected $rules = [
        'req_no' => 'required|string',
        'qty' => 'required|numeric',
        'start_no' =>'required|numeric',
        'end_no' => 'required|numeric',
        //'taxlabel_id' => 'required|numeric',
        'taxable_id' => 'required|numeric',
        'user_id' => 'required|numeric',
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
        $taxlabels = TaxLabel::all();

        $this->user_id = '1';

        return view('livewire.stock_request.add-stock-request-modal', compact('taxlabels'));
    }

    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::where('tax_label_id', null)->where('unit', $value)->get();
    }

    // public function updatedTaxableId($value)
    // {
    //     $taxables = Taxable::find($value);

    //     //$this->option_calculus = $taxables->unit_type ?? "";
    //     $this->unit = $taxables->unit ?? "";
    //     $this->tariff = $taxables->tariff ?? "";
    //     //$this->start_no = $taxables->tariff ?? "";
    // }

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
                'last_no' => $this->start_no,
                'end_no' => $this->end_no,
                'taxable_id' => $this->taxable_id,
                'req_type' => 'DEMANDE',
                'user_id' => $this->user_id,
            ];

            if ($this->edit_mode) {
                $data['taxable_id'] = $this->taxable_idd;
                $data['req_desc'] = 'Etat de comptabilité des VI N°'.$this->req_no;
                $data['req_type'] = 'COMPTABILITE';
            }

            $stock_request = StockRequest::create($data);

            $stock_request->req_id = $stock_request->id;

            if ($this->edit_mode) {
                // Save the invoice ID into the invoice_no column
                $stock_request->req_id = $this->stock_request_id;
            }
            $stock_request->save();

            $this->stock_requests = StockRequest::where('req_no', $this->req_no)->where('req_type', 'DEMANDE')->get();

            //$this->req_no = "";
            $this->qty = "";
            $this->start_no = "";
            $this->end_no = "";

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

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
