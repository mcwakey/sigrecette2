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
            ['name' => "Ventes de produits finis",'code' => "701111",'category' => "CATEGORY 1"],
            ['name' => "Coupes de bois",'code' => "701211",'category' => "CATEGORY 2"],
            ['name' => "Redevances d'enlèvement des ordures et des déchets",'code' => "702111",'category' => "CATEGORY 2"],
            ['name' => "Redevances de vidange et de curage de caniveaux et fosses septiques",'code' => "702211",'category' => "CATEGORY 2"],
            ['name' => "Taxes de désinfection (services d’hygiène)",'code' => "702311",'category' => "CATEGORY 2"],
            ['name' => "Autres recettes de prestations de services",'code' => "702911",'category' => "CATEGORY 2"],
            ['name' => "Variation de stocks de produits",'code' => "703111",'category' => "CATEGORY 1"],
            ['name' => "Taxe d'abattage d'inspection sanitaire des animaux de boucherie",'code' => "704111",'category' => "CATEGORY 1"],
            ['name' => "Autres produits de l'abattoir",'code' => "704911",'category' => "CATEGORY 1"],
            ['name' => "Location de matériels",'code' => "705112",'category' => "CATEGORY 2"],
            ['name' => "Location de mobiliers",'code' => "705212",'category' => "CATEGORY 2"],
            ['name' => "Vente de marchandises",'code' => "707111",'category' => "CATEGORY 1"],
            ['name' => "Autres ventes de produits et services",'code' => "709911",'category' => "CATEGORY 1"],


            ['name' => "Concessions et redevances funéraires", 'code' => "721111", 'category' => "CATEGORY 1"],
            ['name' => "Taxe sur les pompes distributrices de carburant", 'code' => "721211", 'category' => "CATEGORY 1"],
            ['name' => "Locations de droits de chasse et de pêche", 'code' => "721311", 'category' => "CATEGORY 1"],
            ['name' => "Taxes de pâturage", 'code' => "721411", 'category' => "CATEGORY 1"],
            ['name' => "Produits de Location de Terrain (PLT)", 'code' => "721511", 'category' => "CATEGORY 1"],
            ['name' => "Produits de Location de Boutiques (PLB)", 'code' => "721611", 'category' => "CATEGORY 1"],
            ['name' => "Taxes sur la publicité", 'code' => "721711", 'category' => "CATEGORY 1"],
            ['name' => "Autres revenus du domaine", 'code' => "721911", 'category' => "CATEGORY 1"],
            ['name' => "Droits et frais administratifs", 'code' => "721912", 'category' => "CATEGORY 2"],
            ['name' => "Taxe d'expédition, d'enregistrement des actes administratifs et d'état civil", 'code' => "722111", 'category' => "CATEGORY 3"], 
            ['name' => "Redevance d'urbanisme", 'code' => "722211", 'category' => "CATEGORY 3"], 
            ['name' => "Taxes d'inspection sanitaire des produits alimentaires", 'code' => "722311", 'category' => "CATEGORY 3"], 
            ['name' => "Taxes d'abattage des essences forestières", 'code' => "722411", 'category' => "CATEGORY 3"], 
            ['name' => "Taxes d'abattage des palmiers à huile", 'code' => "722511", 'category' => "CATEGORY 3"], 
            ['name' => "Autres droits et frais administratifs", 'code' => "722911", 'category' => "CATEGORY 3"],
            ['name' => "Droits de place dans les marchés, foires et marchands ambulants", 'code' => "723111", 'category' => "CATEGORY 4"],
            ['name' => "Droits de place dans les marchés et foires", 'code' => "723112", 'category' => "CATEGORY 5"], 
            ['name' => "Droits de place des marchands ambulants", 'code' => "723212", 'category' => "CATEGORY 5"],
            ['name' => "Produits du sol et du sous-sol", 'code' => "724111", 'category' => "CATEGORY 1"],
            ['name' => "Produit d'exploitation des carrières", 'code' => "724112", 'category' => "CATEGORY 2"], 
            ['name' => "Redevance minière", 'code' => "724212", 'category' => "CATEGORY 2"], 
            ['name' => "Autres Produits du sol et du sous-sol", 'code' => "724912", 'category' => "CATEGORY 2"],
            ['name' => "Droits de mutations de biens", 'code' => "725111", 'category' => "CATEGORY 1"],
            ['name' => "Droits de stationnement et d’occupation du domaine public", 'code' => "726111", 'category' => "CATEGORY 1"],
            ['name' => "Droits de permis de stationnement et de parking", 'code' => "726112", 'category' => "CATEGORY 2"], 
            ['name' => "Redevance d’occupation du domaine public", 'code' => "726212", 'category' => "CATEGORY 2"],
            ['name' => "Amendes forfaitaires de police", 'code' => "727111", 'category' => "CATEGORY 1"],
            ['name' => "Amendes de simple police", 'code' => "727112", 'category' => "CATEGORY 2"], 
            ['name' => "Autres amendes", 'code' => "727912", 'category' => "CATEGORY 2"],
            ['name' => "Produits des quêtes et contributions volontaires", 'code' => "728111", 'category' => "CATEGORY 1"],
            ['name' => "Autres recettes non fiscales", 'code' => "729911", 'category' => "CATEGORY 1"]

        ];


        $taxLabelNamesZero = [
            ['name' => "Taxe sur les pompes distributrices de carburant", 'code' => "721112",'category'=>"CATEGORY 1"],
            ['name' => "Les redevances d’exploitation des carrières et des mines", 'code' => "724111",'category'=>"CATEGORY 1"],
            ['name' => "La taxe sur la publicité (TSP)", 'code' => "721117",'category'=>"CATEGORY 1"],
            ['name' => "Les produits de location de boutiques", 'code' => "721116",'category'=>"CATEGORY 1"],
            ['name' => "La redevance d'occupation du domaine public (RODP)", 'code' => "726112",'category'=>"CATEGORY 1"],
            ['name' => "La redevance des services concédés ou affermés par la commune", 'code' => "755111",'category'=>"CATEGORY 1"],
            ['name' => "Les droits de place dans les marchés", 'code' => "723111",'category'=>"CATEGORY 1"],
        ];


        $taxLabelNamesZwei = [
            ['name' => "Les produits de location de matériels", 'code' => "705111",'category'=>"CATEGORY 2"],
            ['name' => "Les produits de location de mobiliers", 'code' => "705112",'category'=>"CATEGORY 2"],
            ['name' => "Les produits de location des propriétés de la collectivité territoriale", 'code' => "729119",'category'=>"CATEGORY 2"],
            ['name' => "Les redevances de vidange et de curage des caniveaux et de fosses septiques", 'code' => "702112",'category'=>"CATEGORY 2"],
            ['name' => "Les taxes et redevances relatives aux services d’hygiène et de salubrité publique et aux pompes funèbres", 'code' => "702119",'category'=>"CATEGORY 2"],
            ['name' => "Les taxes ou redevances en matière d’urbanisme et d’environnement", 'code' => "722112",'category'=>"CATEGORY 2"],
            ['name' => "Les droits de stationnement et parking", 'code' => "726111",'category'=>"CATEGORY 2"],
            ['name' => "Les produits de concession dans les cimetières", 'code' => "721111",'category'=>"CATEGORY 2"],
            ['name' => "La taxe d'inhumation et d'exhumation", 'code' => "721111",'category'=>"CATEGORY 2"],
            ['name' => "Les droits d'autorisation de spectacles", 'code' => "722119",'category'=>"CATEGORY 2"],
            ['name' => "Les droits de fourrière et produits de vente d’animaux", 'code' => "729119",'category'=>"CATEGORY 2"],
            ['name' => "Les taxes d’abattage des essences forestières", 'code' => "722114",'category'=>"CATEGORY 2"],
            ['name' => "La taxe d’abattage des palmiers à huile", 'code' => "722115",'category'=>"CATEGORY 2"],
            ['name' => "Les taxes d'encombrement de voies publiques", 'code' => "726112",'category'=>"CATEGORY 2"],
        ];
        $taxLabelNamesDrei = [
            ['name' => "La taxe d’expédition, d’enregistrement et de légalisation des actes administratifs et d’état civil", 'code' => "722111",'category'=>"CATEGORY 3"],
            ['name' => "La taxe d’abattage, d’inspection sanitaire des animaux de boucherie", 'code' => "704111",'category'=>"CATEGORY 3"],
            ['name' => "La taxe de transport et chargement des produits de carrières", 'code' => "724119",'category'=>"CATEGORY 3"],
            ['name' => "La taxe d’inspection sanitaire des produits alimentaires", 'code' => "722113",'category'=>"CATEGORY 3"],
            ['name' => "Les produits des amendes", 'code' => "727119",'category'=>"CATEGORY 3"],
            ['name' => "Les redevances de distribution d'eau", 'code' => "709119",'category'=>"CATEGORY 3"],
            ['name' => "Les droits des marchands ambulants", 'code' => "723112",'category'=>"CATEGORY 3"],
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
