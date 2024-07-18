@php use App\Helpers\InvoiceHelper; @endphp
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
    <title>Fiche de recouvrement des avis distribués</title>
</head>

<body>

<table>
    <tr>
        <td colspan="1" style="border: none; margin: 0;text-align: left">

            <img src="{{ $commune-> getImageUrlAttribute() }}" alt="Logo" style="width: 50px; height: 50px;">

        </td>
        <td colspan="6" style="border: none; padding: 2px;">
            {{$commune->region_name}}

        </td>
        <td colspan="7" style="border: none; padding:2px ;text-align: right;">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="1" style="border: none; margin: 0; padding:2px ;text-align: left;">
        <td colspan="6" style="border: none; margin: 0; padding:2px ;">

            {{$commune->title}}
        </td>
        <td colspan="7" style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">

            Travail-Liberté-Patrie
        </td>
    </tr>
    <tr>
        <th colspan="14" style="border: none; margin: 0; text-align: center;" class="caption">Fiche de recouvrement des
            avis distribués
        </th>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0; text-align: center;">N° {{$print->last_sequence_number}}</td>
    </tr>
    <tr>
        @php
            $year = \App\Models\Year::getActiveYear()
        @endphp
        <td colspan="14" style="border: none; margin: 0;">Exercice : {{" ".$year->name}}</td>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;">Zone fiscale :</td>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;">Nom de l’agent de recouvrement : {{$print->user->name}}</td>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;">Période de distribution:</td>
    </tr>
    <tr>

        <td rowspan="2">N° Avis</td>
        <td rowspan="2">N° OR</td>
        <td rowspan="2">Motif de la recette</td>
        <td rowspan="2">Imputation budgétaire</td>
        <td rowspan="2">NIC</td>
        <td rowspan="2">Raison Sociale / Nom et prénoms du contribuable</td>
        <td rowspan="2">Coordonnées GPS</td>
        <td rowspan="2">Adresse complète</td>
        <td colspan="3">Recouvrements antérieurs</td>
        <td colspan="3">Recouvrement courant</td>
    </tr>
    <tr>

        <td>Somme due</td>
        <td>Sommes déjà recouvrées</td>
        <td>Reste à recouvrer</td>
        <td>Date de recouvrement</td>

        <td>Somme recouvrée</td>
        <td>N° quittance</td>
    </tr>

    @foreach($data as $index => $item)
        @if($item instanceof \App\Models\Invoice)
            @foreach(InvoiceHelper::sumAmountsByTaxCode($item) as $code => $tax)

                @php
                    $paid = \App\Models\Payment::getSumPaymentByCode($code,$item);
                @endphp
                <tr>

                    <td>{{$item->invoice_no}}</td>
                    <td>{{$item->order_no}}</td>
                    <td>{{\App\Models\TaxLabel::getNameByCode($code)}}</td>
                    <td>{{$code}}</td>
                    <td>{{$item->nic}}</td>
                    <td>{{$item->taxpayer?->name}}</td>
                    <td>{{$item->taxpayer?->longitude,$item->taxpayer?->latitude}}</td>
                    <td>{{$item->taxpayer?->address}}</td>
                    <td>{{$tax['amount']}}</td>
                    <td>{{$paid}}</td>
                    <td>{{$tax['amount']-$paid}}</td>


                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    @endforeach


</table>

</body>
</html>
