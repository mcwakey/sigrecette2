<?php

namespace App\Livewire\Zone;

use App\Models\Zone;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddZoneModal extends Component
{
    use WithFileUploads;

    public $zone_id;
    public $name;
    public $status;
    public $edit_mode = false;
    protected $rules = [
        'name' => 'required|string',
        'status' => 'required|string',
    ];
    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateZone',
    ];
    public function render()
    {
        $zones = Zone::all();
        return view('livewire.zone.add-zone-modal', ['zones' => $zones]);
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        try {
            DB::transaction(function () {
                // Prepare the data for creating a new Taxable
                $data = [
                    'name' => $this->name,
                    'status' => $this->status,
                ];
                $zone = Zone::find($this->zone_id) ?? Zone::create($data);
                if ($this->edit_mode) {
                    foreach ($data as $k => $v) {
                        $zone->$k = $v;
                    }
                    $zone->save();
                }
                if ($this->edit_mode) {
                    $this->dispatch('success', __('Zone updated'));
                } else {
                    $this->dispatch('success', __('New Zone created'));
                }
            });
            // Reset the form fields after successful submission
            $this->reset();
        }catch (\Exception $e) {}
    }
    public function deleteUser($id)
    {
        try {
            Zone::destroy($id);
            // Emit a success event with a message
            $this->dispatch('success', 'Taxpayer successfully deleted');
        }catch (\Exception $e) {}

    }
    public function updateZone($id)
    {
        try {
            $this->edit_mode = true;
            $zone = Zone::find($id);
            $this->zone_id = $zone->id;
            $this->name = $zone->name;
            $this->status = $zone->status;
        }catch (\Exception $e) {}

    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
