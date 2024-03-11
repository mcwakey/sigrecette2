<?php

namespace App\Imports;

use App\Models\Taxpayer;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class TaxpayerImport implements ToModel, WithProgressBar,WithBatchInserts, WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user= new Taxpayer([
            'tnif' => fake()->randomNumber(3, 1, 10) . Str::random(5) . fake()->randomNumber(3, 0, 9),
            'name' => $row[10]." ".$row[11],
            'email' => fake()->unique()->safeEmail().uniqid("unique"),
            'email_verified_at' => now(),
            'gender' => $row[12]?:fake()->randomElement(['Homme', 'Femme']),
            'id_type' => fake()->randomElement(['CNI', 'PASSPORT', 'PERMIS DE CONDUIRE', 'CARTE D\'ELECTEUR', 'CARTE DE SEJOUR']),
           //'social_work'=>$row[12],
            'id_number' => random_int(1000000, 6000000),
            'mobilephone' => $row[15] ?: fake()->phoneNumber(),
            'telephone' => $row[16] ?: fake()->phoneNumber(),
            'longitude' =>  $row[9],
            'latitude' => $row[8],
            'address' => $row[7],
            'town_id' => random_int(1, 6),
            'erea_id' => random_int(1, 2),
            'zone_id' => random_int(1, 3),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),

        ]);
        //$user->setRelation('team', new Team(['name' => $row[1]]));
        return $user;
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
