<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Taxable>
 */
class TaxableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(4),
            'tariff' => random_int(5, 60) . "00",
            'unit' => fake()->randomElement(['m2', null]),
            'modality' => fake()->randomElement(['Ticket', 'Quitance', 'Timbre']),
            'periodicity' => fake()->randomElement(['Mois', 'Forfait']),
            'penalty' =>  fake()->randomElement(['10', '15', null]),
            'penalty_type' => fake()->randomElement(['%', null]),
            'tax_label_id' => random_int(1, 10), 
        ];
    }
}
