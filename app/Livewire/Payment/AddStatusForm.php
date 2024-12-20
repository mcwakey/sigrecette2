<?php
namespace App\Livewire\Payment;
use App\Models\Payment;
use App\Traits\DispatchesMessages;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
class AddStatusForm extends Component
{
    use DispatchesMessages;
    public $payment_id;
    public $status;
    public $edit_mode = false;
    protected $rules = [
        "status" => "required",
    ];
    protected $listeners = [
        //'delete_user' => 'deleteUser',
        'update_payment_status' => 'updateStatus',
        //'add_payment' => 'addPayment',
    ];
    public function render()
    {
        return view('livewire.payment.add-status-form');
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        DB::transaction(function () {
            // Prepare data for Payment
            $data = [
                'status' => $this->status,
                'r_user_id' => Auth::id()
            ];
            $payment = Payment::find($this->payment_id); //?? Payment::create($payment_id);
            $this->payment_id = $payment->id;
            foreach ($data as $k => $v) {
                $payment->$k = $v;
            }
            $payment->save();
            $this->dispatchMessage('Paiement', 'update');
        });
        // Reset form fields after successful submission
        $this->reset();
    }
    public function updateStatus($id)
    {
        $payment = Payment::find($id);
        $this->payment_id = $payment->id;
        $this->status = $payment->status;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
