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
        $abilities = [
            'view',
            'edit',
            'create',
            'delete',
        ];

        $permissions_by_role = [
            'administrateur_system' => [
                'invoive',
                'taxpayer',
                'taxpayer_taxable',
                'town',
                'canton',
                'taxlabel',
                'taxable',
                'year',
                'gender',
                'erea',
                'taxable',
                'payment',
                'user',
                'user_log',
                'year',
                'zone',
                'commune',
                'address',
                'activity',
                'account',
                'category'
            ],

            'administrateur' => [],

            'maire' => [],

            'agent_assiette' => [],

            'agent_delegation' => [],

            'regisseur' => [],

            'agent_recouvrement' => [],

            'collecteur' => [],
        ];

        foreach ($permissions_by_role['administrateur_system'] as $permission) {
            foreach ($abilities as $ability) {
                Permission::create(['name' => $ability . ' ' . $permission]);
            }
        }

        foreach ($permissions_by_role as $role => $permissions) {
            $full_permissions_list = [];
            foreach ($abilities as $ability) {
                foreach ($permissions as $permission) {
                    $full_permissions_list[] = $ability . ' ' . $permission;
                }
            }
            Role::create(['name' => $role])->syncPermissions($full_permissions_list);
        }

        User::find(1)->assignRole('administrateur_system');
        User::find(2)->assignRole('administrateur');
        User::find(3)->assignRole(['maire']);
        User::find(4)->assignRole('agent_delegation');
        User::find(5)->assignRole('agent_assiette');
        User::find(6)->assignRole('regisseur');
        User::find(7)->assignRole('agent_recouvrement');
        User::find(8)->assignRole('collecteur');
    }
}
