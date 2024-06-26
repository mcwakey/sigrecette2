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
        <td  colspan="4"> {{$commune->region_name}} </td>
        <td  colspan="4">REPUBLIQUE TOGOLAISE</td>
    </tr>
    <tr>
        <td colspan="4">  {{$commune->title}}</td>
        <td colspan="4">Travail-Liberté-Patrie</td>
    </tr>
    @php
        $year = \App\Models\Year::getActiveYear()
    @endphp
    Exercice : {{$year->name}}
    <tr>
        <td colspan="5">
            ETAT DE VERSEMENT DU REGISSEUR DES RECETTES

        </td>

    </tr>
    <tr>
        <td colspan="8">
            (Recettes sur au comptant)

        </td>

    </tr>
    <tr>
        <td colspan="8">
            N°001
        </td>

    </tr>
    <tr>
        <td colspan="8">
            Nom du Régisseur : {{\App\Models\User::getRegisseurName()}}
        </td>

    </tr>
    <tr>
        <td colspan="8">
            Exercice : {{$year->name}}
        </td>

    </tr>
    <tr>
        <td colspan="8">
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
        <td colspan="4">TOTAL</td>
        <td>{{ $ssomme}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="8">Arrêté le présent état de versement à la somme de e {{number_to_words($ssomme) }} CFA</td>
    </tr>
    <tr>
        <td colspan="4">Le Receveur</td>
        <td colspan="4"> Le Régisseur</td>
    </tr>
    <tr>
        <td colspan="4"> [Signature, nom]</td>
        <td colspan="4">[Signature, nom]</td>
    </tr>

</table>


</body>
</html>
