<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0;
            font-size: 0.75em;
            padding: 5px;
            align-items: center;
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

        p {
            margin-top: 10px;
        }
    </style>
    <title>Bordereau journal des avis de réduction ou d’annulation</title>
</head>
<body>

    <table>
        <caption>REGION PLATEAUX - Commune de Agou - REPUBLIQUE TOGOLAISE</caption>
        <tr>
            <td>Travail-Liberté-Patrie</td>
        </tr>
        <tr>
            <td>Bordereau journal des avis </td>
        </tr>
        <tr>
            <td>Exercice : 2023</td>
        </tr>
    </table>

    <table>

        <thead>
            <tr>
                <th>N° Avis de réduction ou d’annulation</th>
                <th>Date d’émission</th>
                <th>N° Avis réduit ou annulé</th>
                <th>N° OR d’annulation ou réduction</th>
                <th>NIC</th>
                <th>Nom ou raison sociale</th>
                <th>N° de Téléphone</th>
                <th>Adresse complète</th>
                <th>Coordonnées GPS</th>
                <th>Somme réduite ou annulée</th>
                <th>PC/ Rejeté</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $index => $item)
            <tr>
                <td>{{$item[3]}}</td>
                <td>{{$item[9]}}</td>
                <td>{{$item[3]}}</td>
                <td>{{$item[1]}}</td>
                <td>{{$item[1]}}</td>
                <td>{{$item[3]}}</td>

                @php
                    $contribuable = $item[0];
                    $lines = explode("\n", $contribuable);
                    $derniereLigne = end($lines);
                    $numeroTelephone = trim($derniereLigne);
                @endphp

                <td>{{$numeroTelephone}}</td>
                <td>{{$item[0]}}</td>
                <td>{{$item[6]}}</td>
                <td>{{$item[7]}}</td>
                <td></td>
            </tr>
        @endforeach


        <tr>
                <td colspan="9" style="text-align: right;">TOTAL DU PRÉSENT BORDEREAU D’ANNULATION</td>
                <td>12 000</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">TOTAL GÉNÉRAL DU PRÉCÉDENT BORDEREAU D’ANNULATION</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">TOTAL GÉNÉRAL DU PRÉSENT BORDEREAU D’ANNULATION (À reporter)</td>
                <td>12 000</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right;">Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de : douze mille francs CFA</td>
                <td colspan="6" style="text-align: right;">A Agou le 23 janvier 2023 Le Maire, [Cachet, signature, Nom et prénoms]</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
