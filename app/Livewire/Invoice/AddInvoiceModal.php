<?php

namespace App\Livewire\Invoice;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Invoice;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddInvoiceModal extends Component
{
    //use WithFileUploads;

    public $name;
    public $invoice_id;
    public $invoice_no;
    public $order_no;
    public $nic;
    public $status;
    public $taxpayer_id;

    public $tnif;

    //public $created_at;
    // public $mobilephone;
    // public $email;
    // public $latitude;
    // public $canton;
    // public $town;
    // public $erea;
    // public $address;
    // public $zone_id;

    //public $avatar;
    //public $saved_avatar;

    // public $selectedTaxpayerId;

    // public function selectTaxpayer($taxpayerId)
    // {
    //     $this->selectedTaxpayerId = $taxpayerId;

    //     dd($this->selectedTaxpayerId);
    // }

    public $edit_mode = false;

    protected $rules = [
        // 'invoice_id' => 'required|string',
        'invoice_no' => 'required',
        'order_no' => 'required',
        'nic' => 'required',
        'status' => 'required|string',
        //'taxpayer_id' => 'required',

        // 'telephone' => 'required|string|min:10|max:10',
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

    // public $taxpayer_id; // Define public property to hold taxpayer_id
    
    // // Constructor to accept taxpayer_id
    // public function mount($taxpayer_id)
    // {
    //     $this->taxpayer_id = $taxpayer_id;
    // }

    public function render()
    {
        //$cantons = Canton::all();
        //$towns = Town::all();
        //$ereas = Erea::all();
        //$genders = Gender::all();
        //$id_types = IdType::all();
        $taxpayers = Taxpayer::all();
        //$taxpayer_taxables = TaxpayerTaxable::all();

        $taxpayer_taxables = $this->invoice_id ? TaxpayerTaxable::where('invoice_id', $this->invoice_id)->get() : collect();
        $taxpayer_id = $this->taxpayer_id;

        // Assuming you have a public property $canton in your Livewire component
        //$towns = $this->canton ? Town::where('canton_id', $this->canton)->get() : collect();
        //$ereas = $this->town ? Erea::where('town_id', $this->town)->get() : collect();

        //return view('livewire.invoice.add-invoice-modal', ['taxpayer_id' => $this->taxpayer_id]);
    
        return view('livewire.invoice.add-invoice-modal', compact('taxpayers','taxpayer_taxables','taxpayer_id'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new invoice
            $data = [
                'invoice_no' => $this->invoice_no,
                'order_no' => $this->order_no,
                'nic' => $this->nic,
                'status' => $this->status,
                //'taxpayer_id' => $this->taxpayer_id,

                // 'telephone' => $this->telephone,
                // 'longitude' => $this->longitude,
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

            // Update or Create a new invoice record in the database
            //$data['email'] = $this->email;
            $invoice = Invoice::find($this->invoice_id) ?? Invoice::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $invoice->$k = $v;
                }
                $invoice->save();
            }

            if ($this->edit_mode) {
                // Assign selected role for user
                //$taxpayer->syncRoles($this->role);

                // Emit a success event with a message
                $this->dispatch('success', __('Invoice updated'));
            } else {
                // Assign selected role for user
                //$taxpayer->assignRole($this->role);

                // Send a password reset link to the user's email
                //Password::sendResetLink($taxpayer->only('email'));

                // Emit a success event with a message
                $this->dispatch('success', __('New Invoice created'));
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
        Invoice::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Invoice successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $invoice = Invoice::find($id);
        //$taxpayer_taxables = TaxpayerTaxable::where('invoice_id', $id)->get();
        //$taxpayer = Taxpayer::where('name', 'John Doe')->first();


        //$this->taxpayer_id = $id;
        $this->invoice_id = $invoice->id;
        //$this->saved_avatar = $invoice->profile_photo_url;
        $this->invoice_no = $invoice->invoice_no;
        $this->order_no = $invoice->order_no;
        $this->nic = $invoice->nic;
        $this->status = $invoice->status;
        //$this->created_at = $invoice->created_at->format('Y-m-d');
        //$this->name = $invoice->taxpayer->name;
        //$this->email = $invoice->taxpayer->email;

        //$this->mobilephone = $invoice->taxpayer->mobilephone;

        // $this->telephone = $taxpayer->telephone;
        //$this->longitude = $invoice->taxpayer->longitude;
        $this->tnif = $invoice->taxpayer->tnif;
        //$this->canton = $invoice->taxpayer->canton;
        //$this->town = $invoice->taxpayer->town;
        //$this->erea = $invoice->taxpayer->erea;
        //$this->address = $invoice->taxpayer->address;
        //$this->zone_id = $invoice->taxpayer->zone_id;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
