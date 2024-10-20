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
use App\Traits\DispatchesMessages;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use mysql_xdevapi\CollectionRemove;

class AddTaxpayerTaxableModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $taxpayer_taxable_id;
    public $description;
    public $seize;
    public $location;
    public $longitude;
    public $latitude;
    public $taxable_id;


    public $authorisation;
    public $auth_reference;

    public $unit;

    public $length;
    public $width;

    public $taxlabel_id;
    public $taxables=[];
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
    public $option_calculus;

    protected $rules = [
        'description' => 'required|string',
        'seize' => 'required|numeric|gt:0',
        //'location' => 'required',
        'taxable_id' => 'required|int',
        // 'taxpayer_id' => 'required',
        'width'=> 'nullable|numeric|min:0',
        'length'=> 'nullable|numeric|min:0',
        // 'penalty' => 'nullable',
        // 'penalty_type' => 'nullable',
        //'tax_label' => 'required',
        // 'tax_label_id' => 'required',

        //'longitude' => 'required',
        //'latitude' => 'required',
        // 'canton' => 'required',
        // 'town' => 'required',
        // 'erea' => 'required',
        // 'address' => 'required|string',
        // 'zone_id' => 'required',
        // 'avatar' => 'nullable|sometimes|image|max:1024',
    ];

    protected $listeners = [
        'delete_taxpayer_taxable' => 'deleteTaxpayerTaxable',
        'update_taxable' => 'updateTaxpayerTaxable',
        'add_taxpayer_taxable' => 'addTaxpayerTaxable',
        'update_checkbox' => 'updateCheckbox',
        'load_drop' => 'load_drop',
        //'loadTaxable' => 'loadTaxable',
    ];
    public $taxpayer_id;


    public function mount($id){
        $this->taxpayer_id=$id;
    }
    public function render()
    {
        $taxlabels = TaxLabel::where('category', 'LIKE', '%CATEGORY 1%')->get();
        //$taxables = Taxable::all();

        // Assuming you have a public property $canton in your Livewire component

        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        return view('livewire.taxpayer_taxable.add-taxpayer-taxable-modal', compact('taxlabels'));
    }

    public function updatedTaxlabelId($value)
    {
        $this->taxables = Taxable::where('tax_label_id', $value)->get(); // Load taxables based on tax label ID
        //$this->reset('taxables');

        //$this->taxable_id = $taxpayer_taxable->taxable_id;

        //dd($this->taxables);
        // $this->loadTaxables($value); // Call the loadTaxables method when tax label ID is updated
    }

    public function updatedTaxableId($value)
    {
        // Debugging to ensure $value is valid
        //dd($value." TaxableId");

        // Assuming $value is valid, fetch taxables based on tax label ID
        $taxables = Taxable::find($value);
        //$this->ereas = Erea::where('town_id', $value)->get(); // Load taxables based on tax label ID
        //dd($taxables);

        $this->option_calculus = $taxables?->unit_type;
        $this->unit = $taxables?->unit;

    }

    public function updatedLength($value)
    {

        if(is_numeric($value)&&$value>0){

            $this->makeCalculSeize();
        }


    }

    public function updatedWidth($value)
    {


        if(is_numeric($value)&&$value>0){

            $this->makeCalculSeize();
        }

    }
    public function makeCalculSeize()
    {


        if($this->length>0 && $this->width>0){
            $this->width =  doubleval($this->width);
            $this->length = doubleval($this->length);
            $this->seize = $this->length * $this->width;
        }

    }

    public function updateCheckbox($id)
    {
        // Find the taxpayer by ID
        //dd($id);
        $taxpayer_taxables = TaxpayerTaxable::findOrFail($id);

        // Update the invoice_id field based on the checkbox state
            //dd($taxpayer_taxables->billable);
        if ($taxpayer_taxables->billable == 0){
            $taxpayer_taxables->update([
                'billable' => '1'
            ]);
        }else {
            $taxpayer_taxables->update([
                'billable' => '0'
            ]);
        }

        //$taxpayer_taxables = TaxpayerTaxable::findOrFail($id);
        //    dd($taxpayer_taxables->billable);
    }



    public function submit()
    {
        $this->authorize('peut créer une taxation');
        $this->validate();

        //dd($this->taxpayer_id);

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->description,
                'length' => $this->length,
                'width' => $this->width,
                'seize' => $this->seize,
                'location' => $this->location,
                'taxpayer_id' => $this->taxpayer_id,
                'taxable_id' => $this->taxable_id,
                'authorisation' => $this->authorisation,
                'auth_reference' => $this->auth_reference,
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
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
                //$this->dispatch('success', __('Asset updated'));
                $this->dispatchMessage('Taxation du contribuable', 'update');
            } else {
                // Assign selected role for user
                //$taxable->assignRole($this->tax_label);

                // Send a password reset link to the user's email
                //Password::sendResetLink($taxable->only('email'));

                // Emit a success event with a message
                //$this->dispatch('success', __('New Asset created'));
                $this->dispatchMessage('Taxation du contribuable');
            }
            $this->dispatch('updatesTaxpayerTaxables', ['id' => $this->taxpayer_id]);
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteTaxpayerTaxable($id)
    {

        // Prevent deletion of current Taxable
        // if ($id == Auth::id()) {
        //     $this->dispatch('error', 'Taxable cannot be deleted');
        //     return;
        // }

        // Delete the user record with the specified ID
        TaxpayerTaxable::destroy($id);

        $this->dispatchMessage('Taxation du contribuable', 'delete');
    }

    public function updateTaxpayerTaxable($id)
    {
        $this->edit_mode = true;

        $taxpayer_taxable = TaxpayerTaxable::find($id);

        $this->taxpayer_taxable_id = $taxpayer_taxable->id;
        $this->description = $taxpayer_taxable->name;
        $this->length = $taxpayer_taxable->length;
        $this->width = $taxpayer_taxable->width;
        $this->seize = $taxpayer_taxable->seize;
        $this->location = $taxpayer_taxable->location;
        $this->longitude = $taxpayer_taxable->longitude;
        $this->latitude = $taxpayer_taxable->latitude;
        $this->taxpayer_id = $taxpayer_taxable->taxpayer_id;

        $this->taxlabel_id = $taxpayer_taxable->taxable->tax_label_id;
        $this->taxables = Taxable::where('tax_label_id', $taxpayer_taxable->taxable->tax_label_id)->get();

        $this->taxable_id = $taxpayer_taxable->taxable_id;
        //dd($this->taxable_id);

        $this->authorisation = $taxpayer_taxable->authorisation;
        $this->auth_reference = $taxpayer_taxable->auth_reference;
        $this->option_calculus = $taxpayer_taxable->taxable->unit_type;
    }

    public function addTaxpayerTaxable($id)
    {
        //dd($id);
        $taxpayer = Taxpayer::find($id);

        $this->taxpayer_id = $taxpayer->id;
    }

    // public function loadTaxable($id)
    // {
    //     $taxables = Taxable::find($id);


    //     return $this->taxlabel ? Taxable::where('taxlabel_id', $this->taxlabel)->get() : collect();
    // }
    #[On('updateSharedTaxpayerId')]
    public function updateSharedTaxpayerId($id){


        $taxpayer = Taxpayer::find($id);
        if($taxpayer instanceof Taxpayer){
            $this->taxpayer_id = $taxpayer->id;
        }

    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
