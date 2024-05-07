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

        p {
            margin-top: 10px;
        }
    </style>
    <title>ETAT DE COMPTABILITÉ DES VALEURS INACTIVES DU REGISSEUR</title>
</head>
<body>

@php
    $data = \App\Models\StockRequest::find($data[0]);
    if ($data == null) {
        $data = \App\Models\StockTransfer::find($data[0]);
        $taxable = \App\Models\Taxable::find($data->taxable_id);
    } else {
        $taxable = \App\Models\Taxable::find($data->taxable_id);
    }
@endphp

<table>
    <tr>
        <td colspan="5" style="border: none; margin: 0;text-align: left">{{$commune->region_name}}</td>
        <td colspan="5" style="border: none; margin: 0;text-align: left">REPUBLIQUE TOGOLAISE</td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;text-align: right">{{$commune->title}}</td>
        <td colspan="5" style="border: none; margin: 0;text-align: right">Travail-Liberté-Patrie</td>
    </tr>
    <tr>
        <td colspan="10" style="border: none; margin: 0;">ETAT DE COMPTABILITÉ DES VALEURS INACTIVES DU REGISSEUR</td>
    </tr>
    <tr>
        <td colspan="10" style="border: none; margin: 0">{{"N".$data->req_no}}</td>
    </tr>
    <tr>
        <td colspan="10">Nom du Régisseur : Kwassi</td>
    </tr>
    <tr>
        <td colspan="10">Période de recouvrement : 04 au 31 janvier 2023</td>
    </tr>
    <tr>
        <td colspan="10">Catégorie de valeur inactive: {{$taxable->name}}</td>
    </tr>
    <tr>
        <td colspan="10">Valeur faciale : {{ $taxable->unit }} {{ $taxable->tariff }}</td>
    </tr>
    <tr>
        <th rowspan="2">Date</th>
        <th rowspan="2">Pièces justificatives</th>
        <th colspan="2">Numéro</th>
        <th colspan="2">Valeurs prises en charge</th>
        <th colspan="2">Valeurs vendues</th>
        <th colspan="2">Solde des valeurs en stock</th>
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
        <th colspan="4">Report</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    @if($data instanceof App\Models\StockRequest)
        <tr>
            <td>{{ $data->created_at }}</td>
            <td>{{ $data->req_desc }}</td>
            <td>{{ $data->start_no }}</td>
            <td>{{ $data->end_no }}</td>
            <td>{{ $data->qty }}</td>
            <td>{{ $taxable->tariff * $data->qty }}</td>
            <td></td>
            <td></td>
            <td>{{ $data->qty }}</td>
            <td>{{ $taxable->tariff * $data->qty }}</td>
        </tr>
    @endif
    <tr>
        <th colspan="4">Totaux et solde à reporter</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td colspan="5">Prise en charge par le Régisseur des valeurs inactives pour un montant de {{ number_to_words($taxable->tariff * $data->qty) }} FCFA</td>
        <td colspan="5">Arrêté le montant des valeurs vendues à … et le montant des valeurs en stock à …</td>
    </tr>
    <tr>
        <td colspan="5">A Agou, le 4 janvier 2023</td>
        <td colspan="5">A …, le …</td>
    </tr>
    <tr>
        <td colspan="5">Le Régisseur</td>
        <td colspan="5">Le Régisseur</td>
    </tr>
</table>

</body>
</html>
