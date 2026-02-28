<?php

namespace Database\Factories;

use App\Models\Taxable;
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
            'length' => fake()->randomFloat(2, 1, 100),
            'width' => fake()->randomFloat(2, 1, 100),
            'seize' => random_int(1, 20),
            'location' => fake()->city(),
            'longitude' => fake()->longitude(),
            'latitude' => fake()->latitude(),
            'taxpayer_id' => random_int(4995, 5000),
            'taxable_id' => Taxable::inRandomOrder()->first()->id,
            'billable' =>  "NOT BILLED",
            'invoice_id' => null,
            'bill_status' => fake()->randomElement(['paid', 'unpaid', 'pending']),
            'auth_reference' => fake()->bothify('REF-#####-???'),
        ];
    }
}
