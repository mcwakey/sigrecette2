<?php

namespace App\Livewire\StockRequest;

use App\Models\StockRequest;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\TaxpayerTaxable;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddStockRequestModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;
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
    public $remaining_qty;

    public $edit_mode = false;
    //public $option_calculus;

    protected function rules()
    {
        return [
            'req_no' => 'required|string',
            'taxlabel_id' => 'required',
            'taxable_id' => 'required|numeric',
            'start_no' => 'nullable|numeric|min:0|max:' . (intval($this->end_no) - 1),
            'end_no' => 'nullable|numeric|min:' . (intval($this->start_no) + 1),
            'qty' => ['required', 'numeric','min:1', function ($attribute, $value, $fail) {
                if (!is_null($this->start_no) && !is_null($this->end_no)) {
                    if ($value !== intval($this->end_no) - intval($this->start_no) + 1) {
                        $fail('Les valeurs saisies dans n° de debut ou n° de fin sont incorrectes.');
                    }
                }
            }],
        ];
    }


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


        $this->user_id = Auth::id();
        $this->stock_requests = StockRequest::where('req_no', $this->req_no)->where('req_type', 'DEMANDE')->get();
        return view('livewire.stock_request.add-stock-request-modal', compact('taxlabels'));
    }

    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::where('tax_label_id', null)->where('unit', $value)->get();
    }

    public function handleTaxableChange()
    {
        $taxable = Taxable::find($this->taxable_id);

        $this->remaining_qty = $taxable->tariff ?? 0;
    }


    public function makeCalcul()
    {
        if (is_numeric($this->start_no) && is_numeric($this->end_no))
        {
            $this->qty = intval($this->end_no) - intval($this->start_no) + 1;
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
                'start_no' => $this->start_no ,
                'last_no' => $this->start_no ,
                'end_no' => $this->end_no ,
                'taxable_id' => $this->taxable_id,
                'req_type' => 'DEMANDE',
                'user_id' => Auth::id(),
            ];

            if ($this->edit_mode) {
                $data['taxable_id'] = $this->taxable_idd;
                $data['req_desc'] = 'Etat de comptabilité des VI N°'.$this->req_no;
                $data['req_type'] = 'COMPTABILISE';
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
            $this->qty = null;
            $this->start_no = null;
            $this->end_no = null;


            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatchMessage( __('Stock valeur inactive'),'update',);
            }else{
                $this->dispatchMessage('Stock valeur inactive');
            }
            // }
        });

        // Reset the form fields after successful submission
        //$this->reset();
    }

    public function deleteStockRequest($id)
    {
        StockRequest::destroy($id);

       // $this->dispatchMessage('line', 'delete');
    }

    public function addRequest($id)
    {
        $this->edit_mode = false;
        $this->stock_request_id = null;
        $this->req_no = null;
    }

    public function updateRequest($id)
    {
        $this->edit_mode = true;
        //dd($id);
        // $taxpayer = Taxpayer::find($id);
        $stock_request = StockRequest::find($id);
        //dd($stock_request);

        $this->stock_request_id = $id;
        $this->req_no = $stock_request->req_no;

        //$this->taxlabel_idd = $stock_request->taxable->tax_label->id ?? '';
        $this->taxlabel_name = $stock_request->taxable->unit;

        $this->taxable_idd = $stock_request->taxable_id;
        $this->taxable_name = $stock_request->taxable->name;

        $this->start_no = $stock_request->last_no;
        $this->end_no = $stock_request->end_no;
        $this->qty =$stock_request->end_no  - $stock_request->last_no + 1;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
