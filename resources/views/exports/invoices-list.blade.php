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
            padding: 5px;
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
        <td colspan="5"  style="border: none; margin: 0">
            REGION PLATEAUX

        </td>
        <td colspan="6"  style="border: none; margin: 0">
            REPUBLIQUE TOGOLAISE

        </td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0">

            Commune de Agou
        </td>
        <td colspan="6"  style="border: none; margin: 0">

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
            Exercice : 2023

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
            $contribuable = $item[0];
            $lines = explode("\n", $contribuable);
            $name=$lines[1];
            $derniereLigne = end($lines);
            $numeroTelephone = trim($derniereLigne);
            $total_somme +=intval($item[7]);
            $explose_year = explode(" ", $item[9]);
            $year = end($explose_year) ;

        @endphp
        <tr>
            <td>{{$item[3]}}</td>
            <td>{{$year}}</td>
            <td>{{$item[1]}}</td>
            <td>{{$item[3]}}</td>
            <td>{{$name}}</td>
            <td>{{$numeroTelephone}}</td>
            <td>{{$item[4]}}</td>
            <td>{{$item[5]}}</td>
            <td>{{$item[6]}}</td>
            <td>{{$item[7]}}</td>
            <td></td>
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
        <td colspan="5" style="border: none; margin: 0;padding: 5px;"> A <span> Agou</span> le <span>23 janvier 2023</span> </td>
        <td colspan="6" style="border: none; margin: 0;padding: 0;">Le Maire</td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; margin: 0;padding: 0;"></td>
        <td colspan="6" style="border: none; margin: 0;padding: 0;">[Cachet, signature, Nom et prénoms]</td>
    </tr>
    </tbody>
</table>

</body>
</html>
