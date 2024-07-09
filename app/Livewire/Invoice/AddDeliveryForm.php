<?php

namespace App\Livewire\Invoice;

use App\Helpers\Constants;
use App\Models\Invoice;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddDeliveryForm extends Component
{
    //use WithFileUploads;
    use DispatchesMessages;
    public $invoice_id;

    //public $delivery;
    public $delivery_date;
    public $delivery_to;

    public $edit_mode = false;
    private $error_message;
    protected $rules = [
        //"delivery" =>"required",
        "delivery_date" =>"required|string",
        "delivery_to"=>"required|string",
    ];

    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_status' => 'updateStatus',
        //'add_invoice' => 'addInvoice',
    ];
    public function render()
    {
        return view('livewire.invoice.add-delivery-form');
    }
    public function validateData(){
        $this->validate();
        $invoice = Invoice::find($this->invoice_id);
        if($invoice && $invoice->reduce_amount == ''){
            if (
                $invoice  ) {
                if($invoice->type ==Constants::INVOICE_TYPE_TITRE &&  (!$invoice->ondistributionprint) || (!$invoice->onrecoveryprint) ){
                    if(!$invoice->ondistributionprint){
                        $this->error_message="Veuillez au préalable imprimer une fiche de distribution contenant l'avis.";
                    }else{
                        $this->error_message="Veuillez au préalable imprimer une fiche de recouvrement contenant l'avis.";
                    }
                    $this->addError('delivery_to', $this->error_message);

                }


            }
        }

    }
    public function submit()
    {
        $this->validateData();
        if ($this->getErrorBag()->isEmpty()) {

        DB::transaction(function () {

            // Prepare data for Invoice
            $data = [
                'delivery' => "DELIVERED",
                'delivery_date' => $this->delivery_date,
                "delivery_to"=> $this->delivery_to

            ];

            //dd($invoiceData);

            // Create or update Invoice record
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);

            $this->invoice_id = $invoice->id;

            foreach ($data as $k => $v) {
                $invoice->$k = $v;
            }
            $invoice->save();
            $this->dispatchMessage('Avis', 'update');
        });

            $this->reset();
        }else{
            $this->dispatchMessage('Avis', 'update', 'error',$this->error_message);

        }
    }


    public function updateStatus($id)
    {

        $invoice = Invoice::find($id);

        $this->invoice_id = $invoice->id;
        $this->status = $invoice->status;

        //$this->$invoice = $invoice;

        //dd($this->invoice_id);

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}

