<?php

namespace Database\Seeders;

use App\Models\Town;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TownsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Adjouyiko, Adougba, Ahonga-Kopé, Anokoui-Komékopé, Anokoui-Ahokopé, Anomegblé, Anomegblé, Anonkui-Nogo, Apenyigbi, Démakpoè, Djingble, Fiovi, Gbonvié-Anomé, Gnamassigan, Kitidjan, Klévé-Apégnigbi, Klévé-Assiyéyé, Kové, Logope-Agbanyokopé, Logopé-Atsanvé, Logopé-Houmbigblé, Logopé-Kpatefi, Netimé, Nyavimé-Aveyimé, Sogbossito, Sogbossito- Apelebuimé, Sogbossito-Aziasikopé, Télessou-Adokpokopé, Télessou-Agbodekakopé, Togomé, Totsi-Nyivémé, Totsi-Kpatefi-Cacavéli, Zogbégan,

        $cantonNameOne = [
            'Adjouyiko',
            'Adougba',
            'Ahonga-Kopé',
            'Anokoui-Komékopé',
            'Anokoui-Ahokopé',
            'Anomegblé',
            'Anonkui-Nogo',
            'Apenyigbi',
            'Démakpoè',
            'Djingble',
            'Fiovi',
            'Gbonvié-Anomé',
            'Gnamassigan',
            'Kitidjan',
            'Klévé-Apégnigbi',
            'Klévé-Assiyéyé',
            'Kové',
            'Logope-Agbanyokopé',
            'Logopé-Atsanvé',
            'Logopé-Houmbigblé',
            'Logopé-Kpatefi',
            'Netimé',
            'Nyavimé-Aveyimé',
            'Sogbossito',
            'Sogbossito- Apelebuimé',
            'Sogbossito-Aziasikopé',
            'Télessou-Adokpokopé',
            'Télessou-Agbodekakopé',
            'Togomé',
            'Totsi-Nyivémé',
            'Totsi-Kpatefi-Cacavéli',
            'Zogbégan',
        ];

        foreach ($cantonNameOne as $name) {
            if (!app()->environment('production')) {
            Town::create(['name' => $name], ['canton_id' => 1]);
            }
        }

        //Hossoukopé, Logogomé, Elavagno-Atsanvé, Elavagno-Klévé, Awoudja-Kopé, Dansakopé
        $cantonNameTwo=[
            'Hossoukopé',
            'Logogomé',
            'Elavagno-Atsanvé',
            'Elavagno-Klévé',
            'Awoudja-Kopé',
            'Dansakopé',
        ];

        foreach ($cantonNameTwo as $name) {
            if (!app()->environment('production')) {
                Town::create(['name' => $name], ['canton_id' => 2]);
            }
        }
    }
}
