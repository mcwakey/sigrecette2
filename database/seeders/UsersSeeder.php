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
        User::create([
            'name'  => 'Emmanuel Wakey',
            'email' => 'demo@demo.com',
            'password' => Hash::make('demo'),
            'email_verified_at' => now(),
            'longitude' => json_encode([6.145761,6.148153,6.142344]), 
            'latitude' => json_encode([1.204888,1.209127,1.207924]),
        ]);

        // $demoUser2 = User::create([
        //     'name'              => $faker->name,
        //     'email'             => 'admin@demo.com',
        //     'password'          => Hash::make('demo'),
        //     'email_verified_at' => now(),
        // ]);
    }
}
