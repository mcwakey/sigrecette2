<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaxpayerTaxable>
 */
class TaxpayerTaxableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'seize' => random_int(1, 20),
            'location' => fake()->city(),
            'taxpayer_id' => random_int(4995, 5000),
            'taxable_id' => random_int(1, 100),
            'invoice_id' => random_int(1, 5),
        ];
    }
}
