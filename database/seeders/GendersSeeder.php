<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genderNames = [
            'Homme',
            'Femme',
        ];

        foreach ($genderNames as $name) {
            Gender::create(['name' => $name]);
        }
    }
}
