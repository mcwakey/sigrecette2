<?php

namespace App\Livewire\Activity;

use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class AddActivityModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $category_id;
    public $activity_id;
    public $name;
    public $status;
    public $edit_mode = false;
    protected $rules = [
        'category_id' => 'required|int',
        'name' => 'required|string',
        'status' => 'required|string',
    ];
    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_activity' => 'updateActivity',
    ];
    public function render()
    {
        $categories = Category::all();
        return view('livewire.activity.add-activity-modal', ['categories' => $categories]);
    }
    public function submit()
    {
        try {
            $this->validate();
            DB::transaction(function () {
                // Prepare the data for creating a new Taxable
                $data = [
                    'category_id' => $this->category_id,
                    'name' => $this->name,
                    'status' => $this->status,
                ];
                $activity = Activity::find($this->activity_id) ?? Activity::create($data);
                if ($this->edit_mode) {
                    foreach ($data as $k => $v) {
                        $activity->$k = $v;
                    }
                    $activity->save();
                }
                if ($this->edit_mode) {
                    $this->dispatchMessage('Activité', 'update');
                } else {
                    $this->dispatchMessage('Activité');
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
            Activity::destroy($id);
            $this->dispatchMessage('Activité', 'delete');
        }catch (\Exception $e) {

        }

    }
    public function updateActivity($id)
    {
        try {
            $this->edit_mode = true;
            $activity = Activity::find($id);
            $this->category_id = $activity->category_id;
            $this->activity_id = $activity->id;
            $this->name = $activity->name;
            $this->status = $activity->status;
        }catch (\Exception $e) {

        }

    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
