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

    <title>{{$titles[15]}}</title>
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
        <td colspan="6"  style="border: none; margin: 0;text-align: right">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="1" style="border: none; margin: 0; padding:2px ;text-align: left;">
        </td>
        <td colspan="4"  style="border: none; margin: 0;text-align: left">

            {{$commune->title}}
        </td>
        <td colspan="6"  style="border: none; margin: 0;text-align: right">

            Travail-Liberté-Patrie
        </td>
    </tr>



    <tr>
        <th colspan="11" style="border: none; margin: 0;">

            {{$titles[15]}}


        </th>


    </tr>
    <tr>
        <td colspan="11" style="border: none; margin: 0">

            N°{{$print->last_sequence_number}}


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


    @foreach($data as $index => $item)
        @php
            if($action==2) {
            $total_somme +=intval($item->reduce_amount);
            }
            else{
                         $total_somme +=intval($item->amount);
                    }

                $year = date("Y", strtotime( $item[ __('from_date')])) ;
                switch ($item->status) {
                    case App\Enums\InvoiceStatusEnums::APPROVED:
                    case App\Enums\InvoiceStatusEnums::APPROVED_CANCELLATION:
                         case App\Enums\InvoiceStatusEnums::REDUCED:
                        case App\Enums\InvoiceStatusEnums::CANCELED:
                        $item->status = "PC";
                        break;
                    case "REJECTED":
                        $item->status = "REJETÉ";
                        break;
                    default:
                        $item->status = "";
                        break;
                }
        @endphp
        <tr>
            <td>{{$item->invoice_no}}</td>
            <td>@if(isset($item->from_date))
                    {{
    date(
        "d-m-Y", strtotime( $item->from_date )
    )
    }}
                @endif</td>
            <td>{{$item->order_no}}</td>
            <td>{{$item->nic}}</td>
            <td>{{$item->taxpayer?->name}}</td>
            <td>{{$item->taxpayer?->mobilephone}}</td>
            <td>{{$item->taxpayer?->zone?->name}}</td>
            <td>{{$item->taxpayer?->town?->canton->name."-".$item->taxpayer?->town?->name."-".$item->taxpayer?->address}}</td>
            <td>{{$item->taxpayer?->longitude,$item->taxpayer?->latitude}}</td>
            <td>
                @if($action==2)
                    {{format_amount($item->reduce_amount)}}
                @else
                    {{format_amount($item->amount)}}
                @endif

            </td>
            <td>{{$item->status}}</td>
        </tr>
    @endforeach


    <tr>
        <td colspan="9" >{{$titles[11]}} </td>
        <td>{{ $total_somme}}</td>
        <td rowspan="3"></td>
    </tr>
    <tr>
        <td colspan="9" >{{$titles[12]}} </td>
        @if($print->last_sequence_number!=1)
        <td>{{$print->total_last_sequence}}</td>
        @else
            <td>0</td>
        @endif

    </tr>
    <tr>
        <td colspan="9">{{$titles[13]}}</td>
        <td>{{ $total_somme}} </td>

    </tr>
    <tr>
        <td colspan="5"></td>
        <td colspan="6">{{$titles[14]}} : <span>{{number_to_words($total_somme) }}</span> </td>

    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 5;padding: 5;"> </td>
        <td colspan="6" style="border: none; margin: 5;padding: 5;"></td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0; padding: 5px;">
            A <span> {{$commune->title}}</span> le <span>{{ now()->locale('fr')->format('d-m-Y') }}</span>
        </td>


        <td colspan="6" style="border: none; margin: 0;padding: 0;">Le Maire</td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 5;padding: 5;"> </td>
        <td colspan="6" style="border: none; margin: 5;padding: 5;"> {{$commune->mayor_name}} </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 5;padding: 5;"> </td>
        <td colspan="6" style="border: none; margin: 5;padding: 5;"></td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;padding: 0;"></td>
        <td colspan="6" style="border: none; margin: 0;padding: 0;">[Cachet, signature, Nom et prénoms de l’ordonnateur]</td>
    </tr>
    </tbody>
</table>

</body>
</html>
