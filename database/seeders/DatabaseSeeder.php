<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use App\Models\Canton;
use App\Models\Erea;
use App\Models\Invoice;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seedersArray = [];

        if (!app()->environment('production')) {
            array_push(
                $seedersArray,
                UsersSeeder::class,
                CantonsSeeder::class,
                TownsSeeder::class,
                EreasSeeder::class,
                TaxLabelsSeeder::class,
                TaxablesSeeder::class,
                ZonesSeeder::class,
            );
        }

        array_push(
            $seedersArray,
            RolesPermissionsSeeder::class,
            GendersSeeder::class,
            IdTypesSeeder::class,
        );

        $this->call($seedersArray);
    }
}
