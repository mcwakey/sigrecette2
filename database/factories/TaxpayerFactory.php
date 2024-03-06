<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Taxpayer>
 */
class TaxpayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tnif' => fake()->randomNumber(3, 1, 10) . Str::random(5) . fake()->randomNumber(3, 0, 9),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'gender' => fake()->randomElement(['Homme', 'Femme']),
            'id_type' => fake()->randomElement(['CNI', 'PASSPORT', 'PERMIS DE CONDUIRE', 'CARTE D\'ELECTEUR', 'CARTE DE SEJOUR']),
            'id_number' => random_int(1000000, 6000000),
            'mobilephone' => fake()->phoneNumber(),
            'telephone' => fake()->phoneNumber(),
            'longitude' => fake()->randomFloat(10, -180, 180), 
            'latitude' => fake()->randomFloat(10, -180, 180),
            'address' => fake()->streetAddress(),
            //'canton' => fake()->city(),
            'town_id' => random_int(1, 6),
            'erea_id' => random_int(1, 2),
            'zone_id' => random_int(1, 3),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
