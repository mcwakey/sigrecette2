<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
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
    <title>ETAT DE VERSEMENT DU REGISSEUR DES RECETTES</title>
</head>
<body>

<table>
    <tr>
        <td  colspan="4"  style="border: none; padding: 2px;"> {{$commune->region_name}} </td>
        <td  colspan="4" style="border: none; padding:2px ;text-align: right;">REPUBLIQUE TOGOLAISE</td>
    </tr>
    <tr>
        <td colspan="4" style="border: none; margin: 0; padding:2px ;">  {{$commune->title}}</td>
        <td colspan="4" style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">Travail-Liberté-Patrie</td>
    </tr>
    @php
        $year = \App\Models\Year::getActiveYear()
    @endphp
    Exercice : {{$year->name}}
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">
            ETAT DE VERSEMENT DU REGISSEUR DES RECETTES

        </td>

    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">
            (Recettes sur au comptant)

        </td>

    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">
            N°001
        </td>

    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0;">
            Nom du Régisseur : {{\App\Models\User::getRegisseurName()}}
        </td>

    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0;">
            Exercice : {{$year->name}}
        </td>

    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0;" >
            Période de :
        </td>
    </tr>
    <tr>
        <th rowspan="2">Date</th>
        <th rowspan="2">Motif de la recette</th>
        <th rowspan="2">Pièces justificatives des encaissements</th>
        <th colspan="2">Montant</th>
        <th rowspan="2">Imputation</th>
        <th colspan="2">Ordre de recette de régularisation</th>
    </tr>
    <tr>
        <td>Valeurs</td>
        <td>Quittances</td>
        <td>Date</td>
        <td>N°</td>
    </tr>
    @php
        $ssomme=0;
    @endphp
    @foreach($data as $item)
        <tr>
            <td>{{$item["DATE"]}}</td>
            <td>{{$item["DESCRIPTION"]}}</td>
            <td>{{$item["NUMÉRO DE QUITTANCE"]}}</td>
            <td></td>
            @php
                $ssomme+=$item["MONTANT"];
            @endphp
            <td>{{$item["MONTANT"]}}</td>
            <td>{{isset($item["CODE D'IMPUTATION"]) ?? $item["CODE D'IMPUTATION"] }} </td>
            <td></td>
            <td></td>
        </tr>

    @endforeach

    <tr>
        <td colspan="4" style=" margin: 0; text-align: center;">TOTAL</td>
        <td>{{ $ssomme}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="8"  style="border: none; margin: 5;padding: 5;">Arrêté le présent état de versement à la somme de e {{number_to_words($ssomme) }} CFA</td>
    </tr>
    <tr>
        <td colspan="4" style="border: none; margin: 5;padding: 5;">Le Receveur</td>
        <td colspan="4" style="border: none; margin: 5;padding: 5;"> Le Régisseur</td>
    </tr>
    <tr>
        <td colspan="4" style="border: none; margin: 5;padding: 5;"> [Signature, nom]</td>
        <td colspan="4" style="border: none; margin: 5;padding: 5;">[Signature, nom]</td>
    </tr>

</table>


</body>
</html>
