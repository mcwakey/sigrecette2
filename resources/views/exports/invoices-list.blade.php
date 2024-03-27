<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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

            {{$titles[15]}}


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


    @foreach($data as $index => $item)
        @php
            $contribuable = $item[__('taxpayer')];
            $lines = explode("\n", $contribuable);
            $name=$lines[1];
            $derniereLigne = end($lines);
            $numeroTelephone = trim($derniereLigne);
            $total_somme +=intval($item[__('amount')]);
            $year = date("Y", strtotime( $item[ __('from_date')])) ;
            switch ($item[__('aproval')]) {
                case "PRIS EN CHARGE":
                    $item[__('aproval')] = "PC";
                    break;
                case "REJETÉ":
                    $item[__('aproval')] = "RJ";
                    break;
                default:
                    $item[__('aproval')] = "";
                    break;
            }
        @endphp
        <tr>
            <td>{{$item[__('invoice no')]}}</td>
            <td>{{date("d-m-Y", strtotime( $item[ __('from_date')]))}}</td>
            <td>{{$item[__('order no')]}}</td>
            <td>{{$item[__('nic')]}}</td>
            <td>{{$name}}</td>
            <td>{{$numeroTelephone}}</td>
            <td>{{$item[__('zone')]}}</td>
            <td>{{$item[__('address')]}}</td>
            <td>{{$item[__('gps')]}}</td>
            <td>{{$item[__('amount')]}}</td>
            <td>{{$item[__('aproval')]}}</td>
        </tr>
    @endforeach


    <tr>
        <td colspan="9" >{{$titles[11]}} </td>
        <td>{{ $total_somme}}</td>
        <td rowspan="3"></td>
    </tr>
    <tr>
        <td colspan="9" >{{$titles[12]}} </td>
        <td></td>

    </tr>
    <tr>
        <td colspan="9">{{$titles[13]}}</td>
        <td>{{ $total_somme}} </td>

    </tr>
    <tr>
        <td colspan="11">{{$titles[14]}} : <span>{{number_to_words($total_somme) }}</span> </td>

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
        <td colspan="6" style="border: none; margin: 0;padding: 0;">[Cachet, signature, Nom et prénoms]</td>
    </tr>
    </tbody>
</table>

</body>
</html>
