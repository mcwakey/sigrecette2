<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Payment $payment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('peut ajouter un paiement');
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission([
            'peut accepter un paiement',
            'peut comptabiliser un paiement',
        ]);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->hasPermissionTo('peut supprimer un paiement');
    }

    public function restore(User $user, Payment $payment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return false;
    }
}
