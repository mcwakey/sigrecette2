<?php

namespace Database\Factories;

use App\Models\Commune;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commune>
 */
class CommuneFactory extends Factory
{
    protected $model = Commune::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'title' => $this->faker->company,
            'region_name' => $this->faker->state,
            'mayor_name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'treasury_name' => $this->faker->company,
            'treasury_address' => $this->faker->address,
            'treasury_rib' => $this->faker->bankAccountNumber,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'limit_json' => json_encode([]),
            'logo_path' => 'logos/default.png',
            'sign_path' => 'signs/default.png',
            'email' => $this->faker->email,
            'url' => $this->faker->url,
            'qr_code_enabled' => $this->faker->boolean,
            'carry_forward_previous_year' => $this->faker->boolean,
        ];
    }
}
