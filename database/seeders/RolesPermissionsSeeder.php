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

                // Invoice permissions
                'create invoice',
                'create invoice automaticaly',
                'change invoice draft status to pending',
                'change invoice pending status to approved',
                'create invoice delivery date',
                'print invoice',
                'reduce invoice amount',

                // Taxpayer permissions
                'create taxpayer',
                'edit taxpayer',
                'delete taxpayer',

                // Payment permissions
                'view payment',
                'create invoice payment',
                'create no taxpayer invoice payment',

                // Account permissions
                'view account',
                'create collector account state',
                'create collector account supply',
                'create collector new deposit by manager',
                'create collector new deposit',
                'view manager account state',
                'view manager deposit state',

                'view manager newspaper',

                // Config permissions
                'view config',
                'create category',
                'create township',
                'create taxable',
                'create taxlabel',
                'create canton',
                'create town',
                'create zone',
                'create erea',
                'create activity',

                // User permissions
                'create user',
                'edit user',
                'delete user',

                // Geolocatoion permissions
                'view user geolocation',
                'view zone geolocation',

                // Role permissions
                'create role',
                'edit role',
                'delete role',
            ],

            'administrateur' => [],

            'ordonateur' => [],

            'agent_assiette' => [
                // Taxpayer Taxable permissions
                'create taxpayer taxable asset',
                'edit taxpayer taxable asset',

                // Invoice permissions
                'create invoice',
                'reduce invoice amount',

                //Accout permissions
                'view manager account state',
                'view manager deposit state',
            ],

            'agent_delegation' => [
                // Invoice permissions
                'change invoice draft status to pending',
                'print invoice',
            ],

            'regisseur' => [
                // Invoice permissions
                'create invoice delivery date',
                'change invoice pending status to approved',
                'print invoice',

                // Payment permissions
                'create invoice payment',
                'create no taxpayer invoice payment',

                // Account permissions
                'view account',
                'create collector new deposit by manager',
                'create collector account state',
                'create collector account supply',
            ],

            'agent_recouvrement' => [
                // Invoice permissions
                'create invoice delivery date',
                'print invoice',

                // Payment permissions
                'create invoice payment',
            ],

            'collecteur' => [
                // Account permissions
                'create collector new deposit',
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
