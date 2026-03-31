<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission([
            'peut émettre un avis au comptant',
            'peut émettre un avis sur titre',
        ]);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyPermission([
            'peut accepter un avis sur titre',
            'peut prendre en charge un avis sur titre',
            'peut ajouter la date de livraison d\'un avis',
            'peut ajouter le numéro d\'ordre de recette d\'un avis',
        ]);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyPermission([
            'peut rejeter un avis sur titre (agent par délégation de l\'ordonateur)',
            'peut rejeter un avis sur titre (agent par délégation du receveur)',
        ]);
    }

    public function reduce(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyPermission([
            'peut réduire un avis sur titre',
            'peut réduire un avis au comptant',
        ]);
    }

    public function restore(User $user, Invoice $invoice): bool
    {
        return false;
    }

    public function forceDelete(User $user, Invoice $invoice): bool
    {
        return false;
    }
}
