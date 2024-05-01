<?php

namespace App\Livewire\Permission;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleModal extends Component
{
    public $name;
    public $checked_permissions;
    public $check_all;
    public $edit_mode;

    public Role $role;
    public Collection $permissions;

    protected $rules = [
        'name' => 'required|string|unique:roles,name',
    ];

    // 'name' => 'required|string|unique:users,name,' . $this->id,

    // This is the list of listeners that this component listens to.
    protected $listeners = [
        'modal.show.role_name' => 'mountRole',
        'delete_role' => 'deleteRole',
    ];

    // This function is called when the component receives the `modal.show.role_name` event.
    public function mountRole($role_name = '')
    {
        if (empty($role_name)) {
            // Create new
            $this->role = new Role;
            $this->name = '';
            return;
        }

        // Get the role by name.
        $role = Role::where('name', $role_name)->first();
        if (is_null($role)) {
            $this->dispatch('error', 'Le role séléctioner [' . $role_name . '] est introuvable');
            return;
        }

        $this->role = $role;

        // Set the name and checked permissions properties to the role's values.
        $this->name = $this->role->name;
        $this->checked_permissions = $this->role->permissions->pluck('name');
    }

    // This function is called when the component is mounted.
    public function mount()
    {
        // Get all permissions.
        $this->permissions = Permission::all();

        // Set the checked permissions property to an empty array.
        $this->checked_permissions = [];
    }

    // This function renders the component's view.
    public function render()
    {
        // Create an array of permissions grouped by ability.
        $permissions_by_group = [];
        foreach ($this->permissions ?? [] as $permission) {
            $ability = Str::after($permission->name, ' ');

            $permissions_by_group[$ability][] = $permission;
        }

        // Return the view with the permissions_by_group variable passed in.
        return view('livewire.permission.role-modal', compact('permissions_by_group'));
    }

    // This function submits the form and updates the role's permissions.
    public function submit()
    {
        (!empty($this->name) || !is_null($this->name)) && Role::where('name', $this->name)->first() ? $this->edit_mode = true : $this->edit_mode = false;

        if ($this->edit_mode) {
            $this->rules['name'] = 'required|string|unique:roles,name,' . $this->role->id;
        }

        $this->validate();

        if ($this->edit_mode) {
            $this->role->name = $this->name;
            $this->role->syncPermissions($this->checked_permissions);
            $this->dispatch('success', 'Permissions pour ' . ucwords($this->role->name) . ' mis a jour avec succès');
        } else {
            /**@var App\Models\User*/
            $user = auth()->user();

            $role = Role::create([
                'name' => $this->name,
                'user_id' => $user->hasRole('administrateur_system') ? 0 : null,
            ]);

            $role->syncPermissions($this->checked_permissions);
            $this->dispatch('success', 'Role ' . ucwords($role->name) . ' créer avec succès');
        }
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        $name = $role?->name;
        $role->delete();
        $this->dispatch('success', 'Role ' . ucwords($name) . ' supprimé avec succès');
    }

    // This function checks all of the permissions.
    public function checkAll()
    {
        // If the check_all property is true, set the checked permissions property to all of the permissions.
        if ($this->check_all) {
            $this->checked_permissions = $this->permissions->pluck('name');
        } else {
            // Otherwise, set the checked permissions property to an empty array.
            $this->checked_permissions = [];
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
