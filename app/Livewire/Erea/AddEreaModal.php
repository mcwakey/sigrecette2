<?php

namespace App\Livewire\Erea;

use App\Models\Canton;
use App\Models\Erea;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddEreaModal extends Component
{
    use WithFileUploads;


    public $name;
    public $status;
    public $town_id;



    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'town_id' => 'required',


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
        //$cantons = Erea::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        //$genders = Gender::all();
        //$id_types = IdType::all();
        $erea = Erea::all();
        $cantons = Canton::all();
        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        return view('livewire.erea.add-erea-modal', compact('erea','cantons'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->name,
                'town_id' => $this->town_id
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
            $erea = Erea::find($this->id()) ?? Erea::create($data);
            //dd($data);
            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $erea->$k = $v;

                }
                $erea->save();
            }

            if ($this->edit_mode) {
                // Assign selected role for user
                //$taxable->syncRoles($this->tax_label);

                // Emit a success event with a message
                $this->dispatch('success', __('Erea updated'));
            } else {
                // Assign selected role for user
                //$taxable->assignRole($this->tax_label);

                // Send a password reset link to the user's email
                //Password::sendResetLink($taxable->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('New Erea created'));
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
