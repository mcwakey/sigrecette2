<?php

namespace App\Livewire\Taxpayer;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Taxpayer;
use App\Models\Town;
use App\Models\Zone;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddTaxpayerModal extends Component
{
    use WithFileUploads;

    public $taxpayer_id;
    public $tnif;
    public $name;
    public $email;
    public $gender;
    public $id_type;
    public $id_number;
    public $mobilephone;
    public $telephone;
    public $longitude;
    public $latitude;
    public $canton;
    public $town;
    public $erea;
    public $address;
    public $zone_id;
    public $avatar;
    public $saved_avatar;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'gender' => 'required',
        'id_type' => 'required',
        'id_number' => 'required|string',
        'mobilephone' => 'required|string|min:10|max:10',
        'telephone' => 'required|string|min:10|max:10',
        'longitude' => 'nullable',
        'latitude' => 'nullable',
        'canton' => 'required',
        'town' => 'required',
        'erea' => 'required',
        'address' => 'required|string',
        'zone_id' => 'required',
        'avatar' => 'nullable|sometimes|image|max:1024',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
    ];

    public function render()
    {
        $cantons = Canton::all();
        // $towns = Town::all();
        // $ereas = Erea::all();
        $genders = Gender::all();
        $id_types = IdType::all();
        $zones = Zone::all();

        // Assuming you have a public property $canton in your Livewire component
        $towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : [];
        $ereas = $this->town ? Erea::where('town_id', $this->town)->get() :  [];

        return view('livewire.taxpayer.add-taxpayer-modal', compact('cantons', 'towns', 'ereas', 'genders', 'id_types', 'zones'));
    }

    public function submit()
    {

        // dd($this->id_type);

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxpayer
            $data = [
                'name' => $this->name,
                'gender' => $this->gender,
                'id_type' => $this->id_type,
                'id_number' => $this->id_number,
                'mobilephone' => $this->mobilephone,
                'telephone' => $this->telephone,
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
                'canton' => $this->canton,
                'town' => $this->town,
                'erea' => $this->erea,
                'address' => $this->address,
                'zone_id' => $this->zone_id,
            ];

            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            } else {
                $data['profile_photo_path'] = null;
            }

            if (!$this->edit_mode) {
                $data['password'] = Hash::make($this->email);
            }

            // Update or Create a new Taxpayer record in the database
            $data['email'] = $this->email;
            $taxpayer = Taxpayer::find($this->taxpayer_id) ?? Taxpayer::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $taxpayer->$k = $v;
                }
                $taxpayer->save();
            }

            if ($this->edit_mode) {
                // Assign selected role for user
                //$taxpayer->syncRoles($this->role);

                // Emit a success event with a message
                $this->dispatch('success', __('Taxpayer updated'));
            } else {
                // Assign selected role for user
                //$taxpayer->assignRole($this->role);

                // Send a password reset link to the user's email
                //Password::sendResetLink($taxpayer->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('New Taxpayer created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current Taxpayer
        // if ($id == Auth::id()) {
        //     $this->dispatch('error', 'Taxpayer cannot be deleted');
        //     return;
        // }

        // Delete the user record with the specified ID
        Taxpayer::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Taxpayer successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
        $this->saved_avatar = $taxpayer->profile_photo_url;
        $this->name = $taxpayer->name;
        $this->email = $taxpayer->email;
        $this->gender = $taxpayer->gender;
        $this->id_type = $taxpayer->id_type;
        $this->id_number = $taxpayer->id_number;
        $this->mobilephone = $taxpayer->mobilephone;
        $this->telephone = $taxpayer->telephone;
        $this->longitude = $taxpayer->longitude;
        $this->latitude = $taxpayer->latitude;
        $this->canton = $taxpayer->canton;
        $this->town = $taxpayer->town;
        $this->erea = $taxpayer->erea;
        $this->address = $taxpayer->address;
        $this->zone_id = $taxpayer->zone_id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
