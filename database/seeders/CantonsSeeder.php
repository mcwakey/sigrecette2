<?php

namespace Database\Seeders;

use App\Models\Canton;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CantonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cantonNames = [
            'Agoe-Nyivé',
            'Vakpossito',
            'Toglé',
            'Légbassito',
            'Sanguéra',
            'Adétikopé',
        ];

        foreach ($cantonNames as $name) {
            if (!app()->environment('production')) {
                Canton::create(['name' => $name]);
            }
        }
    }
}
