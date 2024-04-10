<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;

            margin: 0;
            padding: 0;
            font-size: 0.70em;
        }

        .avis-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;

        }

        .avis-header {
            text-align: center;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .avis-content {
            line-height: 1.6;
        }

        .sub-header {

            line-height: 1;
        }

        .boder-div-red {
            border-color: #c00000;
        }

        .boder-div-blaw {
            border-color: #0070c0;
        }

        .boder-div-red,
        .boder-div-blaw {
            border-style: solid solid solid solid;
            position: relative;
            max-height: 100px;
            padding-left: 10px;
        }

        .boder-div-blaw .img-fluid,
        .boder-div-red .img-fluid {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -10px;
        }

        .boder-div-blaw .details,
        .boder-div-red .details {
            margin-top: 10px;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }


        .img-thumbnail {
            width: 71px;
            height: 70px;
            object-fit: cover;

        }

        .details {
            margin-top: 10px;
        }

        .details h6 {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .text-start {
            text-align: start;
        }


        .center-image {
            text-align: center;
        }

        .write {

        }

        .count_page {
            display: flex;
            justify-content: end;
            align-items: end;


        }
    </style>
</head>

<body>


<div class="container avis-container">
    <table>
        <tr class="text-start">
            <td class="boder-div-blaw">
                <img src="{{public_path('assets/media/images_exports/image1.png')}}" class="img-fluid" alt="...">
                <div class="details">
                    <h6><span class="write"> {{$commune->title}}</span></h6>
                    <h6>TEL :<span class="write"> {{$commune->phone_number}}</span></h6>
                    <h6>Adresse :<span class="write">{{$commune->address}}</span></h6>
                </div>
            </td>
            <td class="center-image">
                <img src="{{public_path('assets/media/images_exports/image3.jpg')}}" alt="..." class="img-thumbnail">
            </td>
            <td class="boder-div-red">
                <img src="{{public_path('assets/media/images_exports/image2.png')}}" class="img-fluid" alt="...">
                <div class="details">
                    <h6>TRESORERIE DE : <span class="write"> {{$commune->address}}</span></h6>
                    <h6>Adresse :<span class="write"> {{$commune->name}}</span></h6>
                </div>
            </td>
        </tr>
    </table>

    <div class="avis-header">


        <h2 class="text-center">

            <span>@if($action===1) AVIS DES SOMMES À PAYER @else AVIS DE REDUCTION OU D’ANNULATION @endif </span>

        </h2>
        <h6>N°:<span class="write">{{$data->invoice_no}}/</span>{{date("Y", strtotime($data->from_date))}}</h6>


        <h4>Destinataire:<span class="write"> </span></h4>

    </div>
    <div class="sub-header">
        <p>
            <span>Nom/Raison sociale: {{$data->taxpayer->name}},</span>
            <span>Téléphone :{{$data->taxpayer->mobilephone}}</span>
        </p>
        <p>
            <span class="write">Canton :{{$data->taxpayer->town->canton->name}},</span>
            <span class="write">Quartier/Village: {{$data->taxpayer->town->name}},</span>
            <span class="write"> Zone fiscale :{{$data->taxpayer->zone->name}}</span>
        </p>
        <p>
            <span class="write">Adresse complète :{{$data->taxpayer->address}}</span>
            <span class="write"> Coordonnées GPS :{{$data->taxpayer->longitude}} , {{$data->taxpayer->latitude}}</span>
        </p>
        <p>Nic :<span class="write"> {{$data->nic}}</span></p>
    </div>
    <div class="avis-content">
        <p>Madame, Mademoiselle, Monsieur,</p>
        @if($action==1)
            <p>Vous êtes priés de bien vouloir payer à la régie des recettes de la mairie {{$commune->name}}
                le montant ci-dessous :</p>
        @else
            Votre avis des sommes à payer {{$data->invoice_no}}/{{date("Y", strtotime($data->from_date))}}
            du  {{date("d/m/Y", strtotime( $data->from_date))}} est réduit suivant les détails ci-après :
        @endif
        <p>N° d’ordre de recette :<span
                class="write">{{$data->order_no}}/</span> {{date("Y", strtotime( $data->from_date))}}</p>


        <table border="1">

            <tr>
                <th>Matière taxable</th>
                <th>Nom de la Taxation</th>
                <th>Unité d’assiette</th>
                <th>Valeur d’assiette</th>
                <th>Tarif (FCFA)</th>
                <th>Nombre de taxation par an</th>
                <th>Somme due</th>
            </tr>


            @if($action==2)
                <tr>
                    <th colspan="7">Tableau de l’ancien décompte</th>
                </tr>
                @php
                    $last_code=0;
                @endphp
                @foreach($invoice->invoiceitems as  $item)
                    @if($last_code!==$item->taxpayer_taxable->taxable->tax_label->code|| $last_code==0 )
                        @php
                            $last_code= $item->taxpayer_taxable->taxable->tax_label->code;
                        @endphp
                        <tr>
                            <th colspan="3">Libellé de la
                                recette:{{$item->taxpayer_taxable->taxable->tax_label->name}} </th>
                            <th colspan="4">Imputation budgétaire
                                : {{$item->taxpayer_taxable->taxable->tax_label->code}}</th>
                        </tr>

                    @endif
                    <tr>
                        <td style="text-align: center">{{$item->taxpayer_taxable->taxable->name}}</td>
                        <td style="text-align: center">{{$item->taxpayer_taxable->name}}</td>
                        <td style="text-align: center"> {{$item->taxpayer_taxable->taxable->unit}}</td>
                        <td style="text-align: center">{{$item->ii_seize}}</td>
                        <td style="text-align: center">{{$item->ii_tariff}}</td>
                        <td style="text-align: center">{{$item->qty}}</td>
                        <td style="text-align: center">{{$item->amount}}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="6" style="text-align: right;">Total:</th>
                    <td style="text-align: center;">{{$invoice->amount}}</td>
                </tr>
                <tr>
                    <th colspan="7">Tableau du nouveau décompte</th>
                </tr>
            @endif
            @php
                $last_code=0;
            @endphp
            @foreach($data->invoiceitems as $index => $item)
                @if($last_code!==$item->taxpayer_taxable->taxable->tax_label->code|| $last_code==0 )
                    @php
                        $last_code= $item->taxpayer_taxable->taxable->tax_label->code;
                    @endphp
                    <tr>
                        <th colspan="3">Libellé de la
                            recette:{{$item->taxpayer_taxable->taxable->tax_label->name}} </th>
                        <th colspan="4">Imputation budgétaire
                            : {{$item->taxpayer_taxable->taxable->tax_label->code}}</th>
                    </tr>

                @endif
                <tr>
                    <td style="text-align: center">{{$item->taxpayer_taxable->taxable->name}}</td>
                    <td style="text-align: center">{{$item->taxpayer_taxable->name}}</td>
                    <td style="text-align: center"> {{$item->taxpayer_taxable->taxable->unit}}</td>
                    <td style="text-align: center">{{$item->ii_seize}}</td>
                    <td style="text-align: center">{{$item->ii_tariff}}</td>
                    <td style="text-align: center">{{$item->qty}}</td>
                    <td style="text-align: center">{{$item->amount}}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="6" style="text-align: right;"><strong>Total :</strong></td>

                <td style="text-align: center;">{{$data->amount}}</td>


            </tr>


        </table>
        <p>Arrêté le présent avis à la somme de :<span
                class="write">@if($action==1){{number_to_words($data->amount) }}@else {{number_to_words($invoice->amount -$data->amount) }}  @endif</span>
            Francs CFA (Sauf erreur ou omission).</p>
        @if($action==1)
            <p>A payer dans les 30 jours suivant la réception de l’avis, ou avant la fin de chaque mois pour les
                paiements mensualisés.</p>
        @endif
        <table>
            <tr class="text-start">
                <td class="">
                    <p>A {{$commune->name}}, le<span
                            class="write"> {{date("d/m/Y", strtotime( $data->from_date))}}</span></p>
                </td>
                <td class="">
                    <p>Le Maire <span>{{ " ".$commune->mayor_name}}</span></p>
                </td>
            </tr>
        </table>
        @if($action==1)
            <p>N.B. Le paiement peut être effectué en numéraire, par chèque au nom du Receveur de la <span
                    class="write"> {{$commune->title}}</span>. ou
                par virement au compte trésor RIB<span class="write">{{" ".$commune->treasury_rib}}</span>. La quittance
                est délivrée à la réception des espèces, du
                chèque ou de l’ordre de virement par le Régisseur de recettes.</p>
        @endif
    </div>
</div>


</body>

</html>
