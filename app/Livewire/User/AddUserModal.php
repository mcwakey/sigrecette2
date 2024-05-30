<?php

namespace App\Livewire\User;

use App\Helpers\Constants;
use App\Models\User;
use App\Models\Zone;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddUserModal extends Component
{
    use WithFileUploads;

    public $user_id;
    public $name;
    public $email;
    public $role;
    public $zone_id;
    public $avatar;
    public $saved_avatar;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|string',
        'avatar' => 'nullable|sometimes|image|max:1024',
    ];


    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'disabeld_row' => 'disabeldUser',
        'restore_row' => 'restoreUser',
    ];

    public function render()
    {
        $roles = Role::all();
        $zones = Zone::all();

        return view('livewire.user.add-user-modal', compact('roles', 'zones'));
    }

    public function submit()
    {
        if ($this->edit_mode) {
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->user_id;
        }

        $roleNeedZone = [__('agent_recouvrement'), __('collecteur')];

        if (in_array(__($this->role), $roleNeedZone)) {
            $this->rules['zone_id'] = 'required|integer';
        } else if ($this->zone_id) {
            $this->zone_id =  null;
        }

        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new user
            $data = [
                'name' => $this->name,
            ];

            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            } else {
                $data['profile_photo_path'] = null;
            }

            if (!$this->edit_mode) {
                $data['password'] = Hash::make($this->email);
            }

            $data['email'] = $this->email;
            $data['zone_id'] = $this->zone_id;

            if (!$this->edit_mode && !Gate::forUser(auth()->user())->allows('create-user', User::class)) {
                $this->dispatch('error', Constants::NOT_PERMISSION_TO_PERFORM_ACTION);
                return false;
            }

            // Update or Create a new user record in the database
            $user = User::find($this->user_id) ?? User::create($data);

            if ($this->edit_mode && Gate::forUser(auth()->user())->allows('update-user', $user)) {
                foreach ($data as $k => $v) {
                    $user->$k = $v;
                }
                $user->save();
            } else if ($this->edit_mode) {
                $this->dispatch('error', Constants::NOT_PERMISSION_TO_PERFORM_ACTION);
                return false;
            }

            if ($this->edit_mode) {
                // Assign selected role for user
                $user->syncRoles($this->role);

                // Emit a success event with a message
                $this->dispatch('success', __('Utilisateur mis a jour avec succès'));
            } else {
                // Assign selected role for user
                $user->assignRole($this->role);

                // Emit a success event with a message
                $this->dispatch('success', __('Utilisateur créer avec succès'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {

        $user = User::find($id);

        if (!Gate::forUser(auth()->user())->allows('delete-user', $user)) {
            $this->dispatch('error', Constants::NOT_PERMISSION_TO_PERFORM_ACTION);
            return false;
        }

        // Prevent deletion of current user
        if ($id == Auth::id()) {
            $this->dispatch('error', 'La session courant ne peut etre supprimé.');
            return;
        }

        // Delete the user record with the specified ID
        $user = User::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'Utilisateur supprimer avec succès');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $user = User::find($id);

        $this->user_id = $user->id;
        $this->zone_id = $user->zone_id;
        $this->saved_avatar = $user->profile_photo_url;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles?->first()->name ?? '';
    }

    public function disabeldUser($id)
    {
        $user = User::find($id);

        if ($user && !$user ->trashed()) {
            $user->delete();
            $this->dispatch('success', 'Utilisateur désactiver avec succès');
            return true;
        }

    }

    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->find($id);

        if ($user) {
            $user->restore();
            $this->dispatch('success', 'Utilisateur activer avec succès');
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
