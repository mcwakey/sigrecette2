@php
	use Carbon\Carbon;
    $year= \App\Models\Year::getActiveYear();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 0.75em;
            padding: 2px;
            margin: 0 auto;
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }



        th {
            background-color: #f2f2f2;
        }

        .caption {
            font-size: 1.2em;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
    <title>Fiche de distribution des avis</title>
</head>
<body>

<table>
    <tr>
        <td colspan="1"  style="border: none; margin: 0;text-align: left">

            <img src="{{ $commune-> getImageUrlAttribute() }}" alt="Logo" style="width: 50px; height: 50px;">

        </td>
        <td colspan="5"  style="border: none; padding: 2px;">
            {{$commune->region_name}}

        </td>
        <td colspan="6"  style="border: none; padding:2px ;text-align: right;">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="1" style="border: none; margin: 0; padding:2px ;text-align: left;">
        </td>
        <td colspan="5" style="border: none; margin: 0; padding:2px ;">

            {{$commune->title}}
        </td>
        <td colspan="6"  style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">

            Travail-Liberté-Patrie
        </td>
    </tr>
    <tr>
        <th colspan="12" style="border: none; margin: 0; text-align: center;" class="caption">Fiche de distribution des avis</th>
    </tr>
    <tr>
        <td colspan="12" style="border: none; margin: 0; text-align: center;">N°{{$print->last_sequence_number}}</td>
    </tr>
    <tr>
        <td colspan="12" style="border: none; margin: 0;" >Exercice : {{" ".$year->name}}</td>
    </tr>
    <tr>
        <td colspan="12" style="border: none; margin: 0;">Zone fiscale : </td>
    </tr>
    <tr>
        <td colspan="12" style="border: none; margin: 0;" >Nom de l’agent de recouvrement : {{$agent->name}} </td>
    </tr>
    <tr>
        <td  colspan="12" style="border: none; margin: 0;" >Période de distribution: </td>
    </tr>
    <tr>
        <th>N° Avis</th>
        <th>N° OR</th>
        <th>NIC</th>
        <th>Nom ou raison sociale du débiteur</th>
        <th>N° Téléphone</th>
        <th>Canton</th>
        <th>Quartier / Village</th>
        <th>Adresse complète</th>
        <th>Coordonnées GPS</th>
        <th>Somme due</th>
        <th>Date de notification</th>
        <th>Nom et émargement du réceptionnaire</th>
    </tr>
    @php
        $total_somme = 0;
    @endphp

    @foreach($data as $index => $item)


    @if($item->order_no!=null)
            @php
                $total_somme +=intval($item->amount);
            @endphp
    <tr>
        <td style="text-align: center">{{$item->invoice_no}}</td>
        <td style="text-align: center">{{$item->order_no}}</td>
        <td style="text-align: center">{{$item->nic}}</td>
        <td style="text-align: center">{{$item->taxpayer?->name}}</td>
        <td style="text-align: center">{{$item->taxpayer?->mobilephone}}</td>
        <td style="text-align: center">{{$item->taxpayer?->town->canton->name}}</td>
        <td style="text-align: center">{{$item->taxpayer?->town->name}}</td>
        <td style="text-align: center">{{$item->taxpayer?->address}}</td>
        <td style="text-align: center">{{$item->taxpayer?->longitude,$item->taxpayer?->latitude}}</td>
        <td style="text-align: center">{{$item->amount}}</td>
        @if ($item->delivery_date != null)
        <td style="text-align: center">
            {{$item->delivery_date}}
        </td>
        <td style="text-align: center">{{$item->delivery_to}}</td>
            @else
            <td>
            </td>
            <td>
            </td>

        @endif
    </tr>
        @endif
    @endforeach

    <tr>
        <td colspan="9" style="text-align: center;">TOTAL</td>
        <td>{{ $total_somme}}</td>
        <td></td>
        <td></td>
    </tr>

</table>

</body>
</html>
