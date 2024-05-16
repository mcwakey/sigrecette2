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
    <tr>
        <td colspan="1"  style="border: none; margin: 0;text-align: left">

            <img src="{{ $commune->getImageUrlAttribute() }}" alt="Logo" style="width: 50px; height: 50px;">

        </td>
        <td colspan="3"  style="border: none; padding: 2px;">
            {{$commune->region_name}}

        </td>
        <td colspan="4"  style="border: none; padding:2px ;text-align: right;">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="1" style="border: none; margin: 0; padding:2px ;text-align: left;">
        </td>
        <td colspan="3" style="border: none; margin: 0; padding:2px ;">

            {{$commune->title}}
        </td>
        <td colspan="4"  style="border: none; margin: 0 ; padding:2px ; ;text-align: right;">

            Travail-Liberté-Patrie
        </td>
    </tr>
    <tr>
        <th colspan="8" style="border: none; margin: 0; text-align: center;" class="caption">FICHE DU CONTRIBUABLE</th>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">NIC :  {{$data[0]->nif}} </td>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">Nom / Raison sociale : {{$data[0]->name}}   </td>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">N° Téléphone :{{$data[0]->mobilephone}}  </td>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">Zone fiscale : {{$data[0]->zone->name}}</td>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">Adresse complète : {{$data[0]->address}}</td>
    </tr>
    <tr>
        <td colspan="8" style="border: none; margin: 0; text-align: center;">Coordonnées GPS : {{$data[0]->longitude,$data[0]->latitude}}</td>
    </tr>
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

    <tbody>
    @php
    $cumul_plus=0;
$cumul_recouvré=0;
        @endphp
    @foreach($data[1] as  $item)


        @if($item instanceof \App\Models\Invoice )
            @if($item->delivery_date!=null && $item->status!= App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION)
                @foreach(\App\Models\Invoice::sumAmountsByTaxCode($item) as $code => $tax)
                    <tr>
                        <td>{{$item->delivery_date}}</td>
                        <td>Distribution Avis {{$item->invoice_no}}, OR {{$item->order_no}}, {{$tax['name']}}</td>
                        @php
                            $cumul_plus+=$tax['amount'];
                        @endphp
                        <td>{{$tax['amount']}}</td>
                        <td></td>
                        <td>{{$cumul_plus-0}}</td>
                        <td></td>
                        <td></td>
                        <td>{{$cumul_plus-$cumul_recouvré}}</td>
                    </tr>
                @endforeach

            @endif
        @else
            @if($item->reference!=\App\Helpers\Constants::REDUCTION && $item->reference!=\App\Helpers\Constants::ANNULATION )
                <tr>
                    <td>{{$item->created_at}}</td>
                    <td>Recouvrement Avis {{$item->invoice->invoice_no}}, numéro de quittance {{$item->reference}},{{\App\Models\TaxLabel::getNameByCode($item->code)}}</td>

                    <td></td>
                    <td></td>
                    <td></td>
                    @php
                        $cumul_recouvré+=$item->amount;
                        @endphp
                    <td>{{ $item->amount }}</td>
                    <td>{{ $cumul_recouvré}}</td>
                    <td>{{$cumul_plus-$cumul_recouvré}}</td>
                </tr>
                @else
                    @if($item->invoice->delivery_date!=null)
                    <tr>
                        <td>{{$item->invoice->delivery_date}}</td>
                        <td>Avis de réduction ou d’annulation {{$item->invoice->invoice_no}}, OR {{$item->invoice->order_no}}, {{\App\Models\TaxLabel::getNameByCode($item->code)}}</td>
                        <td></td>

                        @php
                            $cumul_plus-=$item->amount;
                            //$cumul_recouvré+=$item->amount;
                        @endphp
                        <td>{{$item->amount}}</td>
                        <td>{{$cumul_plus}}</td>
                        <td></td>
                        <td></td>
                        <td>{{$cumul_plus-$cumul_recouvré}}</td>
                    </tr>

                    @endif
            @endif

        @endif

    @endforeach


    </tbody>
</table>

</body>
</html>
