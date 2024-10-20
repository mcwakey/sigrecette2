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
        $idTypeNames = [
            'PAS DE CARTE',
            'CNI',
            'PASSPORT',
            'PERMIS DE CONDUIRE',
            'CARTE D\'ELECTEUR',
            'CARTE DE SEJOUR',
            'IDENTIFIANT UNIQUE',
        ];

        foreach ($idTypeNames as $name) {
            IdType::create(['name' => $name]);
        }
    }
}
