@php

    // $result=\App\Models\StockTransfer::buildAndGetStockRequestWithQuery($data[0]);
 //dd($result);
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
    <title> ETAT DE COMPTABILITE DES VALEURS INACTIVES DU COLLECTEUR</title>
</head>
<body>


<table>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">{{$commune->region_name}} </td>
        <td colspan="7" style="border: none; margin: 0;text-align: right">REPUBLIQUE TOGOLAISE</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left"> {{$commune->title}}</td>
        <td colspan="7" style="border: none; margin: 0;text-align: right">Travail-Liberté-Patrie</td>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: center">
            ETAT DE COMPTABILITE DES VALEURS INACTIVES DU COLLECTEUR

        </td>

    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: center">
            N°001
        </td>

    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: left">
            @php
                $year = \App\Models\Year::getActiveYear()
            @endphp
            Exercice : {{$year->name}}
        </td>

    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: left">
            Période de recouvrement : 01 à 31
        </td>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: left">
            Nom du collecteur : Collecteur 1

    </tr>
    <tr>
        <td rowspan="3" style="margin: 0;text-align: center">Catégorie</td>
        <td rowspan="3" style="margin: 0;text-align: center">Valeur faciale</td>
        <td colspan="4" style="margin: 0;text-align: center">Reçu</td>
        <td colspan="4" style="margin: 0;text-align: center">Vendu</td>
        <td colspan="4" style="margin: 0;text-align: center">Rendu</td>
    </tr>
    <tr>

        <td colspan="2" style="margin: 0;text-align: center">N°</td>
        <td rowspan="2" style="margin: 0;text-align: center">Nombre</td>
        <td rowspan="2" style="margin: 0;text-align: center">Montant</td>
        <td colspan="2" style="margin: 0;text-align: center">N°</td>
        <td rowspan="2" style="margin: 0;text-align: center">Nombre</td>
        <td rowspan="2" style="margin: 0;text-align: center">Montant</td>
        <td colspan="2" style="margin: 0;text-align: center">N°</td>
        <td rowspan="2" style="margin: 0;text-align: center">Nombre</td>
        <td rowspan="2" style="margin: 0;text-align: center">Montant</td>


    </tr>
    <tr>
        <td style="margin: 0;text-align: center">De</td>
        <td style="margin: 0;text-align: center">A</td>
        <td style="margin: 0;text-align: center">De</td>
        <td style="margin: 0;text-align: center">A</td>
        <td style="margin: 0;text-align: center">De</td>
        <td style="margin: 0;text-align: center">A</td>


    </tr>


    <tr>
        <td>Tickets</td>
        <td>500</td>
        <td>05001
        <td>05300</td>
        <td>300</td>
        <td>150 000</td>
        <td></td>
        </td>
        <td> </td>
        </td>
        <td></td>
        <td></td>
        <td>

        </td>


        <td>

        </td>

        <td>

        </td>

        <td>

        </td>

    </tr>
    <tr>
        <td>Tickets</td>
        <td>500</td>
        <td>
        <td></td>
        <td></td>
        <td></td>
        <td>05001</td>
        </td>
        <td>05080 </td>
        </td>80
        <td>40000</td>
        <td></td>
        <td>

        </td>


        <td>

        </td>

        <td>

        </td>

        <td>

        </td>

    </tr>
    <tr>
        <td>Tickets</td>
        <td>500</td>
        <td>
        <td></td>
        <td></td>
        <td></td>
        <td>05081</td>
        </td>
        <td>05150 </td>
        </td>70
        <td>35000</td>
        <td></td>
        <td>

        </td>


        <td>

        </td>

        <td>

        </td>

        <td>

        </td>

    </tr>
    <tr>
        <td>Tickets</td>
        <td>500</td>
        <td>
        <td></td>
        <td></td>
        <td></td>
        <td>05151</td>
        </td>
        <td>05200 </td>
        </td>50
        <td>25000</td>
        <td></td>
        <td>

        </td>


        <td>

        </td>

        <td>

        </td>

        <td>

        </td>

    </tr>
    <tr>
        <td>Tickets</td>
        <td>500</td>
        <td>
        <td></td>
        <td></td>
        <td></td>
        <td>05201</td>
        </td>
        <td>05260 </td>
        </td>60
        <td>30000</td>
        <td></td>
        <td>

        </td>


        <td>

        </td>

        <td>

        </td>

        <td>

        </td>

    </tr>
    <tr>
        <td>Tickets</td>
        <td>500</td>
        <td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        </td>
        <td> </td>
        </td>
        <td></td>
        <td></td>
        <td>
            5261
        </td>


        <td>
            5300
        </td>


        <td>
            40
        </td>

        <td>
            20000
        </td>

    </tr>
    <tr>
        <td colspan="5" style="margin: 0;text-align: center">Total</td>
        <td>150000</td>
        <td colspan="3"></td>
        <td>130000</td>
        <td colspan="3"></td>
        <td>20000</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Reçu du Régisseur des valeurs pour un montant
            de cent cinquante mille Francs CFA
        </td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Reçu du collecteur des valeurs invendues pour un montant de vingt mille.Francs CFA
        </td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">A {{$commune->title}}, le 19 Avril 2024</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">A {{$commune->title}}, le 19 Avril 2024.</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Le Collecteur</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Le Régisseur de recettes,</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">(nom et signature)</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">(nom, signature et cachet du régisseur)</td>
    </tr>

</table>


</body>
</html>
