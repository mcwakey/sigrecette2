<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_no' => fake()->randomNumber(2, 1, 9) . Str::random(3) . fake()->randomNumber(2, 0, 9),
            //'order_no' => "00" . random_int(1, 10),
            //'nic' =>  "00" . random_int(1, 10),
            'status' => fake()->randomElement(['PENDING', 'REJECTED', 'SUCCESS']),
            'taxpayer_id' => random_int(4950, 5000),
        ];
    }
}
