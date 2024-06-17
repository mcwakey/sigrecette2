<?php

namespace App\Livewire\Taxpayer;

use App\Models\Taxpayer;
use Livewire\Attributes\On;
use Livewire\Component;

class TaxpayerInvoices extends Component
{
    public $taxpayer_id;
    public $taxpayer;
    public function mount($id )
    {
        $this->taxpayer_id = $id;
        $this->assignTaxpayer();

    }
    #[On('refreshTaxpayer')]
    public function refresh(){
        $this->assignTaxpayer();
    }
    public function render()
    {

        dump("render call");
        return view('livewire.taxpayer.taxpayer-invoices');
    }


    public function assignTaxpayer(){
        $this->taxpayer = Taxpayer::find($this->taxpayer_id);
    }

}
