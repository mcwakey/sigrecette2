<?php

namespace App\Livewire\Taxpayer;

use App\Enums\InvoiceStatusEnums;
use App\Enums\TaxpayerStateEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Notifications\InvoiceAccepted;
use App\Notifications\InvoiceApproved;
use App\Notifications\InvoiceCreated;
use App\Notifications\InvoiceRejected;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AddTaxpayerStatusForm extends Component
{
    use DispatchesMessages;
    public $taxpayer_id;

    public $status;

    public $edit_mode = false;

    public function rules()
    {
        return [
            'status' => ['required', 'string', Rule::in(
                TaxpayerStateEnums::APPROVED,
                TaxpayerStateEnums::REJECTED)],
        ];
    }
    private $error_message;
    protected $listeners = [
        'update_status' => 'updateStatus',
    ];
    public function mount($id){
        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer?->id;
        $this->status =  $taxpayer?->from_mobile_and_validate_state;
    }
    public function render()
    {
        return view('livewire.taxpayer.add-taxpayer-status-form',['status' => $this->status]);
    }


    public function submit()
    {
        $this->validate();
        if ($this->getErrorBag()->isEmpty()) {
            DB::transaction(function () {

                $taxpayer = Taxpayer::find($this->taxpayer_id);
                $taxpayer->from_mobile_and_validate_state = $this->status;



                $taxpayer->save();

                $this->dispatchMessage('Taxpayer', 'update');
            });
            $this->reset();
        }else{
            $this->dispatchMessage('Taxpayer', 'update', 'error',"erreur");

        }





    }


    public function updateStatus($id)
    {

        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
        $this->status =  $taxpayer->from_mobile_and_validate_state;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
