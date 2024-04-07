<?php

namespace App\Livewire\Category;

use App\Models\Category;
use App\Traits\DispatchesMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class AddCategoryModal extends Component
{
    use WithFileUploads;
    use DispatchesMessages;

    public $category_id;

    public $name;
    public $status;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'status' => 'required|string',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateCategory',
    ];

    public function render()
    {
        return view('livewire.category.add-category-modal');
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Taxable
            $data = [
                'name' => $this->name,
                'status' => $this->status,
            ];

            $category = Category::find($this->category_id) ?? Category::create($data);

            if ($this->edit_mode) {
                foreach ($data as $k => $v) {
                    $category->$k = $v;
                }
                $category->save();
            }
            if ($this->edit_mode) {
                $this->dispatchMessage('Catégorie', 'update');
            } else {
                $this->dispatchMessage('Catégorie');
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Delete the user record with the specified ID
        Category::destroy($id);

        // Emit a success event with a message
        $this->dispatchMessage('Catégorie', 'delete');
    }

    public function updateCategory($id)
    {
        $this->edit_mode = true;

        $category = Category::find($id);

        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->status = $category->status;
    }


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
