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




        $taxLabelNamesZero = [
            ['name' => "Taxe sur les pompes distributrices de carburant", 'code' => "721112",'category'=>"catégorie 1"],
            ['name' => "Les redevances d’exploitation des carrières et des mines", 'code' => "724111",'category'=>"catégorie 1"],
            ['name' => "La taxe sur la publicité (TSP)", 'code' => "721117",'category'=>"catégorie 1"],
            ['name' => "Les produits de location de boutiques", 'code' => "721116",'category'=>"catégorie 1"],
            ['name' => "La redevance d'occupation du domaine public (RODP)", 'code' => "726112",'category'=>"catégorie 1"],
            ['name' => "La redevance des services concédés ou affermés par la commune", 'code' => "755111",'category'=>"catégorie 1"],
            ['name' => "Les droits de place dans les marchés", 'code' => "723111",'category'=>"catégorie 1"],
        ];


        $taxLabelNamesZwei = [
            ['name' => "Les produits de location de matériels", 'code' => "705111",'category'=>"catégorie 2"],
            ['name' => "Les produits de location de mobiliers", 'code' => "705112",'category'=>"catégorie 2"],
            ['name' => "Les produits de location des propriétés de la collectivité territoriale", 'code' => "729119",'category'=>"catégorie 2"],
            ['name' => "Les redevances de vidange et de curage des caniveaux et de fosses septiques", 'code' => "702112",'category'=>"catégorie 2"],
            ['name' => "Les taxes et redevances relatives aux services d’hygiène et de salubrité publique et aux pompes funèbres", 'code' => "702119",'category'=>"catégorie 2"],
            ['name' => "Les taxes ou redevances en matière d’urbanisme et d’environnement", 'code' => "722112",'category'=>"catégorie 2"],
            ['name' => "Les droits de stationnement et parking", 'code' => "726111",'category'=>"catégorie 2"],
            ['name' => "Les produits de concession dans les cimetières", 'code' => "721111",'category'=>"catégorie 2"],
            ['name' => "La taxe d'inhumation et d'exhumation", 'code' => "721111",'category'=>"catégorie 2"],
            ['name' => "Les droits d'autorisation de spectacles", 'code' => "722119",'category'=>"catégorie 2"],
            ['name' => "Les droits de fourrière et produits de vente d’animaux", 'code' => "729119",'category'=>"catégorie 2"],
            ['name' => "Les taxes d’abattage des essences forestières", 'code' => "722114",'category'=>"catégorie 2"],
            ['name' => "La taxe d’abattage des palmiers à huile", 'code' => "722115",'category'=>"catégorie 2"],
            ['name' => "Les taxes d'encombrement de voies publiques", 'code' => "726112",'category'=>"catégorie 2"],
        ];
        $taxLabelNamesDrei = [
            ['name' => "La taxe d’expédition, d’enregistrement et de légalisation des actes administratifs et d’état civil", 'code' => "722111",'category'=>"catégorie 3"],
            ['name' => "La taxe d’abattage, d’inspection sanitaire des animaux de boucherie", 'code' => "704111",'category'=>"catégorie 3"],
            ['name' => "La taxe de transport et chargement des produits de carrières", 'code' => "724119",'category'=>"catégorie 3"],
            ['name' => "La taxe d’inspection sanitaire des produits alimentaires", 'code' => "722113",'category'=>"catégorie 3"],
            ['name' => "Les produits des amendes", 'code' => "727119",'category'=>"catégorie 3"],
            ['name' => "Les redevances de distribution d'eau", 'code' => "709119",'category'=>"catégorie 3"],
            ['name' => "Les droits des marchands ambulants", 'code' => "723112",'category'=>"catégorie 3"],
        ];

        foreach ([$taxLabelNamesZero,$taxLabelNamesZwei,$taxLabelNamesDrei] as $labelItems) {
            foreach ($labelItems as $item) {
            TaxLabel::create($item);
            }
        }
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
            //TaxLabel::create(['name' => $name]);
        }
    }
}
