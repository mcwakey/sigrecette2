<?php

namespace App\Livewire\Invoice;

;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;

use App\Traits\DispatchesMessages;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
class AddInvoiceGeneralModal extends Component
{
    use DispatchesMessages;
    public $taxpayers;
    public $taxpayer;
    public $search = '';
    public $is_set_taxpayer=false;
    public $taxpayer_id;
    public $taxpayer_taxables;
    protected $rules = [


    ];

    protected $listeners = [
        'load_drop' => 'loadDrop',

    ];




    public function render( )
    {

        $this->taxpayers = Taxpayer::search($this->search)->take(10)->get();
        if(count($this->taxpayers )==1){
            $this->taxpayer=$this->taxpayers[0];
            $this->is_set_taxpayer=true;
            $this->taxpayer_id =  $this->taxpayer->id;
            $this->taxpayer_taxables =$this->taxpayer->taxpayer_taxables;
            $this->dispatch('updateSharedTaxpayerId', id:  $this->taxpayer_id);
        }elseif (  $this->taxpayer){
            $this->is_set_taxpayer=true;
            $this->taxpayer_id =  $this->taxpayer->id;
            $this->taxpayer_taxables =$this->taxpayer->taxpayer_taxables;
            $this->dispatch('updateSharedTaxpayerId', id:  $this->taxpayer_id);
        }else{
            $this->is_set_taxpayer=false;
        }
        return view('livewire.invoice.add-invoice-general-modal',
        [

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

    public function submit()
    {



       $this->validate();

        DB::transaction(function () {


            $this->dispatchMessage('Avis au comptant');

        });


        $this->reset();
    }

    public function select_taxpayer($value){


       $this->taxpayer= Taxpayer::find($value);
       if($this->taxpayer){
           $this->search = '';
           $this->taxpayer_id = $this->taxpayer->id;
           $this->is_set_taxpayer=true;
       }
    }


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
