<?php
namespace App\Livewire\StockRequest;
use App\Models\StockRequest;
use App\Models\StockTransfer;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\TaxpayerTaxable;
use App\Traits\DispatchesMessages;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
    public $taxables = [];
    public $stock_requests = [];
    public $taxable_name;
    public $taxlabel_name;
    public $taxable_idd;
    public $taxlabel_idd;
    public $remaining_qty;
    public $edit_mode = false;
    protected function rules()
    {
        $this->start_no = $this->start_no === "" ? null : $this->start_no;
        $this->end_no = $this->end_no === "" ? null : $this->end_no;
        return [
            'req_no' => 'required|string',
            'taxlabel_id' => 'required',
            'taxable_id' => 'required|numeric',
            'start_no' => 'nullable|numeric|min:0' .
                (is_null($this->end_no) ? '' : '|max:' . (intval($this->end_no) - 1)),
            'end_no' => 'nullable|numeric' .
                (is_null($this->start_no) ? '' : '|min:' . (intval($this->start_no) + 1)),
            'qty' => ['required', 'numeric', 'min:1', function ($attribute, $value, $fail) {
                try {
                    if (!is_null($this->start_no) && !is_null($this->end_no) && $value !== intval($this->end_no) - intval($this->start_no) + 1) {
                        dump($this->start_no, $this->end_no);
                        $fail('Les valeurs saisies dans n° de debut ou n° de fin sont incorrectes.');
                    }
                }catch (Exception $e) {
                    $fail('Les valeurs saisies dans n° de debut ou n° de fin sont incorrectes.');
                }
            }],
        ];
    }
    protected $listeners = [
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
        return view('livewire.stock_request.add-stock-request-modal', ['taxlabels' => $taxlabels]);
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
        if (is_numeric($this->start_no) && is_numeric($this->end_no)) {
            $this->qty = intval($this->end_no) - intval($this->start_no) + 1;
        }
    }
    public function updatedReqNo($value)
    {
        $this->stock_requests = StockRequest::where('req_no', $this->req_no)->where('req_type', 'DEMANDE')->get();
    }
    public function submit()
    {
        $this->validate();
        DB::transaction(function () {
            $data = [
                'req_no' => $this->req_no,
                'req_desc' => 'Demande d’approvisionnement N°' . $this->req_no,
                'qty' => $this->qty,
                'start_no' => $this->start_no,
                'last_no' => $this->start_no,
                'end_no' => $this->end_no,
                'taxable_id' => $this->taxable_id,
                'req_type' => 'DEMANDE',
                'user_id' => Auth::id(),
            ];
            $stock_request = StockRequest::create($data);
            $stock_request->req_id = $stock_request->id;
            if ($this->edit_mode) {
                // Save the invoice ID into the invoice_no column
                $stock_request->req_id = $this->stock_request_id;
            }
            $stock_request->save();
            $this->stock_requests = StockRequest::where('req_no', $this->req_no)->where('req_type', 'DEMANDE')->get();
            $this->qty = null;
            $this->start_no = null;
            $this->end_no = null;
            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatchMessage(__('Stock valeur inactive'), 'update');
            } else {
                $this->dispatchMessage('Stock valeur inactive');
            }
        });
    }
    /**
     * @param $id
     * @return void
     */
    public function deleteStockRequest($id)
    {
        try {
            $in_distribution = StockTransfer::where('stock_request_id', $id)->get();
            if ($in_distribution->isEmpty()) {
                StockRequest::destroy($id);
                $this->dispatchMessage(__('Stock valeur inactive'), 'update');
                return;
            }
            $this->dispatchMessage(__('Stock valeur inactive'), 'update', 'error', 'Mise à jour impossible car le stock est déjà distribué.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                session()->flash('error', 'Erreur : Ce transfert de stock ne peut pas être supprimé car il est lié à d\'autres enregistrements.');
            } else {
                session()->flash('error', 'Erreur lors de la suppression du transfert de stock : ' . $e->getMessage());
            }
        } catch (Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression du stock : ' . $e->getMessage());
        }
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
        $stock_request = StockRequest::find($id);
        $this->stock_request_id = $id;
        $this->req_no = $stock_request->req_no;
        $this->taxlabel_name = $stock_request->taxable->unit;
        $this->taxable_idd = $stock_request->taxable_id;
        $this->taxable_name = $stock_request->taxable->name;
        $this->start_no = $stock_request->last_no;
        $this->end_no = $stock_request->end_no;
        $this->qty = $stock_request->end_no - $stock_request->last_no + 1;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
