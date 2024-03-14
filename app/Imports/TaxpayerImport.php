<?php

namespace App\Imports;

use App\Models\Canton;
use App\Models\Erea;
use App\Models\Taxpayer;
use App\Models\Town;
use App\Models\Zone;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class TaxpayerImport implements ToModel, WithProgressBar,WithBatchInserts, WithChunkReading, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        //todo update logic to create taxpayers taxables
        $taxpayer= null;
        $canton= null;
        $town= null;
        $erea= null;
        $zone= null;
        if (
            ($cantonName = $row['canton'] ?? null) !== null &&
            ($townName = $row['ville_village'] ?? null) !== null &&
            ($ereaName = $row['quartier'] ?? null) !== null &&
            ($zoneName = $row['zone'] ?? null) !== null
        ) {
            $canton = Canton::firstOrCreate(['name' => $cantonName]);
            $town = Town::firstOrCreate(['name' => $townName, 'canton_id' => $canton->id]);
            $erea = Erea::firstOrCreate(['name' => $ereaName, 'town_id' => $town->id]);
            $zone = Zone::firstOrCreate(['name' => $zoneName]);
        }
        if (app()->environment('local')) {




            //dd($canton,$town,$erea,$zone);

            $taxpayer= new Taxpayer([
                'tnif' => fake()->randomNumber(3, 1, 10) . Str::random(5) . fake()->randomNumber(3, 0, 9),
                'name' => $row['nom']." ".$row['prenoms'],
                'email' => fake()->unique()->safeEmail().uniqid("unique"),
                'email_verified_at' => now(),
                'gender' => $row["sexe"]?:fake()->randomElement(['Homme', 'Femme']),
                'id_type' => fake()->randomElement(['CNI', 'PASSPORT', 'PERMIS DE CONDUIRE', 'CARTE D\'ELECTEUR', 'CARTE DE SEJOUR']),
                //'social_work'=>$row[12],
                'id_number' =>$row[ "num_identification"]?:$row["num_carte_electeur"]?:random_int(1000000, 6000000),
                'mobilephone' => $row["telephone_1"] ?: fake()->phoneNumber(),
                'telephone' => $row[ "telephone_2"] ?: fake()->phoneNumber(),
                'longitude' =>  $row["longitude"],
                'latitude' => $row["latitude"],
                'address' => $row["adresse"],
                'town_id' => $town->id?:random_int(1, 6),
                'erea_id' => $erea->id?: random_int(1, 2),
                'zone_id' => $zone->id ?: random_int(1, 3),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),

            ]);
            //$user->setRelation('team', new Team(['name' => $row[1]]));

        }
        else{
            $taxpayer= new Taxpayer([
                'tnif' => fake()->randomNumber(3, 1, 10) . Str::random(5) . fake()->randomNumber(3, 0, 9),
                'name' => $row['nom']." ".$row['prenoms'],
                'email' => fake()->unique()->safeEmail().uniqid("unique"),
                'email_verified_at' => now(),
                'gender' => $row["sexe"]?:fake()->randomElement(['Homme', 'Femme']),
                'id_type' => fake()->randomElement(['CNI', 'PASSPORT', 'PERMIS DE CONDUIRE', 'CARTE D\'ELECTEUR', 'CARTE DE SEJOUR']),
                //'social_work'=>$row[12],
                'id_number' =>$row[ "num_identification"]?:$row["num_carte_electeur"]?:random_int(1000000, 6000000),
                'mobilephone' => $row["telephone_1"] ?: fake()->phoneNumber(),
                'telephone' => $row[ "telephone_2"] ?: fake()->phoneNumber(),
                'longitude' =>  $row["longitude"],
                'latitude' => $row["latitude"],
                'address' => $row["adresse"],
                'town_id' => $town->id?:random_int(1, 6),
                'erea_id' => $erea->id?: random_int(1, 2),
                'zone_id' => $zone->id ?: random_int(1, 3),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),

            ]);
        }
        return $taxpayer;
    }
    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
