<?php

namespace Database\Seeders;

use App\Models\IdType;
use Illuminate\Database\Seeder;

class IdTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoGender = IdType::create([
            'name'              => 'CNI',
            'status'             => 'ACTIVE',
        ]);

        $demoGender1 = IdType::create([
            'name'              => 'PASSPORT',
            'status'             => 'ACTIVE',
        ]);

        $demoGender2 = IdType::create([
            'name'              => 'PERMIS DE CONDUIRE',
            'status'             => 'ACTIVE',
        ]);

        $demoGender3 = IdType::create([
            'name'              => 'CARTE D\'ELECTEUR',
            'status'             => 'ACTIVE',
        ]);

        $demoGender4 = IdType::create([
            'name'              => 'CARTE DE SEJOUR',
            'status'             => 'ACTIVE',
        ]);

        // $demoGender = IdType::create([
        //     'name'              => 'PASSPORT',
        //     'status'             => 'ACTIVE',
        // ]);
    }
}
