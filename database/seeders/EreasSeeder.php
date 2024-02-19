<?php

namespace Database\Seeders;

use App\Models\Erea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Atsanvé, Houmbi, Kpatefi, Klévé, Apelebuimé, Adidodokpo, Nyivémé
        $townNameOne=[
            'Atsanvé',
            'Houmbi',
            'Kpatefi',
            'Klévé',
            'Apelebuimé',
            'Adidodokpo',
            'Nyivémé',
        ];

        foreach ($townNameOne as $name) {
            Erea::create(['name' => $name], ['Town_id' => 1]);
        }
    }
}
