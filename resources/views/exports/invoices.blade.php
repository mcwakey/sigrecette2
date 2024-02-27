<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-size: 0.75em;
        }

        .avis-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
            background-color: #ffffff;
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
        <h6>N°:<span class="write">{{$data[1]}}</span>/{{$data[0]}}</h6>
        <h6>Destinataire:<span class="write"> </span></h6>

    </div>
    <div class="sub-header">
        <p>Nom/Raison sociale :<span class="write">{{$data[4]}}</span></p>
        <p>Nic :<span class="write"> {{$data[2]}}</span></p>
        <p>Téléphone :<span class="write"> {{$data[5]}}</span></p>
        <p>Zone fiscale :<span class="write"> ……………</span></p>
        <p>Canton :<span class="write">{{$data[6]}}</span></p>
        <p>Quartier/Village :<span class="write"> {{$data[7]}}</span></p>
        <p>Adresse complète :<span class="write">{{$data[8]}}</span></p>
        <p>Coordonnées GPS :<span class="write"> ……………</span></p>
    </div>
    <div class="avis-content">
        <p>Madame, Mademoiselle, Monsieur,</p>
        <p>Vous êtes priés de bien vouloir payer à la régie des recettes de la mairie de{{$data[6]}}
            le montant ci-dessous :</p>
        <p>N° d’ordre de recette :<span class="write"> {{$data[1]}}</span>/{{$data[0]}}</span></p>
        <p>Libellé de la recette :<span class="write"> ……………</span></p>
        <p>Imputation budgétaire :<span class="write"> ……………</span></p>
        <p>Matière taxable :<span class="write"> ……………</span></p>
        <p>Unité d’assiette :<span class="write"> ……………</span></p>
        <p>Valeur d’assiette : (1):<span class="write"> ……………</span></p>
        <p>Tarif (FCFA) : (2):<span class="write"> ……………</span></p>
        <p>Nombre de taxation par an : (3):<span class="write"> ……………</span></p>
        <p>Somme due :<span class="write">{{$data[3]}}</span></p>
        <p>Arrêté le présent avis à la somme de :<span class="write">{{ number_to_words($data[3]) }} </span>. Francs CFA (Sauf erreur ou omission)</p>
        <p>A payer dans les 30 jours suivant la réception de l’avis, ou avant la fin de chaque mois pour les
            paiements mensualisés</p>
        <table>
            <tr class="text-start">
                <td class="">
                    <p>A ………, le<span class="write"> ……………</span></p>
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
