<?php

namespace Database\Seeders;

use App\Models\Taxpayer;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaxpayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coordinates = [
            [0.7966636,6.7985916],
            [0.7965752,6.7984923],
            [0.7963058,6.798244],
            [0.7962848,6.7980096],
            [0.7966534,6.7984111],
            [0.7962227, 6.7978469],
            [0.7961719,6.7978092],
            [0.7959429,6.7975004],
            [0.7959217,6.7974388],
            [0.7155227,6.8476695],
            [0.7175388,6.8479116],
            [0.7175813,6.8479008],
            [0.7180042,6,8477277],
        ];

        foreach ($coordinates as $value) {
           Taxpayer::create([
            'tnif' => fake()->randomNumber(3, 1, 10) . Str::random(5) . fake()->randomNumber(3, 0, 9),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'gender' => fake()->randomElement(['Homme', 'Femme']),
            'id_type' => fake()->randomElement(['CNI', 'PASSPORT', 'PERMIS DE CONDUIRE', 'CARTE D\'ELECTEUR', 'CARTE DE SEJOUR']),
            'id_number' => random_int(1000000, 6000000),
            'mobilephone' => fake()->phoneNumber(),
            'telephone' => fake()->phoneNumber(),
            'longitude' => $value[0], 
            'latitude' => $value[1],
            'address' => fake()->streetAddress(),
            //'canton' => fake()->city(),
            'town_id' => random_int(1, 6),
            'erea_id' => random_int(1, 2),
            'zone_id' => random_int(1, 3),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
           ]);
        }

    }
}
