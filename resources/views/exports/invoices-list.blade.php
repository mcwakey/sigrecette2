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
            padding: 5px;
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

    <title>Bordereau journal des avis des sommes à payer</title>
</head>
<body>

<table>


    <tr>
        <td colspan="5"  style="border: none; margin: 0">
            REGION PLATEAUX

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

            Bordereau Journal des avis des sommes à payer


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
        <th>N° Avis des sommes à payer</th>
        <th>Date d’émission</th>
        <th>N° OR </th>
        <th>NIC</th>
        <th>Nom ou raison sociale du contribuable</th>
        <th>N° de Téléphone</th>
        <th>Zone fiscale</th>
        <th>Canton- Quartier - ville - Adresse complète</th>
        <th>Coordonnées GPS</th>
        <th>Somme réduite ou annulée</th>
        <th>PC/ Rejeté</th>
    </tr>
    <tbody>
    @foreach($data as $index => $item)
        <tr>
            <td>{{$item[3]}}</td>
            <td>{{$item[9]}}</td>
            <td>{{$item[3]}}</td>
            <td>{{$item[1]}}</td>
            <td>{{$item[1]}}</td>


            @php
                $contribuable = $item[0];
                $lines = explode("\n", $contribuable);
                $derniereLigne = end($lines);
                $numeroTelephone = trim($derniereLigne);
            @endphp

            <td>{{$numeroTelephone}}</td>
            <td>zone f</td>
            <td>{{$item[0]}}</td>
            <td>{{$item[6]}}</td>
            <td>{{$item[7]}}</td>
            <td></td>
        </tr>
    @endforeach


    <tr>
        <td colspan="9" >TOTAL DU PRÉSENT BORDEREAU </td>
        <td>12 000</td>
        <td rowspan="3"></td>
    </tr>
    <tr>
        <td colspan="9" >TOTAL GÉNÉRAL DU PRÉCÉDENT BORDEREAU </td>
        <td></td>

    </tr>
    <tr>
        <td colspan="9">TOTAL GÉNÉRAL DU PRÉSENT BORDEREAU (À reporter)</td>
        <td>12 000</td>

    </tr>
    <tr>
        <td colspan="4" ></td>
        <td colspan="7">Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de : <span>douze mille francs CFA</span> </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;padding: 5px;"> A <span> Agou</span> le <span>23 janvier 2023</span> </td>
        <td colspan="6" style="border: none; margin: 0;padding: 0;">Le Maire</td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;padding: 0;"></td>
        <td colspan="6" style="border: none; margin: 0;padding: 0;">[Cachet, signature, Nom et prénoms]</td>
    </tr>
    </tbody>
</table>

</body>
</html>
