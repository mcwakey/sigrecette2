<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions_by_role = [
            'administrateur_system' => [
                // Taxpayer Taxable permissions
                'peut créer une taxation',
                'peut modifier une taxation',
                'peut supprimer une taxation',

                // Invoice permissions
                'peut émettre un avis',
                'peut accepter un avis',
                'peut prendre en charge un avis',
                'peut rejeter un avis',
                'peut réduire ou annuler un avis',
                'peut générer automatiquement les avis',

                // Date permissions
                'peut ajouter la date de livraison d\'un avis',

                // Taxpayer permissions
                'peut créer un contribuable',
                'peut modifier un contribuable',
                'peut supprimer un contribuable',

                // Payment permissions
                'peut ajouter un paiement',
                'peut prendre en charge un paiement',

                // Config permissions
                'peut accedeé aux paramétrages du système',

                // Order no permissions
                'peut ajouter le numéro d\'ordre de recette d\'un avis',

                // User permissions
                'peut créer un utilisateur',
                'peut modifier un utilisateur',
                'peut supprimer un utilisateur',

                // Geolocatoion permissions
                'peut voir la geolocalisation d\'un utilisateur',
                'peut voir la geolocalisation d\'un contribuable',

                // Account permissions 
                'peut voir la comptabilité',

                // Recovery permissions
                'peut voir le recouvrement', 

                // Role permissions
                'peut créer un rôle',
                'peut modifier un rôle',
                'peut supprimer un rôle',
            ],

            'administrateur' => [],

            'ordonateur' => [],

            'agent_assiette' => [],

            'agent_delegation' => [],

            'regisseur' => [],

            'agent_recouvrement' => [],

            'collecteur' =>  [],
        ];

        foreach ($permissions_by_role['administrateur_system'] as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($permissions_by_role as $role => $permissions) {
            $permissions_list = [];
            foreach ($permissions as $permission) {
                $permissions_list[] = $permission;
            }
            Role::create(['name' => $role, 'user_id' => 1])->syncPermissions($permissions_list);
        }

        User::find(1)->assignRole('administrateur_system');
        User::find(2)->assignRole('administrateur');
        User::find(3)->assignRole(['ordonateur', 'administrateur']);
        User::find(4)->assignRole('agent_delegation');
        User::find(5)->assignRole('agent_assiette');
        User::find(6)->assignRole('regisseur');
        User::find(7)->assignRole('agent_recouvrement');
    }
}
