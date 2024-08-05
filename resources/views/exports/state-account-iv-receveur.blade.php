@php
$stock_r = \App\Models\StockRequest::find($data[0]);
 $vv_qty =  0;
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
    <title>ETAT DE COMPTABILITÉ DES VALEURS INACTIVES DU REGISSEUR </title>
</head>
<body>

<table>
    <tr>
    <td colspan="1"  style="border: none; margin: 0;text-align: left">

        @if($commune->getImageUrlAttribute()!=null)
            <img src="{{$commune->getImageUrlAttribute()}}" alt="Logo" style="width: 50px; height: 50px;">
        @else
            <img src="{{public_path('assets/media/images_exports/image3.jpg')}}" alt="Logo" style="width: 50px; height: 50px;">
        @endif
    </td>
    <td colspan="4"  style="border: none; margin: 0;text-align: left">

        {{$commune->region_name}}

    </td>
    <td colspan="5"  style="border: none; margin: 0;text-align: right">
        REPUBLIQUE TOGOLAISE

    </td>
    </tr>
    <tr>
        <td colspan="1" style="border: none; margin: 0; padding:2px ;text-align: left;">
        </td>
        <td colspan="4"  style="border: none; margin: 0;text-align: left">

            {{$commune->title}}
        </td>
        <td colspan="5"  style="border: none; margin: 0;text-align: right">

            Travail-Liberté-Patrie
        </td>
    </tr>

    <tr>
        <td colspan="10" style="border: none; margin: 0;text-align: center">
            N°001
        </td>

    </tr>
    <tr>
        <td colspan="10" style="border: none; margin: 0;text-align: left">
            Nom du Régisseur : {{\App\Models\User::getRegisseurName()}}
        </td>

    </tr>
    <tr>
        <td colspan="10" style="border: none; margin: 0;text-align: left">
            Période de recouvrement :
        </td>

    </tr>
    <tr>
        <td colspan="10" style="border: none; margin: 0;text-align: left">
            Catégorie de valeur inactive: {{$stock_r->taxable->name}}
        </td>

    </tr>
    <tr>
        <td colspan="10" style="border: none; margin: 0;text-align: left">
            Valeur faciale :  {{$stock_r->taxable->tariff}}</td>

    </tr>
    <tr>
        <th rowspan="2">Date</th>
        <th rowspan="2">Pièces justificatives</th>
        <th colspan="2">Numéro</th>
        <th  colspan="2">Valeurs prises en  charge</th>
        <th  colspan="2">Valeurs vendues</th>
        <th  colspan="2">Solde des valeurs en stock</th>
    </tr>
    <tr>

        <th>Début</th>
        <th>Fin</th>
        <th>Nombre</th>
        <th>Montant</th>
        <th>Nombre</th>
        <th>Montant</th>
        <th>Nombre</th>
        <th>Montant</th>

    </tr>
    <tr>

        <th colspan="4" style="border: none; margin: 0;text-align: center">
            Report
        </th>
        <th ></th>
        <th ></th>
        <th></th>
        <th ></th>
        <th></th>
        <th ></th>


    </tr>
    <tr>
        <td>{{$stock_r->created_at->format('d M Y')}}</td>
        <td>{{"Demande d’approvisionnement N°".$stock_r->first()->req_no}}</td>
        <td>{{$stock_r->start_no}}</td>
        <td>{{$stock_r->end_no}}</td>
        <td>{{$stock_r->qty}}</td>
        <td>{{$stock_r->qty* $stock_r->taxable->tariff}}</td>
        <td></td>
        <td></td>
        <td>{{$stock_r->qty}}</td>
        <td>{{$stock_r->qty* $stock_r->taxable->tariff}}</td>
    </tr>
    @if($stock_r->req_type==\App\Helpers\Constants::DEMANDE)

        @else
        <tr>
            <td>{{$stock_r->created_at->format('d M Y')}}</td>
            <td>{{"Etat de comptabilité des VI N°".$stock_r->req_no}}</td>
            <td>{{$stock_r->start_no}}</td>
            <td>{{$stock_r->end_no}}</td>
            <td></td>
            <td></td>
            @php
                if (!$stock_r->pc_qty || !$stock_r->sd_qty) {
                       $vv_qty =  0;
                   } else {
                       $vv_qty = $stock_r->pc_qty - $stock_r->sd_qty;

                   }
                   $vv_total = ($stock_r->pc_qty - $stock_r->sd_qty) * $stock_r->taxable->tariff;

            @endphp
            <td>{{$vv_qty}}</td>
            <td>{{intval($vv_qty)* $stock_r->taxable->tariff}}</td>
            <td>{{$vv_qty}}</td>
            <td>{{$vv_total}}</td>
        </tr>
    @endif

    <tr>
        <td colspan="4" >Totaux et solde à reporter</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;text-align: left">
            Prise en charge par le Régisseur des valeurs inactives   pour un montant de   {{number_to_words($stock_r->qty* $stock_r->taxable->tariff)}} FCFA</td>
        <td colspan="5" style="border: none; margin: 0;text-align: left">
            Arrêté le montant des valeurs vendues à {{number_to_words(intval($vv_qty)* $stock_r->taxable->tariff)}}
            et le montant des valeurs en stock à .. {{number_to_words(isset($vv_total)?$vv_total:0)}}
        </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;text-align: left">
            A {{$commune->name}}, {{now()->format('d M Y')}}
        </td>
        <td colspan="5" style="border: none; margin: 0;text-align: left">
            A ……………….., le ……………..
        </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;text-align: center">
            Le Régisseur
        </td>
        <td colspan="5" style="border: none; margin: 0;text-align: center">
            Le Régisseur
        </td>
    </tr>

</table>




</body>
</html>
