<?php

use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

function createPaymentTaxpayer(): Taxpayer
{
    return Taxpayer::create([
        'name' => 'Payment Test Contribuable',
        'gender' => 'Homme',
        'id_type' => 'CNI',
        'id_number' => 'PAY-' . uniqid(),
        'mobilephone' => '90000002',
        'address' => 'Adresse paiement',
        'type' => 'TITRE',
        'password' => bcrypt('secret'),
    ]);
}

function createApprovedInvoice(Taxpayer $taxpayer, float $amount = 10000): Invoice
{
    return Invoice::create([
        'taxpayer_id' => $taxpayer->id,
        'invoice_no' => 'INV-PAY-' . uniqid(),
        'amount' => $amount,
        'qty' => 1,
        'from_date' => now()->startOfYear()->toDateString(),
        'to_date' => now()->endOfYear()->toDateString(),
        'status' => InvoiceStatusEnums::APPROVED->value,
        'type' => 'TITRE',
    ]);
}

describe('Payment Processing', function () {

    test('payment can be created for an invoice', function () {
        $taxpayer = createPaymentTaxpayer();
        $invoice = createApprovedInvoice($taxpayer);

        $payment = Payment::create([
            'amount' => 5000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        expect($payment)->toBeInstanceOf(Payment::class);
        expect($payment->uuid)->not()->toBeNull();
        expect((float) $payment->amount)->toBe(5000.0);
    });

    test('payment belongs to invoice and taxpayer', function () {
        $taxpayer = createPaymentTaxpayer();
        $invoice = createApprovedInvoice($taxpayer);

        $payment = Payment::create([
            'amount' => 5000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        expect($payment->invoice)->toBeInstanceOf(Invoice::class);
        expect($payment->taxpayer)->toBeInstanceOf(Taxpayer::class);
    });

    test('multiple payments can be made on the same invoice', function () {
        $taxpayer = createPaymentTaxpayer();
        $invoice = createApprovedInvoice($taxpayer, 10000);

        Payment::create([
            'amount' => 3000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        Payment::create([
            'amount' => 4000,
            'payment_type' => PaymentTypeEnums::CHEQUE->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        expect($invoice->payments()->count())->toBe(2);
        expect((float) $invoice->payments()->sum('amount'))->toBe(7000.0);
    });

    test('payment uuid is auto-generated', function () {
        $taxpayer = createPaymentTaxpayer();
        $invoice = createApprovedInvoice($taxpayer);

        $payment = Payment::create([
            'amount' => 1000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::PENDING->value,
        ]);

        expect($payment->uuid)->not()->toBeNull();
        expect(strlen($payment->uuid))->toBe(36);
    });

    test('payment supports different payment types', function () {
        $taxpayer = createPaymentTaxpayer();
        $invoice = createApprovedInvoice($taxpayer);

        $cashPayment = Payment::create([
            'amount' => 1000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        $digiPayment = Payment::create([
            'amount' => 2000,
            'payment_type' => PaymentTypeEnums::DIGI->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        expect($cashPayment->payment_type)->toBe(PaymentTypeEnums::CASH->value);
        expect($digiPayment->payment_type)->toBe(PaymentTypeEnums::DIGI->value);
    });

    test('canceled payment does not count toward invoice total', function () {
        $taxpayer = createPaymentTaxpayer();
        $invoice = createApprovedInvoice($taxpayer, 10000);

        Payment::create([
            'amount' => 5000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::ACCOUNTED->value,
        ]);

        Payment::create([
            'amount' => 3000,
            'payment_type' => PaymentTypeEnums::CASH->value,
            'invoice_type' => 'TITRE',
            'taxpayer_id' => $taxpayer->id,
            'invoice_id' => $invoice->id,
            'status' => PaymentStatusEnums::CANCELED->value,
        ]);

        $accountedTotal = $invoice->payments()
            ->where('status', PaymentStatusEnums::ACCOUNTED->value)
            ->sum('amount');

        expect((float) $accountedTotal)->toBe(5000.0);
    });
});
