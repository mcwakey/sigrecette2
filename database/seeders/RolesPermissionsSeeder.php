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
                'create taxpayer taxable asset',
                'edit taxpayer taxable asset',
                'delete taxpayer taxable asset',

                // Invoice permissions
                'create invoice',
                'create invoice automaticaly',
                'change invoice draft status to pending',
                'change invoice pending status to approved',
                'add invoice delivery date',
                'print invoice',
                'reduce invoice amount',

                // Taxpayer permissions
                'create taxpayer',
                'edit taxpayer',
                'delete taxpayer',

                // Payment permissions
                'create invoice payment',
                'create no taxpayer invoice payment',

                // Account permissions
                'view account',
                'add collector account state',
                'add collector account supply',
                'add collector new deposit',

                // Log permissions
                'view log',

                // Config permissions
                'view config',
                'import taxpayer',
                'create township',
                'create taxable',
                'create taxlable',
                'create canton',
                'create town',
                'create tax zone',
                'create erea',
                'create activity',
                'create activity category',

                // User permissions
                'create user',
                'edit user',
                'delete user',
                'add user role',
                'add user permission',


                // Geolocatoion permissions
                'view user geolocation',
                'view zone geolocation',

                // Role permissions
                'create role',
                'edit role',
                'delete role',
            ],

            'administrateur' => [
                // Config permissions
                'view config',
                'import taxpayer',
                'create township',
                'create taxable',
                'create taxlable',
                'create canton',
                'create town',
                'create tax zone',
                'create erea',
                'create activity',
                'create activity category',

                // User permissions
                'create user',
                'edit user',
                'delete user',
                'add user role',
                'add user permission',

                // Geolocatoion permissions
                'view user geolocation',

                // Role permissions
                'create role',
                'edit role',
                'delete role',
            ],

            'ordonateur' => [],

            'agent_assiette' => [
                // Taxpayer Taxable permissions
                'create taxpayer taxable asset',
                'edit taxpayer taxable asset',

                // Invoice permissions
                'create invoice',
                'reduce invoice amount',
            ],

            'agent_delegation' => [
                // Invoice permissions
                'change invoice draft status to pending',
                'print invoice',
            ],

            'regisseur' => [
                // Invoice permissions
                'change invoice pending status to approved',
                'print invoice',

                // Payment permissions
                'create invoice payment',
                'create no taxpayer invoice payment',

                // Account permissions
                'view account',
                'add collector account state',
                'add collector account supply',
            ],

            'agent_recouvrement' => [
                // Invoice permissions
                'add invoice delivery date',
                'print invoice',

                // Payment permissions
                'create invoice payment',

                // Account permissions
                'add collector new deposit',

            ],

            'collecteur' => [
                // Account permissions
                'add collector new deposit',
            ],
        ];

        foreach ($permissions_by_role['administrateur_system'] as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($permissions_by_role as $role => $permissions) {
            $permissions_list = [];
            foreach ($permissions as $permission) {
                $permissions_list[] = $permission;
            }
            Role::create(['name' => $role])->syncPermissions($permissions_list);
        }

        User::find(1)->assignRole('administrateur_system');
        User::find(2)->assignRole('administrateur');
        User::find(3)->assignRole(['ordonateur', 'administrateur']);
        User::find(4)->assignRole('agent_delegation');
        User::find(5)->assignRole('agent_assiette');
        User::find(6)->assignRole('regisseur');
        User::find(7)->assignRole('agent_recouvrement');
        User::find(8)->assignRole('collecteur');
    }
}
