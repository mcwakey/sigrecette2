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

    <title>{{$titles[11]}}</title>
</head>
<body>

<table>


    <tr>
        <td colspan="5"  style="border: none; margin: 0;text-align: left">

            {{$commune->region_name}}

        </td>
        <td colspan="6"  style="border: none; margin: 0;text-align: right">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="5"  style="border: none; margin: 0;text-align: left">

            {{$commune->title}}
        </td>
        <td colspan="6"  style="border: none; margin: 0;text-align: right">

            Travail-Liberté-Patrie
        </td>
    </tr>



    <tr>
        <th colspan="11" style="border: none; margin: 0;">

            {{$titles[11]}}


        </th>


    </tr>
    <tr>
        <td colspan="11" style="border: none; margin: 0">

            N°


        </td>


    </tr>
    <tr>
        <td colspan="11" style="margin-left: 2px;text-align:left;padding:4px ">
            @php
            $year = \App\Models\Year::getActiveYear()
            @endphp
            Exercice : {{$year->name}}

        </td>
    </tr>


    <tr>
        @foreach($titles as $index => $item)
            @if($index <11)
            <th>{{$item}}</th>
            @endif
        @endforeach
    </tr>
    <tbody>
    @php
        $total_somme = 0;
    @endphp



    @foreach($data as $index => $item )

            @php
            $sumsByTaxCode = \App\Models\Invoice::sumAmountsByTaxCode($item);
            @endphp

        {{--TODO logique à refaire l'implémentation n'est pas correction doit gérer la creation des avis et les paiement associés --}}
            @if($item->status!="APROVED-CANCELLATION")
                @foreach($sumsByTaxCode as $code => &$tax)
                            <tr>
                                <td>@if($item->delivery_date !=null)
                                        {{date("d-m-Y", strtotime( $item->delivery_date  ))}}
                                    @endif</td>
                                <td>{{$item->order_no}}</td>
                                <td>{{$item->invoice_no}}</td>
                                <td>{{$item->nic}}</td>
                                <td>{{$item->taxpayer->name}}</td>
                                <td>{{$item->taxpayer->longitude,$item->taxpayer->latitude}}</td>
                                <td>{{$code}}</td>
                                @php
                                    $total_somme+=$tax['amount'];
                                    @endphp
                                <td>{{ $tax['amount']}}</td>
                                <td></td>
                                <td></td>
                                <td>{{$total_somme}}</td>

                            </tr>
            @endforeach
            @endif

        @foreach($item->payments()->get() as $payment)
                <tr>
                <td>@if($item->delivery_date !=null)
                        {{date("d-m-Y", strtotime( $item->delivery_date  ))}}
                    @endif</td>
                <td></td>
                <td>{{$item->invoice_no}}</td>
                <td>{{$item->nic}}</td>
                <td>{{$item->taxpayer->name}}</td>
                <td>{{$item->taxpayer->longitude,$item->taxpayer->latitude}}</td>
                <td>{{$payment->code}}</td>
                    <td></td>
                    <td>
                        @if( $payment->reference!=null)
                            {{ $payment->reference}}
                        @else
                        @endif
                       </td>
                    @php
                        $total_somme-=$payment->amount;
                    @endphp
                    <td>{{ $payment->amount}}</td>
                    <td>{{$total_somme}}</td>
            </tr>
        @endforeach
    @endforeach


    <tr>
        <td colspan="9" >Total </td>
        <td></td>
        <td ></td>
    </tr>

    </tbody>
</table>

</body>
</html>
