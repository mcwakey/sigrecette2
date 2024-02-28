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
        $taxableNamesZero=[
            ['name' => "Appareils distributeurs de carburant",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 1,'unit_type'=>"Nombre"],
            ['name' => "Carrières de sable et de gravier",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 2,'unit_type'=>"Nombre"],
            ['name' => "Matières publicitaires",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 3,'unit_type'=>"Superficie"],
            ['name' => "Terrains appartenant à la collectivité mis en location",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 4,'unit_type'=>"Superficie"],
            ['name' => "Boutiques appartenant à la collectivité mis en location",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 5,'unit_type'=>"Superficie"],
            ['name' => "Domaine public occupé",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 6,'unit_type'=>"Superficie"],
            ['name' => "Service offert au niveau de l’infrastructure marchande faisant l’objet de concession ou d’affermage",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 1,],
            ['name' => "Places de marchés",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 7,'unit_type'=>"Superficie"],
        ];


        $taxableNamesZwei = [
            ['name' => "Matériels appartenant à la collectivité, mis en location",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 8,'unit_type'=>"Nombre"],
            ['name' => "Mobilier appartenant à la collectivité, mis en location",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 9,'unit_type'=>"Nombre"],
            ['name' => "Propriétés (Salle de réunion, de spectacle, place publique, etc.), appartenant à la collectivité territoriale, mis en location (Type de propriété)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 10,'unit_type'=>"Type"],
            ['name' => "Prestations de vidanges et de curages de caniveaux et de fosses septiques (Type de prestation)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 11, 'unit_type'=>"Volume"],
            ['name' => "Services offerts par la collectivité territoriale en matière d’hygiène et de salubrité publique et aux pompes funèbres",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 12,'unit_type'=>"Type"],
            ['name' => "Actes faits en matière d’urbanisme notamment les contrats de vente de terrains, les certificats administratifs, le permis de construire, et autres dossiers topographiques (Type d'acte)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 13,'unit_type'=>"Type"],
            ['name' => "Stationnement et parking d'engins dans un espace aménagé (Type de véhicules)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 14,'unit_type'=>"Type"],
            ['name' => "Espace concédé pour l'inhumation des corps des défunts (Type de concession)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 15,'unit_type'=>"Superficie"],
            ['name' => "Autorisations pour inhumation ou exhumation",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 15,'unit_type'=>"Type"],
            ['name' => "Toutes les autorisations sur les spectacles",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 17,'unit_type'=>"Type"],
            ['name' => "Animaux ou engins saisis et mis en fourrière",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 18,'unit_type'=>"Type"],
            ['name' => "Abattage d'arbres (Type d'arbre abattu)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 19,'unit_type'=>"Type"],
            ['name' => "Abattage des palmiers à huile",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 20,'unit_type'=>"Nombre"],
            ['name' => "Occupation d’emprises des voies publiques",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 21,'unit_type'=>"Superficie"],
        ];

        $taxableNamesDrei = [
            ['name' => "Prestations du service d’état civil et délivrance d’actes administratifs",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 22],
            ['name' => "Les animaux à l’abattage dans l’espace aménagé servant d’abattoir",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 23],
            ['name' => "Camions transportant les sables et graviers des carrières (Type de camion)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 24],
            ['name' => "Commerce et vente de produits alimentaires (Type de produits)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 25,'unit_type'=>"Type"],
            ['name' => "Infractions diverses dans la limite de leurs compétences (Type d'infraction)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 26,'unit_type'=>"Type"],
            ['name' => "Les bornes fontaines (kiosques à eau)",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 27,'unit_type'=>"Type"],
            ['name' => "Marchands ambulants dans le marché",'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 28,'unit_type'=>"Type"],
        ];

        foreach ([$taxableNamesZero,$taxableNamesZwei,$taxableNamesDrei] as $items) {
            foreach ($items as $item){
                Taxable::create($item);
            }
        }

        $taxableNamesOne=[
            ['name' => 'Cinéma, Récital', 'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Bals', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Café, Bars Resto', 'tariff' => '3000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Cérémonies rituelles', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Véillee', 'tariff' => '2000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
            ['name' => 'Théâtre', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 1],
        ];

        foreach ($taxableNamesOne as $name) {
            //Taxable::create($name);
        }

        $taxableNamesTwo=[
            ['name' => 'Garage anarchique des véhicules plus 72 heures', 'tariff' => '2000', 'periodicity' => 'mois', 'tax_label_id' => 2],
            ['name' => 'Kiosques àtransfert', 'tariff' => '1500', 'periodicity' => 'mois', 'tax_label_id' => 2],
            ['name' => 'Apatam aux abords des rues (PM)', 'tariff' => '15000', 'periodicity' => 'Forfait', 'tax_label_id' => 2],
            ['name' => 'Autorisation provisoire d\'Installation et d\'ouverture de débits de boisson', 'tariff' => '500', 'periodicity' => 'mois', 'tax_label_id' => 2],
        ];

        foreach ($taxableNamesTwo as $name) {
            //Taxable::create($name);
        }

        $taxableNamesThree=[
            ['name' => 'Panneau publicitaire moins 1m?', 'tariff' => '2000', 'periodicity' => 'mois', 'tax_label_id' => 6],
            ['name' => 'Panneau égale a 1m', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 6],
            ['name' => 'Panneaupublicitaire plus de 1m?', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 6],
            ['name' => 'Publicité et annonce par haut-parleur', 'tariff' => '1000', 'periodicity' => 'Forfait', 'tax_label_id' => 6],

        ];

        foreach ($taxableNamesThree as $name) {
           // Taxable::create($name);
        }

        $taxableNamesFour=[
            ['name' => 'Location pour foire commerciale', 'tariff' =>  '100000', 'periodicity' => 'Forfait', 'tax_label_id' => 7],
            ['name' => 'Location pour foire d\'exposition', 'tariff' =>  '100000', 'periodicity' => 'Forfait', 'tax_label_id' => 7],
            ['name' => 'Autre compétition', 'tariff' =>  '2000', 'periodicity' => 'mois', 'tax_label_id' => 7],
            ['name' => 'Location ed boutique', 'tariff' =>  '700', 'periodicity' => 'mois', 'tax_label_id' => 7],
            ['name' => 'Location ed magasin', 'tariff' =>  '700', 'periodicity' => 'mois', 'tax_label_id' => 7],

        ];

        foreach ($taxableNamesFour as $name) {
            //Taxable::create($name);
        }
    }
}
