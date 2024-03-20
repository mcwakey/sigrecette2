<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }

        body{
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
            background-color: #ffffff;
            margin-bottom: 20px;

        }
        h4 {
            margin: 0;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .margin-top {
            margin-top: 1.25rem;
        }
        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241 245 249);
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.875rem;
        }
        table.products tr {
            background-color: rgb(96 165 250);
        }
        table.products th {
            color: #ffffff;
            padding: 0.5rem;
        }
        table tr.items {
            background-color: rgb(241 245 249);
        }
        table tr.items td {
            padding: 0.5rem;
        }
        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
<table class="w-full">
    <tr>
        <td class="w-half">
            <img src="../images/image3.jpg" alt="laravel daily" width="50" />
        </td>
        <td class="w-half">
            <div>
                <div>
                    <h6>
                        Invoice ID: 834847473
                    </h6>
                    <h6>
                        N° d'avis: 834847473
                    </h6>
                </div>

            </div>

        </td>
    </tr>
</table>

<div class="margin-top">
    <table class="w-full">
        <tr>
            <td class="w-half">
                <div><h4>To:</h4></div>
                <div>John Doe</div>
                <div>123 Acme Str.</div>
            </td>
            <td class="w-half">
                <div><h4>From:</h4></div>
                <div>Laravel Daily</div>
                <div>London</div>
            </td>
        </tr>
    </table>
</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th>Reference N°</th>
            <th>Amount paid</th>
            <th>Payment Type</th>
            <th>Payment restant</th>
            <th>Total pament</th>
        </tr>
        <tr class="items">

            @foreach($data as $item)
                <td>
                    {{ $item['name'] }}
                </td>
                <td>
                    {{ $item['quantity'] }}
                </td>
                <td>
                    {{ $item['description'] }}
                </td>
                <td>
                    {{ $item['description'] }}
                </td>
                <td>
                    {{ $item['price'] }}
                </td>
            @endforeach
        </tr>
    </table>
</div>

<div class="total">
    Total: $129.00 USD
</div>
<p>N.B. Le paiement peut être effectué en numéraire, par chèque au nom du Receveur de la Commune de <span class="write"> ………………………………</span>. ou
    par virement au compte trésor RIB<span class="write"> ………………………………</span>. La quittance est délivrée à la réception des espèces, du
    chèque ou de l’ordre de virement par le Régisseur de recettes.</p>
<div class="footer margin-top">
    <div>Thank you</div>
    <div>&copy; Laravel Daily</div>
</div>
</body>
</html>
