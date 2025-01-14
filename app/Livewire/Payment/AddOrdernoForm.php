<?php
namespace App\Livewire\Invoice;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
class AddOrdernoForm extends Component
{
    public $invoice_id;
    public $orderno;
    public $edit_mode = false;
    protected $rules = [
        "orderno" => "required",
    ];
    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_invoice' => 'updateInvoice',
        //'add_invoice' => 'addInvoice',
    ];
    public function render()
    {
        return view('livewire.invoice.add-orderno-form');
    }
    public function submit()
    {
        $this->validate();
        DB::transaction(function () {
            // Prepare data for Invoice
            $data = [
                'order_no' => $this->orderno,
            ];
            $invoice = Invoice::find($this->invoice_id); //?? Invoice::create($invoice_id);
            $this->invoice_id = $invoice->id;
            foreach ($data as $k => $v) {
                $invoice->$k = $v;
            }
            $invoice->save();
            $this->dispatch('success', __('Invoice updated'));
        });
        // Reset form fields after successful submission
        $this->reset();
    }
    public function updateInvoice($id)
    {
        $this->invoice_id = $id;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
