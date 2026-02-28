<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        $fromDate = now()->subDays(rand(1, 365));
        $toDate = (clone $fromDate)->addDays(rand(1, 365));

        return [
            'invoice_id' => fake()->unique()->randomNumber(6),
            'taxpayer_id' => random_int(4995, 5000),
            'invoice_no' => 'INV-' . fake()->unique()->numberBetween(1000, 9999),
            'order_no' => 'ORD-' . fake()->unique()->numberBetween(100, 999),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'reduce_amount' => fake()->randomFloat(2, 0, 500),
            'qty' => fake()->numberBetween(1, 100),
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'pay_status' => fake()->randomElement(['paid', 'unpaid', 'partial', 'cancelled']),
            'status' => fake()->randomElement(['draft', 'issued', 'sent', 'archived']),
            'uuid' => fake()->uuid(),
            'delivery_date' => fake()->dateTimeBetween($toDate, '+1 month'),
            'delivery_to' => fake()->address(),
            'type' => fake()->randomElement(['standard', 'proforma', 'credit', 'debit']),
            'delivery' => fake()->randomElement(['email', 'post', 'in_person', 'online']),
            'edition_state' => fake()->randomElement(['original', 'revised', 'corrected']),
            'notes' => fake()->sentence(),
        ];
    }
}
