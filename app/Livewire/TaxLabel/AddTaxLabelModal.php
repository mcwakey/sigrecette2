<?php

namespace App\Livewire\TaxLabel;

use App\Models\TaxLabel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddTaxLabelModal extends Component
{
    use WithFileUploads;

    public $tax_label_id;
    public $name;
    public $category;
    public $code;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'category' => 'required',
        'code' => 'required',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'close_tax_label_modal' => 'closeTaxLabelModal',
    ];

    public function render()
    {
        return view('livewire.tax_label.add-tax-label-modal');
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new TaxLabel
            $data = [
                'name' => $this->name,
                'category' => $this->category,
                'code' => $this->code,
            ];

            // Update or Create a new TaxLabel record in the database
            //$data['email'] = $this->email;
            $tax_label = TaxLabel::find($this->tax_label_id) ?? TaxLabel::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $tax_label->$k = $v;
                }
                $tax_label->save();
            }

            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatch('success', __('Libellé Fiscal mis a jour.'));
            } else {

                // Emit a success event with a message
                $this->dispatch('success', __('Libellé Fiscal créer.'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        TaxLabel::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Libellé Fiscal supprimé.');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $tax_label = TaxLabel::find($id);

        $this->tax_label_id = $tax_label->id;
        $this->name = $tax_label->name;
        $this->category = $tax_label->category;
        $this->code = $tax_label->code;
    }

    public function closeTaxLabelModal()
    {
        $this->edit_mode = false;
        $this->tax_label_id = '';
        $this->name = '';
        $this->category = '';
        $this->code = '';
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
