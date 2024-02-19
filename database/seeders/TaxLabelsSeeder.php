<?php

namespace Database\Seeders;

use App\Models\TaxLabel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxLabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxLabelNames=[
            'Taxe sur les spectacles et autres manifestations publiques',
            'Taxe de voirie',
            'Taxe sur les pompes distributrices de carburant',
            'Taxe de marche',
            'Taxe d\'encombrement des voies publiques',
            'Taxe sur la publicité',
            'Produits de location de terrain et de boutique',
        ];

        foreach ($taxLabelNames as $name) {
            TaxLabel::create(['name' => $name]);
        }
    }
}
