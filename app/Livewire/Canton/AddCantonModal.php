<?php

namespace App\Livewire\Canton;

use App\Models\Canton;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddCantonModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $canton_id;

    public $name;
    public $status;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'status' => 'required|string',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateCanton',
    ];

    public function render()
    {
        return view('livewire.canton.add-canton-modal');
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

            $canton = Canton::find($this->canton_id) ?? Canton::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $canton->$k = $v;
                }
                $canton->save();
            }

            if ($this->edit_mode) {
                $this->dispatchMessage('Canton', 'update');
            } else {
                $this->dispatchMessage('Canton');
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Canton::destroy($id);

        // Emit a success event with a message
        //$this->dispatch('success', 'Taxpayer successfully deleted');
        $this->dispatchMessage('Canton', 'delete');

    }

    public function updateCanton($id)
    {
        $this->edit_mode = true;

        $canton = Canton::find($id);

        $this->canton_id = $canton->id;
        $this->name = $canton->name;
        $this->status = $canton->status;
    }


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
