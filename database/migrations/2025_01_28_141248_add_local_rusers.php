<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        $password=null;
        if (env('APP_NAME') !== 'Giz') {
            return;
        }
        if(env('APP_ENV') == 'local') {
          $password="rc@2025";
        }
        DB::transaction(function () use ($password) {
            $role = Role::firstOrCreate(['name' => 'Agent de Recensement']);
            $users_table = [
                [
                    'name' => 'AGBEKPONOU Komi Damien',
                    'email' => 'agbekponou@gmail.com',
                    'password' => $password!=null ?  $password : Hash::make('@ko2i023'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'ALFA Nayou',
                    'email' => 'alfa@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@na34ou20'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'BAKAÏ Bidénam',
                    'email' => 'bakai@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@bind77enam1'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'BEWI Panabessé',
                    'email' => 'bewi@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@pa4$abe2'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'BEWI Solime',
                    'email' => 'solime@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@ioslime2'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'DERMANI Azime',
                    'email' => 'dermani@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@apime01'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'DJAKADA Lelouwazou',
                    'email' => 'djakada@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@kezz9pu22'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'HALAWI Essowèdéou',
                    'email' => 'halawi@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@es90owe2'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'KEDOU Koumaï Afissétou',
                    'email' => 'kedou@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@afittep2'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'KILIOUWE Gnimdéwa',
                    'email' => 'keliouwe@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('$nidekal22'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'KPENGUIYE Esso-Hanna',
                    'email' => 'kpenguiye@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@kina11'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'LAOUTOU Wassoutou',
                    'email' => 'laoutou@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@pas*o22'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'MAZIGNADA Essohanam',
                    'email' => 'mazignada@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@$ssoha1'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'PATOUZI Prénam Prince',
                    'email' => 'patouzi@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@pr$ionam2'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'PITASSA Essobatou',
                    'email' => 'pitassa@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@e%ssoba1'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'SINDJALIM Essowedéou',
                    'email' => 'sindjalim@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@#ssowe3'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'TAGNAM Mamouwa Armel',
                    'email' => 'tagnam@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@iomou22'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'TAKOUGNADI Tègbazondou',
                    'email' => 'takougnadi@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@pioegba22'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'TATCHOKE Esso-Tchélinam',
                    'email' => 'tatchoke@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@granpcheli3'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'TCHARA Tani Victoire',
                    'email' => 'tchara@gmail.com',
                    'password' =>  $password!=null ? $password : Hash::make('@atpni223'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($users_table as $user) {
                $super_user = User::create($user);
                $super_user->assignRole($role);
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
