<?php

namespace App\Livewire\Taxable;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Town;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddTaxableModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $taxable_id;
    public $name;
    public $tariff;
    public $tariff_type;
    public $unit;
    public $unit_type;
    public $modality;
    public $periodicity;
    public $penalty;
    public $penalty_type;
    public $tax_label_id;
    public $use_second_formula=false;

    // public $longitude;
    // public $latitude;
    // public $canton;
    // public $town;
    // public $erea;
    // public $address;
    // public $zone_id;
    // public $avatar;
    // public $saved_avatar;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string|unique:taxables,name,',
        'tariff' => 'required|numeric',
        'tariff_type' => 'required|string',
        'unit' => 'required|string',
        'unit_type' => 'required|string',
        'periodicity' => 'required|string',
        'tax_label_id' => 'required|int',

    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
    ];

    public function render()
    {
        //$cantons = Canton::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        //$genders = Gender::all();
        //$id_types = IdType::all();
        $tax_labels = TaxLabel::all();







        return view('livewire.taxable.add-taxable-modal', compact('tax_labels'));
    }
    public function mount()

    {
        if($this->edit_mode){
            $taxable = Taxable::find($this->taxable_id);

            if($taxable){
                $this->use_second_formula =(bool)$taxable->use_second_formula;
            }
        }
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        DB::transaction(function () {
            $data = [
                'name' => $this->name,
                'tariff' => $this->tariff,
                'tariff_type' => $this->tariff_type,
                'unit' => $this->unit,
                'unit_type' => $this->unit_type,
                'modality' => $this->modality,
                'periodicity' => $this->periodicity,
                'penalty' => $this->penalty,
                'penalty_type' => $this->penalty_type,
                'tax_label_id' => $this->tax_label_id,
                'use_second_formula'=>$this->use_second_formula
                // 'latitude' => $this->latitude,
                // 'canton' => $this->canton,
                // 'town' => $this->town,
                // 'erea' => $this->erea,
                // 'address' => $this->address,
                // 'zone_id' => $this->zone_id,
            ];

            // if ($this->avatar) {
            //     $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            // } else {
            //     $data['profile_photo_path'] = null;
            // }

            // if (!$this->edit_mode) {
            //     $data['password'] = Hash::make($this->email);
            // }

            // Update or Create a new Taxable record in the database
            //$data['email'] = $this->email;
            $taxable = Taxable::find($this->taxable_id) ?? Taxable::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $taxable->$k = $v;
                }
                $taxable->save();
            }


            if ($this->edit_mode) {
                $this->dispatchMessage('Matière Taxable', 'update');
            } else {
                $this->dispatchMessage('Matière Taxable');
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current Taxable
        // if ($id == Auth::id()) {
        //     $this->dispatch('error', 'Taxable cannot be deleted');
        //     return;
        // }

        // Delete the user record with the specified ID
        Taxable::destroy($id);

        // Emit a success event with a message
        //$this->dispatch('success', 'Taxable successfully deleted');
        $this->dispatchMessage('Matière Taxable', 'delete');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $taxable = Taxable::find($id);

        $this->taxable_id = $taxable->id;
        $this->use_second_formula = $taxable->use_second_formula;
        $this->tax_label_id = $taxable->tax_label_id;
        $this->name = $taxable->name;
        $this->tariff = $taxable->tariff;
        $this->tariff_type = $taxable->tariff_type;
        $this->unit = $taxable->unit;
        $this->unit_type = $taxable->unit_type;
        $this->modality = $taxable->modality;
        $this->periodicity = $taxable->periodicity;
        $this->penalty = $taxable->penalty;
        $this->penalty_type = $taxable->penalty_type;


        //$this->tax_label = $taxable->tax_labels?->first()->name ?? '';

        // $this->mobilephone = $taxable->mobilephone;
        // $this->telephone = $taxable->telephone;
        // $this->longitude = $taxable->longitude;
        // $this->latitude = $taxable->latitude;
        // $this->canton = $taxable->canton;
        // $this->town = $taxable->town;
        // $this->erea = $taxable->erea;
        // $this->address = $taxable->address;
        // $this->zone_id = $taxable->zone_id;

    }
    public function rendering($view, $data)
    {
        // Runs BEFORE the provided view is rendered...
        //
        // $view: The view about to be rendered
        // $data: The data provided to the view
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
