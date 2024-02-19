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
        $this->call([
            UsersSeeder::class,
            RolesPermissionsSeeder::class,
            TaxpayersSeeder::class,
            CantonsSeeder::class,
            TownsSeeder::class,
            EreasSeeder::class,
            TaxLabelsSeeder::class,
            TaxablesSeeder::class,
            ZonesSeeder::class,
            // InvoiceSeeder::class,
            // TaxpayerTaxablesSeeder::class,
            GendersSeeder::class,
            IdTypesSeeder::class,
        ]);


        User::factory(20)->create();
        //Address::factory(20)->create();
        Taxpayer::factory(5000)->create();
        //Canton::factory(10)->create();
        //Town::factory(30)->create();
        //Erea::factory(100)->create();
        //TaxLabel::factory(10)->create();
        //Taxable::factory(100)->create();
        //Invoice::factory(5)->create();
        //TaxpayerTaxable::factory(20)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
