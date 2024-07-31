<?php

namespace App\Imports;

use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Erea;
use App\Models\Taxpayer;
use App\Models\Town;
use App\Models\Zone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class TaxpayerImport implements ToModel, WithProgressBar,WithBatchInserts, WithChunkReading, WithHeadingRow, ShouldQueue
{
    use Importable;
    use RemembersRowNumber;
    use RemembersChunkOffset;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function model(array $row)
    {

        $faker =  fake();
        if (!isset($row['nom'])
            || !isset($row['prenoms'])
            || !isset($row['adresse']  )
            || !isset($row['zone'])
            || !isset($row['activite'])
        ) {
            return null;
        }
        $existingTaxpayer = Taxpayer::where(
            'name', $row['nom'] . " ".$row['prenoms']
        )
            ->where('address', $row["adresse"])
            ->first();

        if ($existingTaxpayer) {
            return null;
        }
        $canton = isset($row['canton'])?Canton::firstOrCreate(['name' =>  $row['canton']]): Canton::firstOrCreate(['name' => "Aneho"]);
        $town = Town::firstOrCreate(['name' =>
            (isset($row['ville_village']) ? $row['ville_village'] . "/" : "" .
                isset($row['quartier'])) ? $row['quartier'] : "", 'canton_id' => $canton->id]);
        //$erea = Erea::firstOrCreate(['name' => $row['quartier'], 'town_id' => $town->id]);
        $zone = Zone::firstOrCreate(['name' => $row['zone']]);
        $category =  isset($row['categ_activite'])?Category::firstOrCreate(['name' => $row['categ_activite']]): Category::firstOrCreate(['name' => 'Non défini']);
        $activity= Activity::firstOrCreate(['name' => $row["activite"], 'category_id' => $category->id]);

       // dump($category,$activity);
        // Créer le modèle Taxpayer
        $taxpayer = new Taxpayer([
            'tnif' => $row['n°'] ?? fake()->randomNumber(3, 1, 10) . Str::random(5) . fake()->randomNumber(3, 0, 9),
            'name' => $row['nom'] . " ".$row['prenoms'],
            'email' => isset($row['email'])?$row['email']:"",
            'email_verified_at' => now(),
            'gender' => $row["sexe"] ?? $faker->randomElement(['Homme', 'Femme']),
            'id_type' => $faker->randomElement(['CNI', 'PASSPORT', 'PERMIS DE CONDUIRE', 'CARTE D\'ELECTEUR', 'CARTE DE SEJOUR']),
            'id_number' => $row["num_identification"] ?? $row["num_carte_electeur"] ?? random_int(1000000, 6000000),
            'mobilephone' => $row["telephone_1"] ?? " ",
            'telephone' => $row["telephone_2"] ?? " ",
            'longitude' => $row["longitude"]?? " ",
            'latitude' => $row["latitude"]?? " ",
            'address' => $row["adresse"]?? " ",
            'town_id' => $town->id,
            'zone_id' => $zone->id,
            'category_id'=>$category->id,
            'activity_id'=>$activity->id,
            "other_work"=>isset($row["autre_activite"])?$row["autre_activite"]:"" ,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'social_work'=>isset($row["raison sociale"])??$row["raison sociale"],
        ]);
        if(config('app.disable_taxpayers_on_load')){
            $taxpayer->delete();
        }

        return $taxpayer;
    }


    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
