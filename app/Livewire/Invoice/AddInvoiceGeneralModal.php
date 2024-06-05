<?php

namespace App\Livewire\Invoice;

use App\DataTables\TaxpayersDataTable;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\Year;
use App\Traits\DispatchesMessages;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Permission;

class AddInvoiceGeneralModal extends Component
{
    use DispatchesMessages;
    public $taxpayers;
    public $taxpayer;
    public $search = '';
    protected $rules = [


    ];

    protected $listeners = [
        'load_drop' => 'loadDrop',
    ];


    public function mount()
    {


    }

    public function render()
    {

        $this->taxpayers = Taxpayer::search($this->search)->take(10)->get();

        return view('livewire.invoice.add-invoice-general-modal',
        [

        ]);
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
       }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
