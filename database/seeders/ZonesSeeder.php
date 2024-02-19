<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zoneNames=[
            'Zone 1',
            'Zone 2',
            'Zone 3',
            'Zone 4',
            'Zone 5',
            'Zone 6',
            'Zone 7',
            'Zone 8',
            'Zone 9',
            'Zone 10',
        ];

        foreach ($zoneNames as $name) {
            Zone::create(['name' => $name]);
        }
    }
}
