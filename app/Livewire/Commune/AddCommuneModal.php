<?php
namespace App\Livewire\Commune;
use App\Models\Commune;
use App\Traits\DispatchesMessages;
use http\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
class AddCommuneModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;
    public $commune_id;
    public $mayor_name;
    public $phone_number;
    public $address;
    public $treasury_name;
    public $treasury_address;
    public $treasury_rib;
    public $t_title;
    public $name;
    public $region_name;
    public $latitude;
    public $longitude;
    public $limit_json;
    public $edit_mode = false;
    public $url;
    public $qr_code_enabled=false;
    public $carry_forward_previous_year=false;
    public $email;
    public $logo;
    public $saved_logo;
    protected $rules = [
        "t_title" => 'required|string',
        'name' => 'required|string',
        'region_name' => 'required|string',
        'mayor_name' => 'required|string',
        'phone_number' => 'required|string',
        'address' => 'required|string',
        'treasury_name' => 'required|string',
        'treasury_address' => 'required|string',
        'logo' => 'nullable|sometimes|image|mimes:jpeg,png,jpg|max:1024',
        'treasury_rib' => 'nullable|sometimes|string',
        'qr_code_enabled' => 'required|boolean',
        'carry_forward_previous_year' => 'required|boolean',
    ];
    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateCommune',
    ];
    public function render()
    {
        return view('livewire.commune.add-commune-modal');
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        DB::transaction(function () {
            $title = $this->t_title . " " . $this->name;
            $string_data = null;
            if ($this->limit_json) {
                $content = $this->limit_json->store('uploads', 'public');
                $string_data = json_decode(file_get_contents(storage_path('app/public/' . $content)), true);
            }
            // Prepare the data for creating a new Taxable
            $data = [
                "title" => $title,
                'name' => $this->name,
                'region_name' => $this->region_name,
                'mayor_name' => $this->mayor_name,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
                'treasury_name' => $this->treasury_name,
                'treasury_address' => $this->treasury_address,
                'treasury_rib' => $this->treasury_rib,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'email' => $this->email,
                'url' => $this->url,
                'qr_code_enabled' => $this->qr_code_enabled,
                'carry_forward_previous_year' => $this->carry_forward_previous_year,
            ];
            if ($this->logo) {
                $data['logo_path'] = $this->logo->store('logo', 'public');
            }
            if ($this->limit_json) {
                $data['limit_json'] = json_encode($string_data['geometry']['coordinates'][0][0]);
            }
            $commune = Commune::find($this->commune_id) ?? Commune::getFirstCommune() ?? Commune::create($data);
            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $commune->$k = $v;
                }
                $commune->save();
            }
            if ($this->edit_mode) {
                $this->dispatchMessage('Commune', 'update');
            } else {
                $this->dispatchMessage('Commune');
            }
        });
        // Reset the form fields after successful submission
        $this->reset();
    }
    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Commune::destroy($id);
        $this->dispatchMessage('Commune', 'delete');
    }
    public function updateCommune($id)
    {
        $this->edit_mode = true;
        $commune = Commune::find($id);
        $this->saved_logo = $commune->logo_path;
        $this->commune_id = $commune->id;
        $this->name = $commune->name;
        $t_title = str_replace($this->name, '', $commune->title);
        $this->t_title = trim($t_title);
        $this->region_name = $commune->region_name;
        $this->mayor_name = $commune->mayor_name;
        $this->phone_number = $commune->phone_number;
        $this->address = $commune->address;
        $this->treasury_name = $commune->treasury_name;
        $this->treasury_address = $commune->treasury_address;
        $this->treasury_rib = $commune->treasury_rib;
        $this->latitude = $commune->latitude;
        $this->longitude = $commune->longitude;
        $this->url = $commune->url;
        $this->email = $commune->email;
        $this->qr_code_enabled= $commune->qr_code_enabled;
        $this->carry_forward_previous_year= $commune->carry_forward_previous_year;
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
