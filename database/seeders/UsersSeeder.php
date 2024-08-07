<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        if (!app()->environment('production')) {
            User::create([
                'name'  => 'Administrateur Système',
                'email' => 'AdministrateurSystem@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
            ]);

            User::create([
                'name'  => 'Administrateur',
                'email' => 'Administrateur@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
            ]);

            User::create([
                'name'  => 'Ordonateur',
                'email' => 'Ordonateur@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
            ]);

            User::create([
                'name'  => 'Agent Par Delegation',
                'email' => 'AgentParDelegation@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
            ]);

            User::create([
                'name'  => 'Agent D\'Assiette',
                'email' => 'AgentAssiette@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
            ]);

            User::create([
                'name'  => 'Regisseur',
                'email' => 'Regisseur@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
            ]);

            User::create([
                'name'  => 'Agent De Recouvrement',
                'email' => 'AgentDeRecouvrement@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
                'longitude' => json_encode([6.145761, 6.148153, 6.142344]),
                'latitude' => json_encode([1.204888, 1.209127, 1.207924]),
            ]);

            User::create([
                'name'  => 'Collecteur',
                'email' => 'Collecteur@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
                'longitude' => json_encode([6.145761, 6.148153, 6.142344]),
                'latitude' => json_encode([1.204888, 1.209127, 1.207924]),
            ]);

            User::create([
                'name'  => 'AgentDelegationReceveur',
                'email' => 'AgentDelegationReceveur@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
                'longitude' => json_encode([6.145761, 6.148153, 6.142344]),
                'latitude' => json_encode([1.204888, 1.209127, 1.207924]),
            ]);

            User::create([
                'name'  => 'AgentRecette',
                'email' => 'AgentRecette@demo.com',
                'password' => Hash::make('demo'),
                'email_verified_at' => now(),
                'longitude' => json_encode([6.145761, 6.148153, 6.142344]),
                'latitude' => json_encode([1.204888, 1.209127, 1.207924]),
            ]);
        }
    }
}
