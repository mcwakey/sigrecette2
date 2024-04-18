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
    <title>État de versement du Régisseur des Recettes (Recettes sur titre)</title>
</head>
<body>

<table>
    <tr>
        <td  colspan="2"> {{$commune->region_name}} </td>
        <td  colspan="3">REPUBLIQUE TOGOLAISE</td>
    </tr>
    <tr>
        <td colspan="2">  {{$commune->title}}</td>
        <td colspan="3">Travail-Liberté-Patrie</td>
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
        <td colspan="5">
            (Recettes sur au comptant)

        </td>

    </tr>
    <tr>
        <td colspan="5">
            N°001
        </td>

    </tr>
    <tr>
        <td colspan="5">
            Nom du régisseur :
        </td>

    </tr>
    <tr>
        <td colspan="5">
            Exercice : {{$year->name}}
        </td>

    </tr>
    <tr>
        <td colspan="5">
            Période de : 05/01 à 07/01
        </td>
    </tr>
    <tr>
        <th>Date</th>
        <th>Motif de la recette</th>
        <th>Pièces justificatives des encaissements</th>
        <th>Montant</th>
        <th>Imputation</th>
        <th>Odre de de recette de regularisation </th>
    </tr>
    <tr>
        <th>valeurs</th>
        <th>>Quitance</th>
        <th>Pièces justificatives des encaissements</th>
        <th>Montant</th>
        <th>Imputation</th>
        <th>Ordre de recette de régularisation</th>
    </tr>

    @php
    ssomme=0;
    @endphp


    @foreach($data as $item)
        @php
            ssomme+=$item["MONTANT"];
        @endphp
        <tr>
            <td>{{$item["DATE"]}}</td>
            <td>{{$item["NO D'AVIS"]}}</td>
            <td>{{$item["NO D'ORDRE DE RECETTE"]}} </td>
            <td>{{$item["NUMÉRO DE QUITTANCE"]}}</td>
            <td>{{$item["MONTANT"]}}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="3">TOTAL</td>
        <td></td>
        <td>{{ $ssomme}}</td>
    </tr>
    <tr>
        <td colspan="5">Arrêté le présent état de versement à la somme de e {{number_to_words($ssomme) }} CFA</td>
    </tr>
    <tr>
        <td colspan="3">Le Receveur</td>
        <td colspan="2"> Le Régisseur</td>
    </tr>
    <tr>
        <td colspan="3"> [Signature, nom]</td>
        <td colspan="2">[Signature, nom]</td>
    </tr>

</table>


</body>
</html>
