<?php

namespace App\Livewire\Taxpayer;

use App\Events\TaxpayerAction;
use App\Models\Erea;
use App\Models\Town;
use App\Models\Zone;
use App\Models\Canton;
use App\Models\Gender;
use App\Models\IdType;
use Livewire\Component;
use App\Models\Taxpayer;
use Illuminate\Http\Request;
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
    public $town_id;
    public $erea_id;
    public $address;
    public $zone_id;

    public $file_no;
    public $category_work;
    public $work;
    public $other_work;
    public $authorisation;
    public $auth_reference;
    public $nif;
    public $social_work;
    //public $zone_id;

    public $avatar;
    public $saved_avatar;


    public $towns = [];
    public $ereas = [];

    public $edit_mode = false;

    // protected $rules = [
    //     'name' => 'required|string',
    //     'email' => 'required|email',
    //     'gender' => 'required',
    //     'id_type' => 'required',
    //     'id_number' => 'required|string',
    //     'mobilephone' => 'required|string|min:10|max:10',
    //     'telephone' => 'required|string|min:10|max:10',
    //     'longitude' => 'nullable',
    //     'latitude' => 'nullable',
    //     'address' => 'required|string',
    //     //'canton' => 'required',
    //     'town_id' => 'required',
    //     'erea_id' => 'required',
    //     'zone_id' => 'required',
    //     'avatar' => 'nullable|sometimes|image|max:1024',
    // ];

    protected function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'nullable|sometimes|email',
            'gender' => 'required',
            'id_type' => 'required',
            'id_number' => 'nullable',

             'telephone' => 'nullable',
            //  [
            //     'required',
            //     'string',
            //     'min:8',
            //     'max:8',
            //     new \App\Rules\ValidPhoneNumber,
            // ],

            'mobilephone' => [
                'required',
                'string',
                'min:8',
                'max:8',
                new \App\Rules\ValidPhoneNumber,
            ],
            'longitude' => 'nullable|sometimes|string',
            'latitude' => 'nullable|sometimes|string',
            'address' => 'nullable|sometimes|string',

            // 'file_no' => 'required',
            // 'category_work' => 'required',
            // 'work' => 'required',
            // 'other_work' => 'required',
            'authorisation' => 'required|string',
            // 'auth_reference' => 'required',
            // 'nif' => 'required',
            // 'social_work' => 'required',

            //'canton' => 'required',
            'town_id' => 'required|int',
            'erea_id' => 'required|int',
            'zone_id' => 'required|int',
            'avatar' => 'nullable|sometimes|image|max:1024',
        ];
    }

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_taxpayer' => 'updateTaxPayer',
        'load_drop' => 'loadDrop',
    ];


    public function render()
    {
        $cantons = Canton::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        $genders = Gender::all();
        $id_types = IdType::all();
        $zones = Zone::all();

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : [];
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() :  [];

        return view('livewire.taxpayer.add-taxpayer-modal', compact('cantons', 'genders', 'id_types', 'zones'));
    }

    public function submit(Request $request)
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
                'address' => $this->address,

                'file_no' => $this->file_no,
                'category_work' => $this->category_work,
                'work' => $this->work,
                'other_work' => $this->other_work,
                'authorisation' => $this->authorisation,
                'auth_reference' => $this->auth_reference,
                'nif' => $this->nif,
                'social_work' => $this->social_work,

                //'canton' => $this->canton,
                'town_id' => $this->town_id,
                'erea_id' => $this->erea_id,
                'zone_id' => $this->zone_id,
            ];

            //dd($data);

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

            $taxpayerActionData = [];
            $taxpayerActionData['status'] = $this->edit_mode ? 204 : 201;
            $taxpayerActionData['taxpayerId'] = $taxpayer->id;
            $taxpayerActionData['statusText'] = $taxpayerActionData['status'] == 204 ? 'UPDATED' : 'CREATED' ;

            event(new TaxpayerAction(request(),$taxpayerActionData));

        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    // public function loadDrop($id)
    // {
    //     //dd($id);
    // }

    public function updatedCanton($value)
    {
        // dd($value);
        //$this->taxables = Taxable::where('tax_label_id', $value)->get(); // Load taxables based on tax label ID
        $this->towns = Town::where('canton_id', $value)->get(); // Load taxables based on tax label ID

        

        // $taxpayer = Taxpayer::find($value);

        // //dd( $taxpayer);

        //$this->town_id = $taxpayer->town_id;
        //$this->erea_id = $taxpayer->erea_id;
        // //$this->zone_id = $taxpayer->zone_id;
    }

    public function updatedTownId($value)
    {
        //dd($value);
        $this->ereas = Erea::where('town_id', $value)->get(); // Load taxables based on tax label ID
        //dd($value);
        //$this->taxables = Taxable::where('tax_label_id', $value)->get(); // Load taxables based on tax label ID
        //$this->towns = Town::where('canton_id', $value)->get(); // Load taxables based on tax label ID

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

    public function updateTaxPayer($id)
    {
        //dd($id);

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
        $this->address = $taxpayer->address;

        $this->file_no = $taxpayer->file_no;
        $this->category_work = $taxpayer->category_work;
        $this->work = $taxpayer->work;
        $this->other_work = $taxpayer->other_work;
        $this->authorisation = $taxpayer->authorisation;
        $this->auth_reference = $taxpayer->auth_reference;
        $this->nif = $taxpayer->nif;
        $this->social_work = $taxpayer->social_work;

        $this->canton = $taxpayer->town->canton->id;
        $this->town_id = $taxpayer->town_id;
        $this->erea_id = $taxpayer->erea_id;
        $this->zone_id = $taxpayer->zone_id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
