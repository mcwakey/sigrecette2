<?php

namespace App\Livewire\Ticket;

use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddTicketModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $taxable_id;
    public $name;
    public $tariff;
    public $unit;

    public $edit_mode = false;
    protected function rules(){
        $rules = [
            'name' => 'required|string|unique:taxables,name,' . ($this->edit_mode ?  $this->taxable_id : ''),
            'tariff' => 'required|numeric',
            'unit' => 'required',
        ];

        if ($this->edit_mode) {
            $rules['name'] = 'required|string|unique:taxables,name,' . $this->taxable_id;
        }
        
        return $rules;
    }
    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'close_ticket_modal' => 'closeTicketModal',
    ];

    public function render()
    {

        $tax_labels = TaxLabel::all();


        return view('livewire.ticket.add-ticket-modal', compact('tax_labels'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->name,
                'tariff' => $this->tariff,
                'unit' => $this->unit,
            ];

            $taxable = Taxable::find($this->taxable_id) ?? Taxable::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $taxable->$k = $v;
                }
                $taxable->save();
            }

            if ($this->edit_mode) {
  
                // Emit a success event with a message
                $this->dispatch('success', __('Valeur inactive mis a jour'));
            } else {
                // Emit a success event with a message
                $this->dispatch('success', __('Valeur inactive créer'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Taxable::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Valeur inactive supprimer.');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;
        $taxable = Taxable::find($id);
        $this->taxable_id = $taxable->id;
        $this->name = $taxable->name;
        $this->tariff = $taxable->tariff;
        $this->unit = $taxable->unit;

    }

    public function closeTicketModal()
    {
        $this->edit_mode = false;
        $this->taxable_id = '';
        $this->name = '';
        $this->tariff = '';
        $this->unit = '';
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
