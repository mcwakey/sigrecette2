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
    <title>État de versement du Régisseur des Recettes (Recettes sur titre)</title>
</head>
<body>

<table>
    <tr>
        <td  colspan="2" style="border: none; padding: 2px;"> {{$commune->region_name}} </td>
        <td  colspan="3"  style="border: none; padding:2px ;text-align: right;">REPUBLIQUE TOGOLAISE</td>
    </tr>
    <tr>
        <td colspan="2"  style="border: none; margin: 0; padding:2px ;">  {{$commune->title}}</td>
        <td colspan="3"  style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">Travail-Liberté-Patrie</td>
    </tr>
    @php
        $year = \App\Models\Year::getActiveYear()
    @endphp
    Exercice : {{$year->name}}
    <tr>
        <td colspan="5"  style="border: none; margin: 0; text-align: center;">
            ETAT DE VERSEMENT DU REGISSEUR DES RECETTES

        </td>

    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0; text-align: center;">
            (Recettes sur titre)

        </td>

    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0; text-align: center;">
            N°001
        </td>

    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;" >
            Nom du régisseur :
        </td>

    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;" >
            Exercice : {{$year->name}}
        </td>

    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;" >
            Période de : 05/01 à 07/01
        </td>
    </tr>
    <tr>
        <th>Date</th>
        <th>N° Avis des sommes à payer</th>
        <th>N° ordre de recettes</th>
        <th>Pièces justificatives des encaissements</th>
        <th>Montant</th>
    </tr>

    @php
    $somme=0;
    @endphp


    @foreach($data as $item)
        @php
            $somme+=$item["MONTANT"];
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
        <td>{{ $somme}}</td>
    </tr>
    <tr>
        <td colspan="5">Arrêté le présent état de versement à la somme de e {{number_to_words($somme) }} CFA</td>
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
