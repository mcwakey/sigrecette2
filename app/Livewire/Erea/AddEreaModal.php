<?php

namespace App\Livewire\Erea;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Town;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddEreaModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $canton_id;
    public $town_id;
    public $erea_id;
    public $name;
    public $status;
    public $towns = [];
    public $edit_mode = false;
    protected $rules = [
        'town_id' => 'required|int',
        'name' => 'required|string',
        'status' => 'required|string',
    ];
    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateErea',
        'load_drop' => 'loadDrop',
    ];
    public function updatedCantonId($value)
    {
        $this->towns = Town::where('canton_id', "=", $value)->get(); // Load taxables based on tax label ID
    }
    public function render()
    {
        $cantons = Canton::all();
        return view('livewire.erea.add-erea-modal', ['cantons' => $cantons]);
    }
    public function submit()
    {
        // Validate the form input data
        $this->validate();
        try {
            DB::transaction(function () {
                // Prepare the data for creating a new Taxable
                $data = [
                    'town_id' => $this->town_id,
                    'name' => $this->name,
                    'status' => $this->status,
                ];
                $erea = Erea::find($this->erea_id) ?? Erea::create($data);
                if ($this->edit_mode) {
                    foreach ($data as $k => $v) {
                        $erea->$k = $v;
                    }
                    $erea->save();
                }
                if ($this->edit_mode) {
                    $this->dispatchMessage('Quartier', 'update');
                } else {
                    $this->dispatchMessage('Quartier');
                }
            });
            // Reset the form fields after successful submission
            $this->reset();
        }catch (\Exception $e) {

        }

    }
    public function deleteUser($id)
    {
        try {
            Erea::destroy($id);
            $this->dispatchMessage('Quartier', 'delete');
        }catch (\Exception $e) {

        }

    }
    public function updateErea($id)
    {
        try {
            $this->edit_mode = true;
            $erea = Erea::find($id);
            $this->town_id = $erea->town_id;
            $this->erea_id = $erea->id;
            $this->name = $erea->name;
            $this->status = $erea->status;
        }catch (\Exception $e) {

        }

    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
