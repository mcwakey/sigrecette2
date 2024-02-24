<?php

namespace App\Livewire\Town;

use App\Models\Canton;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddTownModal extends Component
{
    use WithFileUploads;


    public $name;

    public $canton_id;



    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'canton_id' => 'required|int',


    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
    ];

    public function render()
    {

        $town = Town::all();
        $cantons = Canton::all();


        return view('livewire.town.add-town-modal', compact('town','cantons'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->name,
                'canton_id' => $this->canton_id

            ];

            $town = Town::find($this->id()) ?? Town::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $town->$k = $v;
                }
                $town->save();
            }

            if ($this->edit_mode) {
                $this->dispatch('success', __('Town updated'));
            } else {
                $this->dispatch('success', __('New Town created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }





    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
