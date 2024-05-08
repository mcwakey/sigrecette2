<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 0.75em;
            padding: 0px;
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

        th, td {
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        caption {
            font-size: 1.2em;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
    <title>Registre-journal des déclarations préalables des usagers</title>
</head>
<body>


    <table>
        <tr>
            <td colspan="1"  style="border: none; margin: 0;text-align: left">

                <img src="{{ $commune-> getImageUrlAttribute() }}" alt="Logo" style="width: 50px; height: 50px;">

            </td>
            <td colspan="6"  style="border: none; margin: 0;text-align: left">

                {{$commune->region_name}}

            </td>
            <td colspan="8"  style="border: none; margin: 0;text-align: right">
                REPUBLIQUE TOGOLAISE

            </td>
        </tr>
        <tr>
            <td colspan="1" style="border: none; margin: 0; padding:2px ;text-align: left;">
            </td>
            <td colspan="6"  style="border: none; margin: 0;text-align: left">

                {{$commune->title}}
            </td>
            <td colspan="8"  style="border: none; margin: 0;text-align: right">

                Travail-Liberté-Patrie
            </td>
        </tr>



        <tr>
            <th colspan="15" style="border: none; margin: 0;">

                Registre-journal des déclarations préalables des usagers


            </th>


        </tr>
        <tr>
            <td colspan="15" style="margin-left: 2px;text-align:left;padding:4px ">
                @php
                    $year = \App\Models\Year::getActiveYear()
                @endphp
                Exercice : {{$year->name}}

            </td>
        </tr>


        <tr>
                <th>N° Enregistrement</th>
                <th>Date de demande</th>
                <th>Objet de la demande</th>
                <th>Nom du demandeur</th>
                <th>N° de Téléphone</th>
                <th>Adresse du demandeur</th>
                <th>Service chargé du traitement</th>
                <th>Imputation</th>
                <th>Tarif</th>
                <th>Base de calcul</th>
                <th>Somme due</th>
                <th>N° Avis des sommes à payer</th>
                <th>Réf. Contrat</th>
                <th>Somme payée</th>
                <th>N° quittance</th>
            </tr>
        @foreach($data   as $index => $item)
            <tr>
                <td>{{$index}}</td>
                <td>{{date("d-m-Y", strtotime( $item->created_at )) }}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->taxpayer?->name}}</td>
                <td>{{$item->taxpayer?->mobilephone}}</td>
                <td>{{$item->taxpayer?->town?->canton->name."-".$item->taxpayer?->town?->name."-".$item->taxpayer?->address}}</td>
                <td></td>
                <td> {{$item->taxpayer_taxable->taxable->tax_label->code}}</td>
                <td>{{$item->ii_tariff}}</td>
                <td>{{$item->ii_seize}}</td>
                <td>{{$item->amount}}</td>
                <td>{{$data->invoice_no}}</td>
                <td></td>
                <td>{{$item->amount}}</td>
                <td>{{$item->order_no}}</td>
            </tr>
        @endforeach
    </table>

</body>
</html>
