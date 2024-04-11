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
    <title>Registre journal des avis distribués</title>
</head>
<body>

<table>
    <tr>
        <td colspan="5" style="border: none; padding: 2px;">
            {{$commune->region_name}}

        </td>
        <td colspan="4" style="border: none; padding:2px ;text-align: right;">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0; padding:2px ;">

            {{$commune->title}}
        </td>
        <td colspan="4" style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">

            Travail-Liberté-Patrie
        </td>
    </tr>
    <tr>
        <th colspan="9" style="border: none; margin: 0; text-align: center;" class="caption">Registre journal des avis
            distribués
        </th>
    </tr>
    <tr>
        <td colspan="9" style="border: none; margin: 0;">@php
                $year = \App\Models\Year::getActiveYear()
            @endphp
            Exercice : {{$year->name}}</td>
    </tr>
    <tr>
        <th>N° Avis</th>
        <th>N° OR</th>
        <th>NIC</th>
        <th>Nom ou Raison sociale</th>
        <th>N° Téléphone</th>
        <th>Date de notification</th>
        <th>Somme due</th>
        <th>Somme réduite ou annulée</th>
        <th>Cumul</th>
    </tr>
    @php
        $total_somme = 0;
    @endphp
    <tr>
        <td colspan="6" style="border: none; margin: 0; text-align: center;">REPORT</td>
        <td>{{ $total_somme}}</td>
        <td></td>
        <td>{{ $total_somme}}</td>
    </tr>

    @foreach($data as $index => $item)
        @if ($item->delivery_date != null)
            <tr>
                <td style="text-align: center">{{$item->invoice_no}}</td>
                <td style="text-align: center">{{$item->order_no}}</td>
                <td style="text-align: center">{{$item->nic}}</td>
                <td style="text-align: center">{{$item->taxpayer->name}}</td>
                <td style="text-align: center">{{$item->taxpayer->mobilephone}}</td>
                <td>
                    {{ date('Y-m-d', strtotime($item->delivery_date)) }}

                </td>
                <td style="text-align: center">
                    @if ($item->reduce_amount == '')
                        {{$item->amount}}
                        @php
                            $total_somme +=intval($item->amount)
                        @endphp
                    @else
                        0
                    @endif
                </td>
                <td style="text-align: center">@if ($item->reduce_amount != '')
                        {{$item->reduce_amount}}
                        @php
                            $total_somme +=intval($item->reduce_amount)
                        @endphp
                    @else
                        0
                    @endif</td>

                <td>{{ $total_somme}}</td>
            </tr>
        @endif

    @endforeach

    <tr>
        <td colspan="6" style="border: none; margin: 0; text-align: center;">TOTAL A REPORTER</td>
        <td>{{ $total_somme}}</td>
        <td></td>
        <td>{{ $total_somme}}</td>
    </tr>


</table>

</body>
</html>
