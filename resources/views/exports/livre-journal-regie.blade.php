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
    <title>LIVRE-JOURNAL DE REGIE</title>
</head>
<body>
<table >
    <tr>
        <td colspan="4"  style="border: none; padding: 2px;text-align: left;">
            {{$commune->region_name}}

        </td>
        <td colspan="4"  style="border: none; padding:2px ;text-align: right;">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="4" style="border: none; margin: 0; padding:2px ;text-align: left;">

            {{$commune->title}}
        </td>
        <td colspan="4"  style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">

            Travail-Liberté-Patrie
        </td>
    </tr>
    <tr>
        <th colspan="8" style="border: none; margin: 0; text-align: center;" class="caption"> LIVRE-JOURNAL DE REGIE</th>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0;text-align: left;" >Exercice : {{" ".$year->name}}</td>
    </tr>




    <tr>

        <td rowspan="2">Date de l’opération</td>
        <td rowspan="2">Description de l’opération</td>
        <td rowspan="2">Imputation budgétaire</td>
        <td rowspan="2">N° de la quittance </td>
        <td colspan="2">Recette</td>
        <td rowspan="2">Versement</td>
        <td rowspan="2">Solde</td>
    </tr>
    <tr>

        <td>Numéraire</td>
        <td>Chèque</td>

    </tr>
    <tr>
        <td colspan="3">Report</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>
    @foreach($data as $item)
        <tr>

            <td>{{$item["DATE"]}}</td>
            <td>{{$item["DESCRIPTION"]}}</td>
            <td>{{$item["CODE D'IMPUTATION"]}}</td>
            <td>{{$item["NUMÉRO DE QUITTANCE"]}}</td>
            <td>{{$item["MONTANT"]}}</td>
            <td></td>
            <td>{{$item["VERSEMENT"]}}</td>
            <td>{{$item["SOLDE"]}}</td>

        </tr>
    @endforeach

    <tr>

        <td colspan="4">Total à reporter</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>

</table>





</body>
</html>
