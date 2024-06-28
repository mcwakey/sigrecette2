<?php

namespace App\Livewire\Taxpayer;

use App\Events\TaxpayerAction;
use App\Models\Activity;
use App\Models\Erea;
use App\Models\Town;
use App\Models\Zone;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Gender;
use App\Models\IdType;
use App\Traits\DispatchesMessages;
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
    use DispatchesMessages;

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
    public $address;
    public $zone_id;

    public $file_no;
    public $category_id;
    public $activity_id;
    public $other_work;
    public $authorisation;
    public $auth_reference;
    public $nif;
    public $social_work;

    public $avatar;
    public $saved_avatar;

    public $towns = [];
    public $activities = [];

    public $edit_mode = false;

    protected function rules()
    {

        $rules =  [
            'name' => 'required|string',
            'email' => 'nullable|sometimes|email',
            'gender' => 'required',

            'telephone' => 'nullable',

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
            'category_id' => 'required|int',
            'activity_id' => 'required|int',
            // 'other_work' => 'required',

            'authorisation' => 'required|string',

            // 'auth_reference' => 'required',
            // 'nif' => 'required',
            // 'social_work' => 'required',

            'canton' => 'required',
            'town_id' => 'required|int',
            'zone_id' => 'required|int',
            'avatar' => 'nullable|sometimes|image|max:1024',
        ];

        if($this->id_type != 'PAS DE CARTE'){
            $rules['id_type'] = 'required';
            $rules['id_number'] = 'required';
        }

        if(strtolower($this->authorisation) == 'yes'){
            $rules['auth_reference'] = 'required';
        }

        return $rules;
    }

    protected $listeners = [
        'delete_taxpayer' => 'deleteUser',
        'restore_taxpayer' => 'restoreUser',
        'update_taxpayer' => 'updateTaxPayer',
        'load_drop' => 'loadDrop',
    ];


    public function render()
    {
        $cantons = Canton::where('status',"ACTIVE")->get();
        $genders = Gender::all();
        $id_types = IdType::all();
        $zones = Zone::all();
        $categories = Category::all();

        return view('livewire.taxpayer.add-taxpayer-modal', compact('cantons', 'genders', 'id_types', 'zones', 'categories'));
    }

    public function submit(Request $request)
    {

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
                'category_id' => $this->category_id,
                'activity_id' => $this->activity_id,
                'other_work' => $this->other_work,
                'authorisation' => $this->authorisation,
                'auth_reference' => $this->auth_reference,
                'nif' => $this->nif,
                'social_work' => $this->social_work,

                'town_id' => $this->town_id,
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
                $this->dispatchMessage('Contribuable', 'update');
            } else {
                $this->dispatchMessage('Contribuable');
            }

            $taxpayerActionData = [];
            $taxpayerActionData['status'] = $this->edit_mode ? 204 : 201;
            $taxpayerActionData['taxpayerId'] = $taxpayer->id;
            $taxpayerActionData['statusText'] = $taxpayerActionData['status'] == 204 ? 'UPDATED' : 'CREATED';

            event(new TaxpayerAction(request(), $taxpayerActionData));
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function updatedCanton($value)
    {
        $this->towns = Town::where('canton_id', $value)->where('status',"ACTIVE")->get();
    }

    public function updatedCategoryId($value)
    {
        $this->activities = Activity::where('category_id', $value)->get();
    }

    public function deleteUser($id)
    {
        $taxpayer = Taxpayer::find($id);


            if ($taxpayer &&!$taxpayer->trashed()) {
                $taxpayer->delete();
                $this->dispatchMessage('Contribuable', 'delete');

            }

    }
    public function restoreUser($id)
    {
        $taxpayer = Taxpayer::onlyTrashed()->find($id);

        if ($taxpayer) {
            $taxpayer->restore();
            $this->dispatchMessage('Contribuable', 'restore');
        }
    }


    public function updateTaxPayer($id)
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
        $this->address = $taxpayer->address;

        $this->file_no = $taxpayer->file_no;
        $this->category_id = $taxpayer->category_id;
        $this->activity_id = $taxpayer->activity_id;
        $this->other_work = $taxpayer->other_work;
        $this->authorisation = $taxpayer->authorisation;
        $this->auth_reference = $taxpayer->auth_reference;
        $this->nif = $taxpayer->nif;
        $this->social_work = $taxpayer->social_work;

        $this->canton = $taxpayer->town->canton->id;
        $this->town_id = $taxpayer->town_id;
        $this->zone_id = $taxpayer->zone_id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
