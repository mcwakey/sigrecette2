<?php

namespace App\Livewire\Town;

use App\Models\Canton;
use App\Models\Town;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddTownModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;
    public $canton_id;
    public $town_id;

    public $name;
    public $status;

    public $edit_mode = false;

    protected $rules = [
        'canton_id' => 'required|int',
        'name' => 'required|string',
        'status' => 'required|string',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateTown',
    ];

    public function render()
    {
        $cantons = Canton::all();

        return view('livewire.town.add-town-modal', compact('cantons'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'canton_id' => $this->canton_id,
                'name' => $this->name,
                'status' => $this->status,
            ];

            $town = Town::find($this->town_id) ?? Town::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $town->$k = $v;
                }
                $town->save();
            }

            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatchMessage('Ville/Quartier', 'update');
            } else {
                // Emit a success event with a message
                $this->dispatchMessage('Ville/Quartier');
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Town::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Taxpayer successfully deleted');
    }

    public function updateTown($id)
    {
        $this->edit_mode = true;

        $town = Town::find($id);

        $this->canton_id = $town->canton_id;

        $this->town_id = $town->id;
        $this->name = $town->name;
        $this->status = $town->status;

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
