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
        $demoGender = Gender::create([
            'name'              => 'Homme',
            'status'             => 'ACTIVE',
        ]);

        $demoGender = Gender::create([
            'name'              => 'Femme',
            'status'             => 'ACTIVE',
        ]);
    }
}
