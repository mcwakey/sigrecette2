<?php

namespace App\Livewire\Year;

use App\Models\Year;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddYearModal extends Component
{
    use WithFileUploads;

    public $year_id;

    public $name;
    public $status;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'status' => 'required|string',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateYear',
    ];

    public function render()
    {
        return view('livewire.year.add-year-modal');
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->name,
                'status' => $this->status,
            ];

            $year = Year::find($this->year_id) ?? Year::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $year->$k = $v;
                }
                $year->save();
            }

            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatch('success', __('Year updated'));
            } else {
                // Emit a success event with a message
                $this->dispatch('success', __('New Year created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Year::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Taxpayer successfully deleted');
    }

    public function updateYear($id)
    {
        $this->edit_mode = true;

        $year = Year::find($id);

        $this->year_id = $year->id;
        $this->name = $year->name;
        $this->status = $year->status;
    }


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
