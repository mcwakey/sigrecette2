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
            font-size: 0.75em;
        }

        .avis-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;

        }

        .avis-header {
            text-align: center;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .avis-content {
            line-height: 1.6;
        }

        .sub-header {
            margin-left: 90px;
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
        .write{
            color: #0070c0;
        }
        .count_page{
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
                        <h6>COMMUNE :<span class="write"> {{$data[6]}}</span></h6>
                        <h6>TEL :<span class="write"> ……………</span></h6>
                        <h6>Adresse :<span class="write"> ……………</span></h6>
                    </div>
                </td>
                <td class="center-image">
                    <img src="{{public_path('assets/media/images_exports/image3.jpg')}}" alt="..." class="img-thumbnail">
                </td>
                <td class="boder-div-red">
                    <img src="{{public_path('assets/media/images_exports/image2.png')}}" class="img-fluid" alt="...">
                    <div class="details">
                        <h6>TRESORERIE DE : <span class="write"> {{$data[6]}}</span></h6>
                        <h6>Adresse :<span class="write"> ……………</span></h6>
                    </div>
                </td>
            </tr>
        </table>

        <div class="avis-header">
            <h2 class="text-center">AVIS DES SOMMES À PAYER</h2>
            <h6>N°:<span class="write">{{$data[1]}}/</span>{{date("Y", strtotime($data[0]))}}</h6>
            <h4>Destinataire:<span class="write"> </span></h4>

        </div>
        <div class="sub-header">
            <p>Nom/Raison sociale :<span class="write">{{$data[4]}}</span></p>
            <p>Nic :<span class="write"> {{$data[2]}}</span></p>
            <p>Téléphone :<span class="write"> {{$data[5]}}</span></p>
            <p>Zone fiscale :<span class="write"> {{$data[9]}}</span></p>
            <p>Canton :<span class="write">{{$data[6]}}</span></p>
            <p>Quartier/Village :<span class="write"> {{$data[7]}}</span></p>
            <p>Adresse complète :<span class="write">{{$data[8]}}</span></p>
            <p>Coordonnées GPS :<span class="write"> {{$data[10]}} , {{$data[11]}}</span></p>
        </div>
        <div class="avis-content">
            <p>Madame, Mademoiselle, Monsieur,</p>
            <p>Vous êtes priés de bien vouloir payer à la régie des recettes de la mairie de {{$data[6]}}
                le montant ci-dessous :</p>
            <p>N° d’ordre de recette :<span class="write">{{$data[1]}}/</span> {{date("Y", strtotime( $data[0]))}}</p>

            <p>Libellé de la recette :<span class="write"> {{$data[12][0][0]}}</span></p>
            <p>Imputation budgétaire :<span class="write"> {{$data[12][0][1]}}</span></p>

            <table border="1">
                <thead>
                <tr>
                    <th>Nom de la Taxation</th>
                    <th>Matière taxable</th>
                    <th>Unité d’assiette</th>
                    <th>Valeur d’assiette</th>
                    <th>Tarif (FCFA)</th>
                    <th>Nombre de taxation par an</th>
                    <th>Somme due</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data[12] as $index => $item)
                <tr>
                    <td style="text-align: center">{{$item[8]}}</td>
                    <td style="text-align: center">{{$item[2]}}</td>
                    <td style="text-align: center"> {{$item[4]}}</td>
                    <td style="text-align: center">{{$item[3]}}</td>
                    <td style="text-align: center">{{$item[5]}}</td>
                    <td style="text-align: center">{{$item[7]}}</td>
                    <td style="text-align: center">{{$item[6]}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Total :</strong></td>
                    <td>{{$data[3]}}</td>
                </tr>
                </tbody>
            </table>
            <p>Arrêté le présent avis à la somme de :<span class="write">{{number_to_words($data[3]) }} </span> Francs CFA (Sauf erreur ou omission).</p>
            <p>A payer dans les 30 jours suivant la réception de l’avis, ou avant la fin de chaque mois pour les
                paiements mensualisés.</p>
            <table>
                <tr class="text-start">
                    <td class="">
                        <p>A ………, le<span class="write"> {{date("d/m/Y", strtotime( $data[0]))}}</span></p>
                    </td>
                    <td class="">
                        <p>Le Maire</p>
                    </td>
                </tr>
            </table>
            <p>N.B. Le paiement peut être effectué en numéraire, par chèque au nom du Receveur de la Commune de <span class="write"> ………………………………</span>. ou
                par virement au compte trésor RIB<span class="write"> ………………………………</span>. La quittance est délivrée à la réception des espèces, du
                chèque ou de l’ordre de virement par le Régisseur de recettes.</p>
        </div>
    </div>


</body>

</html>
