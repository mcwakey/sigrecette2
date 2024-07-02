<?php

namespace App\Livewire\Invoice;

;

use App\DataTables\TaxpayersDataTable;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;

use App\Models\Zone;
use App\Traits\DispatchesMessages;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class AddInvoiceGeneralModal extends Component
{
    use DispatchesMessages;
    use WithPagination;


    public $taxpayer_id;
    public $taxpayer_taxables;

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


    public function mount($id){
       $this->taxpayer_id = $id;
        $this->loadTaxpayerData();
    }
    public function render()
    {


        return view('livewire.invoice.add-invoice-general-modal',
        [
            'taxpayer'=>Taxpayer::find($this->taxpayer_id)

        ]);
    }
    public function loadTaxpayerData()
    {
        $taxpayer = Taxpayer::find($this->taxpayer_id);
        if ($taxpayer) {
            $this->taxpayer_taxables = $taxpayer->taxpayer_taxables;
            $this->dispatch('updateSharedTaxpayerId', ['id' => $this->taxpayer_id]);
        }
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
    public function updating()
    {
        $this->loadTaxpayerData();
    }
    #[On('updatesTaxpayerTaxables')]
    public function updatesTaxpayerTaxables($id){
        if($this->taxpayer_id == $id ){
            $this->loadTaxpayerData();
        }

    }
    public function submit()
    {



       $this->validate();

        DB::transaction(function () {


            $this->dispatchMessage('Avis au sur titre');

        });


        $this->reset();
    }




    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
