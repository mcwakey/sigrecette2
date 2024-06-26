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
            Nom du collecteur : {{$user->name}}

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
    $total=0;
$total_rendu=0;
$total_vendu=0;
    foreach ($data as $item){

        if($item->trans_type=="RECU"){
             $total+=($item->taxable->tariff*$item->rc_qty);
        }elseif($item->trans_type=="VENDU"){
            $total_vendu+=($item->taxable->tariff*$item->vv_qty);
        }
        elseif($item->trans_type=="RENDU"){
            $total_rendu+=($item->taxable->tariff*$item->rd_qty);
        }
    }
    @endphp
    @foreach($data as $index => $item)

        @if($item->type=="ACTIVE")
            <tr>
                <td>{{ $item->taxable->name }}</td>
                <td>{{$item->taxable->tariff}}</td>
                <td>
                    @if($item->trans_type=="RECU")
                        {{$item->start_no }}</td>
                    @endif

                <td>
                    @if($item->trans_type=="RECU")
                        {{$item->end_no }}
                    @endif
                </td>

                </td>
                <td>
                    @if($item->trans_type=="RECU")
                    {{$item->rc_qty}}
                    @endif
                </td>
                <td>
                    @if($item->trans_type=="RECU")
                    {{ $item->rc_qty*$item->taxable->tariff}}
                    @endif
                </td>

                <td>
                    @if($item->trans_type=="VENDU")
                        {{$item->start_no }}
                    @endif
                </td></td>
                <td>
                    @if($item->trans_type=="VENDU")
                        {{$item->end_no }}
                    @endif
                </td></td>
                <td>
                    @if($item->trans_type=="VENDU")
                    {{$item->vv_qty}}
                    @endif
                </td>
                <td>
                    @if($item->trans_type=="VENDU")

                    {{ $item->vv_qty*$item->taxable->tariff}}
                    @endif
                </td>
                <td>
                    @if($item->trans_type==="RENDU")

                        {{$item->start_no }}
                    @endif
                </td>


                <td>
                    @if($item->trans_type==="RENDU")
                    {{$item->end_no }}
                    @endif
                </td>

                <td>
                    @if($item->trans_type==="RENDU")
                        {{$item->rd_qty}}
                    @endif
                </td>
                <td>
                    @if($item->trans_type==="RENDU")
                        {{ $item->rd_qty*$item->taxable->tariff}}
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
        <td colspan="7" style="border: none; margin: 0;text-align: left"> A {{$commune->name}}, le {{now()->format('d M Y')}}</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">A ……………….., le ……………..</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Le Collecteur</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left">Le Régisseur de recettes,</td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left">{{$user->name}}</td>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
    </tr>
    <tr>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
        <td colspan="7" style="border: none; margin: 0;text-align: left"></td>
    </tr>

</table>



</body>
</html>
