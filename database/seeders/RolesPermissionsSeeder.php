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
                'peut émettre un avis au comptant',
                'peut émettre un avis sur titre',
                'peut accepter un avis sur titre',
                'peut prendre en charge un avis sur titre',
                'peut valider un avis au comptant',
                'peut prendre en charge un avis au comptant',
                'peut rejeter un avis au comptant',
                'peut rejeter un avis sur titre (agent par délégation de l\'ordonateur)',
                'peut rejeter un avis sur titre (agent par délégation du receveur)',
                'peut réduire un avis sur titre',
                'peut réduire un avis au comptant',
                'peut réduire un avis au comptant',
                'peut générer automatiquement les avis sur titre',

                // Date permissions
                'peut ajouter la date de livraison d\'un avis',

                // Taxpayer permissions
                'peut créer un contribuable',
                'peut modifier un contribuable',
                'peut activer un contribuable',
                'peut désactiver un contribuable',
                'peut valider un contribuable',

                // Payment permissions
                'peut ajouter un paiement',
                'peut accepter un paiement',
                'peut comptabiliser un paiement',
                'peut supprimer un paiement',

                // Order no permissions
                'peut ajouter le numéro d\'ordre de recette d\'un avis',

                // User permissions
                'peut créer un utilisateur',
                'peut modifier un utilisateur',
                'peut activer un utilisateur',
                'peut désactiver un utilisateur',
                'peut créer un collecteur',

                // Geolocatoion permissions
                'peut voir la geolocalisation d\'un contribuable',

                // Account permissions 
                'peut voir la comptabilité',
                'peut effectuer une demande d\'approvisionement de valeur inactive',
                'peut effectuer un versement au régisseur',
                'peut effectuer un versement au receveur',
                'peut effectuer une alimentation des collecteurs',
                'peut faire un etat de compte du collecteur',
                'peut faire un etat de compte du régisseur',

                // Print permissions
                // 'peut imprimer des fiches',

                // Recovery permissions
                'peut voir le recouvrement',

                // Role permissions
                'peut créer un rôle',
                'peut modifier un rôle',
                'peut supprimer un rôle',

                // Township permissions
                'peut créer une commune',
                'peut modifier une commune',

                // Tickect permissions
                'peut créer une valeur inactive',
                'peut modifier une valeur inactive',
                'peut supprimer une valeur inactive',

                // Village or quartier permissions
                'peut créer un village/quartier',
                'peut modifier un village/quartier',
                'peut supprimer un village/quartier',

                // Canton permissions
                'peut créer un canton',
                'peut modifier un canton',
                'peut supprimer un canton',

                // Zone permissions
                'peut créer une zone',
                'peut modifier une zone',
                'peut supprimer une zone',

                // Activity permissions
                'peut créer une activité',
                'peut modifier une activité',
                'peut supprimer une activité',

                // Category 
                'peut créer une catégorie',
                'peut modifier une catégorie',
                'peut supprimer une catégorie',

                // TaxLabel permissions
                'peut créer un libellé fiscale',
                'peut modifier un libellé fiscale',
                'peut supprimer un libellé fiscale',

                // Taxable permissions
                'peut créer une matière taxable',
                'peut modifier une matière taxable',
                'peut supprimer une matière taxable',
            ],

            'administrateur' => [
                // User permissions
                'peut créer un utilisateur',
                'peut modifier un utilisateur',
                'peut activer un utilisateur',
                'peut désactiver un utilisateur',
                'peut créer un collecteur',

                // Taxpayer permissions
                'peut activer un contribuable',
                'peut désactiver un contribuable',
                'peut valider un contribuable',

                // Role permissions
                'peut créer un rôle',
                'peut modifier un rôle',
                'peut supprimer un rôle',

                // Township permissions
                'peut créer une commune',
                'peut modifier une commune',

                // Tickect permissions
                'peut créer une valeur inactive',
                'peut modifier une valeur inactive',
                'peut supprimer une valeur inactive',

                // Village or quartier permissions
                'peut créer un village/quartier',
                'peut modifier un village/quartier',
                'peut supprimer un village/quartier',

                // Canton permissions
                'peut créer un canton',
                'peut modifier un canton',
                'peut supprimer un canton',

                // Zone permissions
                'peut créer une zone',
                'peut modifier une zone',
                'peut supprimer une zone',

                // Activity permissions
                'peut créer une activité',
                'peut modifier une activité',
                'peut supprimer une activité',

                // Category 
                'peut créer une catégorie',
                'peut modifier une catégorie',
                'peut supprimer une catégorie',

                // TaxLabel permissions
                'peut créer un libellé fiscale',
                'peut modifier un libellé fiscale',
                'peut supprimer un libellé fiscale',

                // Taxable permissions
                'peut créer une matière taxable',
                'peut modifier une matière taxable',
                'peut supprimer une matière taxable',

                // Geolocatoion permissions
                'peut voir la geolocalisation d\'un contribuable',
            ],

            'ordonateur' => [
                // Account permissions 
                'peut voir la comptabilité',

                // Recovery permissions
                'peut voir le recouvrement',
            ],

            'agent_assiette' => [
                // Taxpayer Taxable permissions
                'peut créer une taxation',
                'peut modifier une taxation',
                'peut supprimer une taxation',

                // Invoice permissions
                'peut émettre un avis sur titre',
                'peut réduire un avis sur titre',
                'peut générer automatiquement les avis sur titre',

                // Geolocatoion permissions
                'peut voir la geolocalisation d\'un contribuable',
            ],

            'agent_delegation' => [
                // Invoice permissions
                'peut accepter un avis sur titre',
                'peut rejeter un avis sur titre (agent par délégation de l\'ordonateur)',
            ],

            'regisseur' => [
                // Account permissions 
                'peut voir la comptabilité',
                'peut effectuer un versement au receveur',
                'peut effectuer un versement au régisseur',
                'peut effectuer une demande d\'approvisionement de valeur inactive',
                'peut effectuer une alimentation des collecteurs',
                'peut faire un etat de compte du collecteur',
                'peut faire un etat de compte du régisseur',
                'peut valider un avis au comptant',
                'peut prendre en charge un avis au comptant',
                'peut rejeter un avis au comptant',
                'peut réduire un avis au comptant',

                // Recovery permissions
                'peut voir le recouvrement',

                // Collector permissions
                'peut créer un collecteur',

                // Payment permissions
                'peut ajouter un paiement',
                'peut accepter un paiement',
                'peut comptabiliser un paiement',

                // Geolocatoion permissions
                'peut voir la geolocalisation d\'un contribuable',
            ],

            'agent_recouvrement' => [
                // Taxpayer permissions
                'peut créer un contribuable',
                'peut modifier un contribuable',

                // Payment permissions
                'peut ajouter un paiement',

                // Recovery permissions
                'peut voir le recouvrement',

                // Geolocatoion permissions
                'peut voir la geolocalisation d\'un contribuable',

                // Date permissions
                'peut ajouter la date de livraison d\'un avis',
            ],

            'collecteur' =>  [],

            'agent_delegation_du_receveur' => [
                // Invoice permissions
                'peut prendre en charge un avis sur titre',
                'peut rejeter un avis sur titre (agent par délégation du receveur)',
            ],

            'agent_recette' => [
                // Order no permissions
                'peut ajouter le numéro d\'ordre de recette d\'un avis',
            ],
        ];

        foreach ($permissions_by_role['administrateur_system'] as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($permissions_by_role as $role => $permissions) {
            $permissions_list = [];
            foreach ($permissions as $permission) {
                $permissions_list[] = $permission;
            }
            Role::firstOrCreate(['name' => $role, 'user_id' => 0])->syncPermissions($permissions_list);
        }

        if (!app()->environment('production')) {
            User::find(1)->assignRole('administrateur_system');
            User::find(2)->assignRole('administrateur');
            User::find(3)->assignRole(['ordonateur', 'administrateur']);
            User::find(4)->assignRole('agent_delegation');
            User::find(5)->assignRole('agent_assiette');
            User::find(6)->assignRole('regisseur');
            User::find(7)->assignRole('agent_recouvrement');
            User::find(8)->assignRole('collecteur');
            User::find(9)->assignRole('agent_delegation_du_receveur');
            User::find(10)->assignRole('agent_recette');
        }
    }
}
