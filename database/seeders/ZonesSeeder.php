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
        
        $zoneData = [
            [
                'name' => 'Zone 1', 
                'longitude' => json_encode([6.145761,6.148153,6.142344]), 
                'latitude' => json_encode([1.204888,1.209127,1.207924]),
            ],

            [
                'name' => 'Zone 2', 
                'longitude' => json_encode([6.148922, 6.146473, 6.147356,6.149634,]), 
                'latitude' => json_encode([1.214912, 1.215800, 1.219066,1.220011]),
            ],

            [
                'name' => 'Zone 3', 
                'longitude' => json_encode([6.141973, 6.142600,6.140503,]), 
                'latitude' => json_encode([1.208325,1.212879,1.212234]),
            ],
        ];

        foreach ($zoneData as $data) {
            Zone::create($data);
        }
    }
}
