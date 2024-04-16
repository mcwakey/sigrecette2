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
    <title>Fiche du contribuable</title>
</head>
<body>


<table>
    <caption>REGION PLATEAUX - Commune Agou - REPUBLIQUE TOGOLAISE</caption>
    <tr>
        <td>Travail-Liberté-Patrie</td>
    </tr>
    <tr>
        <td>FICHE DU CONTRIBUABLE</td>
    </tr>
    <tr>
        <td>NIC : 00012</td>
    </tr>
    <tr>
        <td>Nom / Raison sociale : Jacqueline</td>
    </tr>
    <tr>
        <td>N° Téléphone : 91..</td>
    </tr>
    <tr>
        <td>Zone fiscale : Zone 1</td>
    </tr>
    <tr>
        <td>Adresse complète : XX YY ZZ</td>
    </tr>
    <tr>
        <td>Coordonnées GPS : XY</td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Motif</th>
        <th>Montant émission</th>
        <th>Montant annulation / réduction</th>
        <th>Cumul exigible</th>
        <th>Montant recouvré</th>
        <th>Cumul recouvré</th>
        <th>Reste à recouvrer</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data[1] as  $item)


        @if($item instanceof \App\Models\Invoice )
            @if($item->delivery_date!=null)
                @foreach(\App\Models\Invoice::sumAmountsByTaxCode($item) as $code => $tax)
                    <tr>
                        <td>{{$item->delivery_date}}</td>
                        <td>Distribution Avis {{$item->invoice_no}}, OR {{$item->order_no}}, redevance occupation domaine 2023</td>
                        @if($item->reduce_amount != '')
                            <td></td>
                            <td>{{$item->reduce_amount}}</td>
                        @else
                            <td>{{$tax['amount']}}</td>
                            <td></td>
                        @endif
                        <td>120 000</td>
                        <td></td>
                        <td>120 000</td>
                        <td></td>
                    </tr>
                @endforeach

            @endif
        @else
            <tr>
                <td>{{$item->created_at}}</td>
                <td>Recouvrement Avis {{$item->invoice->invoice_no}}, OR {{$item->reference}},redevance occupation domaine 2023</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $item->amount }}</td>
                <td>120 000</td>
                <td></td>
            </tr>
        @endif

    @endforeach


    </tbody>
</table>

</body>
</html>
