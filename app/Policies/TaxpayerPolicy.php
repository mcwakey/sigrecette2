<?php

namespace App\Policies;

use App\Models\Taxpayer;
use App\Models\User;

class TaxpayerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Taxpayer $taxpayer): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('peut créer un contribuable');
    }

    public function update(User $user, Taxpayer $taxpayer): bool
    {
        return $user->hasPermissionTo('peut modifier un contribuable');
    }

    public function delete(User $user, Taxpayer $taxpayer): bool
    {
        return $user->hasPermissionTo('peut désactiver un contribuable');
    }

    public function approve(User $user, Taxpayer $taxpayer): bool
    {
        return $user->hasPermissionTo('peut valider un contribuable');
    }

    public function restore(User $user, Taxpayer $taxpayer): bool
    {
        return $user->hasPermissionTo('peut activer un contribuable');
    }

    public function forceDelete(User $user, Taxpayer $taxpayer): bool
    {
        return false;
    }
}
