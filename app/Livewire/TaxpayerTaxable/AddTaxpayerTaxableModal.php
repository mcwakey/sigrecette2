<?php

namespace App\Livewire\TaxpayerTaxable;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddTaxpayerTaxableModal extends Component
{
    use WithFileUploads;

    public $taxpayer_taxable_id;
    public $name;
    public $seize;
    public $location;
    public $taxable_id;
    public $taxpayer_id;

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
        'name' => 'required|string',
        'seize' => 'required',
        'location' => 'nullable',
        'taxable_id' => 'required',
        'taxpayer_id' => 'required',

        // 'penalty' => 'nullable',
        // 'penalty_type' => 'nullable',
        //'tax_label' => 'required',
        // 'tax_label_id' => 'required',

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
        //$taxpayers = Taxpayer::all();
        $taxables = Taxable::all();

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        return view('livewire.taxpayer_taxable.add-taxpayer-taxable-modal', compact('taxables'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->name,
                'seize' => $this->seize,
                'location' => $this->location,
                'taxpayer_id' => $this->taxpayer_id,
                'taxable_id' => $this->taxable_id,
                // 'penalty' => $this->penalty,
                // 'penalty_type' => $this->penalty_type,
                // 'tax_label_id' => $this->tax_label_id,

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
            $taxpayer_taxable = TaxpayerTaxable::find($this->taxpayer_taxable_id) ?? TaxpayerTaxable::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $taxpayer_taxable->$k = $v;
                }
                $taxpayer_taxable->save();
            }

            if ($this->edit_mode) {
                // Assign selected role for user
                //$taxable->syncRoles($this->tax_label);

                // Emit a success event with a message
                $this->dispatch('success', __('Asset updated'));
            } else {
                // Assign selected role for user
                //$taxable->assignRole($this->tax_label);

                // Send a password reset link to the user's email
                //Password::sendResetLink($taxable->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('New Asset created'));
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
        TaxpayerTaxable::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Asset successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $taxpayer_taxable = TaxpayerTaxable::find($id);

        $this->taxpayer_taxable_id = $taxpayer_taxable->id;
        //$this->saved_avatar = $taxable->profile_photo_url;
        $this->name = $taxpayer_taxable->name;
        $this->seize = $taxpayer_taxable->seize;
        $this->location = $taxpayer_taxable->location;
        $this->taxable_id = $taxpayer_taxable->taxable_id;
        $this->taxpayer_id = $taxpayer_taxable->taxpayer_id;
        // $this->penalty = $taxpayer_taxable->penalty;
        // $this->penalty_type = $taxpayer_taxable->penalty_type;
        
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
