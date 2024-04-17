<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
    <title>Journal des avis des sommes à payer confiés par le receveur</title>
</head>
<body>

<table>

    <tr>
        <td colspan="5"  style="border: none; margin: 0">
            {{$commune->region_name}}

        </td>
        <td colspan="6"  style="border: none; margin: 0">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0">

            Commune de Agou
        </td>
        <td colspan="6"  style="border: none; margin: 0">

            Travail-Liberté-Patrie
        </td>
    </tr>



    <tr>
        <th colspan="11" style="border: none; margin: 0;">

            {{$titles[15]}}


        </th>


    </tr>
    <tr>
        <td colspan="11" style="border: none; margin: 0">

            N°


        </td>


    </tr>
    <tr>
        <td colspan="11" style="margin-left: 2px;text-align:left;padding:4px ">
            Exercice : 2023

        </td>
    </tr>

    <tr>
        <th>Date réception/encaissement</th>
        <th>N° OR</th>
        <th>N° Avis des sommes à payer</th>
        <th>NIC</th>
        <th>Nom ou raison sociale du contribuable</th>
        <th>Coordonnées GPS</th>
        <th>Imputation</th>
        <th>Montant émis</th>
        <th>N° quittance</th>
        <th>Montant encaissé/annulé</th>
        <th>Reste à recouvrer</th>
    </tr>
    <tr>
        <td>05/01</td>
        <td>003/23</td>
        <td>003/23</td>
        <td>00013</td>
        <td>Florence</td>
        <td>XY</td>
        <td>726112</td>
        <td>24 000</td>
        <td></td>
        <td></td>
        <td>204 000</td>

    </tr>
    @foreach($data as $index => $item)
    <tr>
        <td>{{$item[1]}}</td>
        <td>{{date("d-m-Y", strtotime( $item[11]))}}</td>
        <td></td>
        <td>{{$item[3]}}</td>
        <td></td>
        <td></td>
        <td>{{$item[4]}}</td>
        <td>{{$item[5]}}</td>
        <td>{{$item[6]}}</td>
        <td>{{$item[8]}}</td>
        <td>{{$item[9]}}</td>
    </tr>
    @endforeach


    <tr>
        <td colspan="10" style="text-align: right;">TOTAL</td>
        <td>204 000</td>
    </tr>
    </tbody>
</table>

</body>
</html>
