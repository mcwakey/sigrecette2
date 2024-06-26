<?php

namespace App\Livewire\Payment;

use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Helpers\Constants;
use App\Enums\PaymentStatusEnums;
use App\Models\User;
use App\Notifications\InvoicePaid;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class AddPaymentGeneralModal extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;
    use WithPagination;
    public $invoice_id;
    public $invoice;
    public $search = '';

    public $perPages=10;
    protected function rules(){
        $rules = [


        ];

        return $rules;
    }


    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_payment' => 'updatePayment',
        'update_payment_amount'=>'updatePaymentAmount'
        //'add_invoice' => 'addPayment',
        //'load_invoice' => 'loadPayment',
    ];


    public function render()
    {

        //$this->invoices = Invoice::search($this->search)->take(10)->get();

        if ( $this->invoice ) {
           // $this->invoice = $this->invoices[0];
            $this->invoice_id =$this->invoice->id;
            $this->dispatch('updateSharedInvoiceId', id:  $this->invoice_id);

        }



        return view('livewire.payment.add-payment-general-modal',[
            'invoices'=>Invoice::search($this->search)->paginate($this->perPages, pageName: 'payment-modal')
        ]);
    }

    public function select_invoice($value){


        $this->invoice= Invoice::find($value);
        if($this->invoice){
            $this->search = '';
            $this->invoice_id = $this->invoice->id;
        }
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
