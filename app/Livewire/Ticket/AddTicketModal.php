<?php

namespace App\Livewire\Ticket;

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

class AddTicketModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;


    public $taxable_id;
    public $name;
    public $tariff;
    // public $tariff_type;
    public $unit;
    // public $unit_type;
    // public $modality;
    // public $periodicity;
    // public $penalty;
    // public $penalty_type;
    // public $tax_label_id;

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
        'name' => 'required',
        'tariff' => 'required|numeric',
        //'tariff_type' => 'required',
        'unit' => 'required',
        //'unit_type' => 'required',
        //'modality' => 'required',
        //'periodicity' => 'required',
        //'penalty' => 'nullable',
        //'penalty_type' => 'nullable',
        //'tax_label' => 'required',
        //'tax_label_id' => 'required',

        // 'longitude' => 'nullable',
        // 'latitude' => 'nullable',
        // 'canton' => 'required',
        // 'town' => 'required',
        // 'erea' => 'required',
        // 'address' => 'required|string',
        // 'zone_id' => 'required',
        // 'avatar' => 'nullable|sometimes|image|max:1024',
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

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

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
                //'tariff_type' => $this->tariff_type,
                'unit' => $this->unit,
                //'unit_type' => $this->unit_type,
                //'modality' => $this->modality,
                //'periodicity' => $this->periodicity,
                //'penalty' => $this->penalty,
                //'penalty_type' => $this->penalty_type,
                //'tax_label_id' => $this->tax_label_id,

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
                // Assign selected role for user
                //$taxable->syncRoles($this->tax_label);

                // Emit a success event with a message
                $this->dispatch('success', __('Valeur inactive mis a jour'));
            } else {
                // Assign selected role for user
                //$taxable->assignRole($this->tax_label);

                // Send a password reset link to the user's email
                //Password::sendResetLink($taxable->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('Valeur inactive créer'));
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
        $this->dispatch('success', 'Valeur inactive supprimer.');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $taxable = Taxable::find($id);

        $this->taxable_id = $taxable->id;
        //$this->saved_avatar = $taxable->profile_photo_url;
        //$this->tax_label_id = $taxable->tax_label_id;
        $this->name = $taxable->name;
        $this->tariff = $taxable->tariff;
        //$this->tariff_type = $taxable->tariff_type;
        $this->unit = $taxable->unit;
        //$this->unit_type = $taxable->unit_type;
        // $this->modality = $taxable->modality;
        // $this->periodicity = $taxable->periodicity;
        // $this->penalty = $taxable->penalty;
        // $this->penalty_type = $taxable->penalty_type;

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

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
