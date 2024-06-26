<?php

namespace App\Livewire\Invoice;

;

use App\DataTables\TaxpayersDataTable;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;

use App\Models\Zone;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class AddInvoiceGeneralModal extends Component
{
    use DispatchesMessages;
    use WithPagination;
    public $search="";
    public $is_set_taxpayer=false;
    public $taxpayer_id;
    public $taxpayer_taxables;
    public $perPages=10;
    protected $rules = [


    ];

    protected $listeners = [
        'load_drop' => 'loadDrop',

    ];
    public $zones;


    public function rules()
    {
        return [];
    }


    public function mount(){
        $this->zones = Zone::all();
    }
    public function render(TaxpayersDataTable $taxpayersDataTable  )
    {

        $taxpayer= Taxpayer::find($this->taxpayer_id);
        if($taxpayer){
            $this->is_set_taxpayer=true;
            $this->taxpayer_taxables =$taxpayer->taxpayer_taxables;
            $this->dispatch('updateSharedTaxpayerId', id:  $this->taxpayer_id);
        }else{
            $this->is_set_taxpayer=false;
        }
        return view('livewire.invoice.add-invoice-general-modal',
        [
            'taxpayers'=> Taxpayer::search($this->search)->paginate($this->perPages, pageName: 'invoices-modal'),
            'taxpayer'=>$taxpayer,
            //'taxpayersDataTable'=>$taxpayersDataTable->html()

        ]);
    }
    public function updateCheckbox($taxpayerTaxableId, $value)
    {
        foreach ($this->taxpayer_taxables as &$taxpayer_taxable) {
            if ($taxpayer_taxable['id'] == $taxpayerTaxableId) {
                $taxpayer_taxable['billable'] = $value;
                break;
            }
        }

        TaxpayerTaxable::where('id', $taxpayerTaxableId)->update(['billable' => $value]);
    }
    public function hhpaginationView()
    {
        return 'layout.partials.custom_pagination';
    }
    public function submit()
    {



       $this->validate();

        DB::transaction(function () {


            $this->dispatchMessage('Avis au comptant');

        });


        $this->reset();
    }

    public function select_taxpayer($value){
        $this->taxpayer_id = $value;
    }


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
