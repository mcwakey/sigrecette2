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
        <td  colspan="7" style="border: none; margin: 0;text-align: right">Travail-Liberté-Patrie</td>
    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: center">
            ETAT DE COMPTABILITE DES VALEURS INACTIVES DU COLLECTEUR

        </td>

    </tr>
    <tr>
        <td  colspan="14"  style="border: none; margin: 0;text-align: left">
            N°001
        </td>

    </tr>
    <tr>
        <td  colspan="14"  style="border: none; margin: 0;text-align: left">
            @php
                $year = \App\Models\Year::getActiveYear()
            @endphp
            Exercice : {{$year->name}}
        </td>

    </tr>
    <tr>
        <td colspan="14" style="border: none; margin: 0;text-align: left">
            Période de recouvrement : ... à ....
        </td>
    </tr>
    <tr>
        <td  colspan="14" style="border: none; margin: 0;text-align: left">
            Nom du collecteur :  {{\Illuminate\Support\Facades\Auth::user()->name}}

    </tr>
    <tr>
        <td rowspan="3" style="margin: 0;text-align: center">Catégorie</td>
        <td rowspan="3" style="margin: 0;text-align: center">Valeur faciale</td>
        <td colspan="4" style="margin: 0;text-align: center">Reçu</td>
        <td  colspan="4" style="margin: 0;text-align: center">Vendu</td>
        <td  colspan="4" style="margin: 0;text-align: center">Rendu</td>
    </tr>
    <tr>
        <td rowspan="2" style="margin: 0;text-align: center">Nombre</td>
        <td colspan="2" style="margin: 0;text-align: center">N°</td>
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

    @php
        $total = array_reduce($data, function ($carry, $item) {
     return $carry + intval($item['Montant Recu']);
 }, 0);
$total_vendu = array_reduce($data, function ($carry, $item) {
     return $carry + intval($item['Montant Vendu']);
 }, 0);
$total_rendu = array_reduce($data, function ($carry, $item) {
     return $carry + intval($item['Montant Rendu']);
 }, 0);
    @endphp
    @foreach($data as $index => $item)
        @if($item['Statut']=="COMPTE RENDU")
            <tr>
                <td>{{ explode(" ", $item[__('taxable')])[0] }}</td>
                <td>{{$item[__('tariff')]}}</td>
                <td>
                    @if($item["Montant Rendu"]=="")
                        {{ explode(" - ", $item['No . De - A'])[0] }}</td>
                    @endif

                <td>
                    @if($item["Montant Rendu"]=="" && $item["Qté Rendu"]=="")
                        {{ explode(" - ", $item['No . De - A'])[1] }}</td>
                    @endif
                </td>
                <td>{{$item['Qté Recu']}}</td>
                <td>{{ $item['Montant Recu']}}</td>
                <td>{{ explode(" - ", $item['No . De - A'])[0] }}</td></td>
                <td> {{ explode(" - ", $item['No . De - A'])[1] }}</td></td>
                <td>{{$item['Qté Vendu']}}</td>
                <td>{{$item['Montant Vendu']}}</td>
                <td>

                        {{ explode(" - ", $item['No . De - A'])[0] }}</td>


                <td>
                        {{ explode(" - ", $item['No . De - A'])[1] }}</td>

                <td>
                    @if($item["Montant Rendu"]!="")
                        {{$item["Qté Rendu"]}}
                    @endif
                </td>
                <td>
                    @if($item["Montant Rendu"]!="")
                    {{$item["Montant Rendu"]}}
                    @endif
                </td>
            </tr>
        @endif

    @endforeach
    <tr>
        <td colspan="5" style="margin: 0;text-align: center">Total</td>
        <td>{{$total}}</td>
        <td colspan="3"></td>
        <td >{{$total_vendu}}</td>
        <td colspan="3"></td>
        <td >{{$total_rendu}}</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Reçu du Régisseur des valeurs pour un montant de {{ number_to_words($total) ." "."Francs CFA"}} </td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Reçu du collecteur des valeurs invendues pour un montant de {{ number_to_words($total_rendu) ." "."Francs CFA"}}..Francs CFA    </td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">A Agou, le 4 janvier 2023</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">A ……………….., le ……………..</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Le Collecteur</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Le Régisseur de recettes,</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">{{$data[0]["Collecteur"]}}</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">(nom et signature)</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">(nom, signature et cachet du régisseur)</td>
    </tr>

</table>



</body>
</html>
