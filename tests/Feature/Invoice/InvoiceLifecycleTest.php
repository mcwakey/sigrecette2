<?php

use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

function createTestTaxpayer(): Taxpayer
{
    return Taxpayer::create([
        'name' => 'Test Contribuable',
        'gender' => 'Homme',
        'id_type' => 'CNI',
        'id_number' => 'TEST-' . uniqid(),
        'mobilephone' => '90000001',
        'address' => 'Adresse test',
        'type' => 'TITRE',
        'password' => bcrypt('secret'),
    ]);
}

function createTestInvoice(Taxpayer $taxpayer, string $status = 'DRAFT', float $amount = 10000): Invoice
{
    return Invoice::create([
        'taxpayer_id' => $taxpayer->id,
        'invoice_no' => 'INV-TEST-' . uniqid(),
        'amount' => $amount,
        'qty' => 1,
        'from_date' => now()->startOfYear()->toDateString(),
        'to_date' => now()->endOfYear()->toDateString(),
        'status' => $status,
        'type' => 'TITRE',
    ]);
}

describe('Invoice Lifecycle', function () {

    test('invoice is created with DRAFT status by default', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer);

        expect($invoice->status)->toBe(InvoiceStatusEnums::DRAFT->value);
        expect($invoice->uuid)->not()->toBeNull();
    });

    test('invoice can transition to ACCEPTED status', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer);

        $invoice->update(['status' => InvoiceStatusEnums::ACCEPTED->value]);

        expect($invoice->refresh()->status)->toBe(InvoiceStatusEnums::ACCEPTED->value);
    });

    test('invoice can transition to APPROVED status', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer, InvoiceStatusEnums::ACCEPTED->value);

        $invoice->update(['status' => InvoiceStatusEnums::APPROVED->value]);

        expect($invoice->refresh()->status)->toBe(InvoiceStatusEnums::APPROVED->value);
    });

    test('invoice belongs to a taxpayer', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer);

        expect($invoice->taxpayer)->toBeInstanceOf(Taxpayer::class);
        expect($invoice->taxpayer->id)->toBe($taxpayer->id);
    });

    test('invoice pay_status updates when fully paid', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer, InvoiceStatusEnums::APPROVED->value, 10000);

        $invoice->update(['pay_status' => InvoicePayStatusEnums::PAID->value]);

        expect($invoice->refresh()->pay_status)->toBe(InvoicePayStatusEnums::PAID->value);
    });

    test('invoice pay_status is PART_PAID when partially paid', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer, InvoiceStatusEnums::APPROVED->value, 10000);

        $invoice->update(['pay_status' => InvoicePayStatusEnums::PART_PAID->value]);

        expect($invoice->refresh()->pay_status)->toBe(InvoicePayStatusEnums::PART_PAID->value);
    });

    test('approved invoice can be canceled', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer, InvoiceStatusEnums::APPROVED->value);

        $invoice->update(['status' => InvoiceStatusEnums::CANCELED->value]);

        expect($invoice->refresh()->status)->toBe(InvoiceStatusEnums::CANCELED->value);
    });

    test('approved invoice can be reduced', function () {
        $taxpayer = createTestTaxpayer();
        $invoice = createTestInvoice($taxpayer, InvoiceStatusEnums::APPROVED->value, 10000);

        $invoice->update([
            'status' => InvoiceStatusEnums::REDUCED->value,
            'reduce_amount' => 3000,
        ]);

        $invoice->refresh();
        expect($invoice->status)->toBe(InvoiceStatusEnums::REDUCED->value);
        expect((float) $invoice->reduce_amount)->toBe(3000.0);
    });

    test('invoice scopes filter correctly', function () {
        $taxpayer = createTestTaxpayer();
        createTestInvoice($taxpayer, InvoiceStatusEnums::DRAFT->value);
        createTestInvoice($taxpayer, InvoiceStatusEnums::APPROVED->value);
        createTestInvoice($taxpayer, InvoiceStatusEnums::APPROVED->value);

        expect(Invoice::ofStatus(InvoiceStatusEnums::DRAFT->value)->count())->toBe(1);
        expect(Invoice::approved()->count())->toBe(2);
        expect(Invoice::forTaxpayer($taxpayer->id)->count())->toBe(3);
    });
});
