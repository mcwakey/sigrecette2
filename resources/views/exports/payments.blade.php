<!doctype html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Quittance de paiement</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h4 { margin: 0; }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: top;
            padding: 2px;
        }

        .header-center { text-align: center; }
        .header-right { text-align: right; }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
            text-decoration: underline;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            width: 140px;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .payments-table th {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 4px 6px;
            text-align: left;
            font-size: 10px;
            border: 1px solid #2c3e50;
        }

        .payments-table td {
            padding: 4px 6px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        .payments-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-right { text-align: right; }

        .total-row td {
            font-weight: bold;
            background-color: #eee;
        }

        .footer-note {
            font-size: 9px;
            margin-top: 8px;
            line-height: 1.4;
        }

        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            padding-top: 25px;
            vertical-align: bottom;
        }
    </style>
</head>
<body>
    @php
        $firstPayment = $payments->first();
        $invoice = $firstPayment->invoice;
        $taxpayer = $firstPayment->taxpayer;
        $totalPaid = $payments->sum('amount');
    @endphp

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 60px;">
                @if($commune->getImageUrlAttribute() != null)
                    <img src="{{ $commune->getImageUrlAttribute() }}" alt="Logo" style="width: 50px; height: 50px;">
                @endif
            </td>
            <td class="header-center">
                <strong>COMMUNE DE {{ strtoupper($commune->name ?? '') }}</strong><br>
                {{ $commune->address ?? '' }}
            </td>
            <td class="header-right" style="width: 160px;">
                <strong>Date :</strong> {{ now()->format('d/m/Y') }}<br>
                <strong>N° Quittance :</strong> {{ $firstPayment->reference ?? $firstPayment->id }}
            </td>
        </tr>
    </table>

    <div class="title">QUITTANCE DE PAIEMENT</div>

    {{-- Taxpayer & Invoice Info --}}
    <table class="info-table">
        <tr>
            <td class="info-label">Contribuable :</td>
            <td>{{ $taxpayer->name ?? '-' }}</td>
            <td class="info-label">N° Avis :</td>
            <td>{{ $invoice->invoice_no ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Adresse :</td>
            <td>{{ $taxpayer->address ?? '-' }}</td>
            <td class="info-label">Type :</td>
            <td>{{ $invoice->type ?? '-' }}</td>
        </tr>
    </table>

    {{-- Payment Details Table --}}
    <table class="payments-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Libelle taxe</th>
                <th>Type paiement</th>
                <th class="text-right">Montant paye</th>
                <th class="text-right">Reste a payer</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->reference ?? '-' }}</td>
                    <td>{{ $payment->tax_label->name ?? $payment->code ?? '-' }}</td>
                    <td>{{ $payment->payment_type ?? '-' }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($payment->remaining_amount ?? 0, 0, ',', ' ') }}</td>
                    <td>{{ $payment->created_at?->format('d/m/Y') ?? '-' }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">Total paye :</td>
                <td class="text-right">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <p class="footer-note">
        N.B. Le paiement peut etre effectue en numeraire, par cheque au nom du Receveur de la Commune
        de {{ $commune->name ?? '' }}, ou par virement au compte tresor.
        La quittance est delivree a la reception des especes, du cheque ou de l'ordre de virement par le Regisseur de recettes.
    </p>

    <table class="signature-table">
        <tr>
            <td>
                Le Contribuable<br><br><br>
                _________________________
            </td>
            <td>
                Le Receveur<br><br><br>
                _________________________
            </td>
        </tr>
    </table>
</body>
</html>
