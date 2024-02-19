<?php

namespace Database\Seeders;

use App\Models\Taxable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxableNamesOne=[
            ['name' => 'Cinéma, Récital', 'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Bals', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Café, Bars Resto', 'tariff' => '3000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Cérémonies rituelles', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Véillee', 'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Théâtre', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
        ];

        foreach ($taxableNamesOne as $name) {
            Taxable::create($name);
        }

        $taxableNamesTwo=[
            ['name' => 'Garage anarchique des véhicules plus 72 heures', 'tariff' => '2000', 'periodicity' => 'mois', 'tax_label_id' => 2],
            ['name' => 'Kiosques àtransfert', 'tariff' => '1500', 'periodicity' => 'mois', 'tax_label_id' => 2],
            ['name' => 'Apatam aux abords des rues (PM)', 'tariff' => '15000', 'periodicity' => 'Forfait', 'tax_label_id' => 2],
            ['name' => 'Autorisation provisoire d\'Installation et d\'ouverture de débits de boisson', 'tariff' => '500', 'periodicity' => 'mois', 'tax_label_id' => 2],
        ];

        foreach ($taxableNamesTwo as $name) {
            Taxable::create($name);
        }

        $taxableNamesThree=[
            ['name' => 'Panneau publicitaire moins 1m?', 'tariff' => '2000', 'periodicity' => 'mois', 'tax_label_id' => 6],
            ['name' => 'Panneau égale a 1m', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 6],
            ['name' => 'Panneaupublicitaire plus de 1m?', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 6],
            ['name' => 'Publicité et annonce par haut-parleur', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 6],

        ];

        foreach ($taxableNamesThree as $name) {
            Taxable::create($name);
        }

        $taxableNamesFour=[
            ['name' => 'Location pour foire commerciale', 'tariff' =>  '100000', 'periodicity' => 'Forfait', 'tax_label_id' => 7],
            ['name' => 'Location pour foire d\'exposition', 'tariff' =>  '100000', 'periodicity' => 'Forfait', 'tax_label_id' => 7],
            ['name' => 'Autre compétition', 'tariff' =>  '2000', 'periodicity' => 'mois', 'tax_label_id' => 7],
            ['name' => 'Location ed boutique', 'tariff' =>  '700', 'periodicity' => 'mois', 'tax_label_id' => 7],
            ['name' => 'Location ed magasin', 'tariff' =>  '700', 'periodicity' => 'mois', 'tax_label_id' => 7],

        ];

        foreach ($taxableNamesFour as $name) {
            Taxable::create($name);
        }
    }
}
