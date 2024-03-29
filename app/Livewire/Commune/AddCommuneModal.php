<?php

namespace App\Livewire\Commune;

use App\Models\Commune;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddCommuneModal extends Component
{
    use WithFileUploads;

    public $commune_id;
    public $mayor_name;
    public $phone_number;
    public $address;
    public $treasury_name;
    public $treasury_address;
    public $treasury_rib;
    public $title;
    public $name;
    public $region_name;

    public $edit_mode = false;


    protected $rules = [
        "title"=> 'required|string',
        'name'=> 'required|string',
        'region_name'=> 'required|string',
        'mayor_name'=> 'required|string',
        'phone_number'=> 'required|string',
        'address'=> 'required|string',
        'treasury_name'=> 'required|string',
        'treasury_address'=> 'required|string',
        'treasury_rib'=> 'required|string',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateCommune',
    ];

    public function render()
    {
        $communes = Commune::all();

        return view('livewire.commune.add-commune-modal', compact('communes'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();
        $this->title = "Commune ".$this->title.$this->name;
        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                "title"=> $this->title,
                'name'=> $this->name,
                'region_name'=> $this->region_name,
                 'mayor_name' => $this->mayor_name,
                'phone_number'=> $this->phone_number,
                'address'=> $this->address,
                'treasury_name'=> $this->treasury_name,
                'treasury_address'=> $this->treasury_address,
                'treasury_rib'=> $this->treasury_rib,
            ];

            $commune = Commune::find($this->commune_id) ?? Commune::getFirstCommune()?? Commune::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $commune->$k = $v;
                }
                $commune->save();
            }

            if ($this->edit_mode) {
                // Emit a success event with a message
                $this->dispatch('success', __('Commune updated'));
            } else {
                // Emit a success event with a message
                $this->dispatch('success', __('New Commune created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Commune::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Commune successfully deleted');
    }

    public function updateCommune($id)
    {
        $this->edit_mode = true;


        $commune = Commune::find($id);

        $this->commune_id = $commune->id;
        $this->name = $commune->name;
        $this->region_name =$commune->region_name ;
        $this->title = $commune->title;

        $this->mayor_name = $commune->mayor_name;
        $this->phone_number = $commune->phone_number;
        $this->address = $commune->address;
        $this->treasury_name = $commune->treasury_name;
        $this->treasury_address = $commune->treasury_address;
        $this->treasury_rib = $commune->treasury_rib;

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
