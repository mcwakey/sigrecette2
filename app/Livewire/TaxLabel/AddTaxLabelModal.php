<?php

namespace App\Livewire\TaxLabel;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\TaxLabel;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddTaxLabelModal extends Component
{
    use WithFileUploads;

    // public $tax_label_id;
    public $tax_label_id;
    public $name;
    public $category;
    public $code;
    // public $modality;
    // public $periodicity;
    // public $penalty;
    // public $penalty_type;

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
        'category' => 'required',
        'code' => 'required',
        // 'modality' => 'required',
        // 'periodicity' => 'required|string',
        // 'penalty' => 'nullable',
        // 'penalty_type' => 'nullable',
        // //'tax_label' => 'required',
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
        //$id_types = IdType::all();
        // $tax_labels = TaxLabel::all();

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

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
                // 'modality' => $this->modality,
                // 'periodicity' => $this->periodicity,
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
                // Assign selected role for user
                //$tax_label->syncRoles($this->tax_label);

                // Emit a success event with a message
                $this->dispatch('success', __('TaxLabel updated'));
            } else {
                // Assign selected role for user
                //$tax_label->assignRole($this->tax_label);

                // Send a password reset link to the user's email
                //Password::sendResetLink($tax_label->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('New TaxLabel created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current TaxLabel
        // if ($id == Auth::id()) {
        //     $this->dispatch('error', 'TaxLabel cannot be deleted');
        //     return;
        // }

        // Delete the user record with the specified ID
        TaxLabel::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'TaxLabel successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $tax_label = TaxLabel::find($id);

        $this->tax_label_id = $tax_label->id;
        //$this->saved_avatar = $tax_label->profile_photo_url;
        $this->name = $tax_label->name;
        $this->category = $tax_label->category;
        $this->code = $tax_label->code;
        // $this->modality = $tax_label->modality;
        // $this->periodicity = $tax_label->periodicity;
        // $this->penalty = $tax_label->penalty;
        // $this->penalty_type = $tax_label->penalty_type;
        
        //$this->tax_label = $tax_label->tax_labels?->first()->name ?? '';

        // $this->mobilephone = $tax_label->mobilephone;
        // $this->telephone = $tax_label->telephone;
        // $this->longitude = $tax_label->longitude;
        // $this->latitude = $tax_label->latitude;
        // $this->canton = $tax_label->canton;
        // $this->town = $tax_label->town;
        // $this->erea = $tax_label->erea;
        // $this->address = $tax_label->address;
        // $this->zone_id = $tax_label->zone_id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
