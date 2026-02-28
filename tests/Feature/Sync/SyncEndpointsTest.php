<?php

use App\Enums\InvoicePayStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use App\Models\User;
use App\Services\Sync\PaymentImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, RefreshDatabase::class);

function createTaxpayer(): Taxpayer
{
    return Taxpayer::create([
        'tnif' => null,
        'name' => 'Test Taxpayer',
        'gender' => 'Homme',
        'id_type' => 'CNI',
        'id_number' => '123456',
        'mobilephone' => '90000000',
        'telephone' => null,
        'longitude' => null,
        'latitude' => null,
        'address' => 'Test',
        'password' => bcrypt('secret'),
    ]);
}

test('sync invoices requires auth', function () {
    config(['sync.allowed_ips' => '127.0.0.1']);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->getJson('/api/v1/sync/invoices')
        ->assertStatus(401);
});

test('sync invoices blocks non whitelisted ip', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    config(['sync.allowed_ips' => '10.0.0.1']);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->getJson('/api/v1/sync/invoices')
        ->assertStatus(403);
});

test('sync invoices returns approved valid invoices for active year', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    config(['sync.allowed_ips' => '127.0.0.1']);

    $taxpayer = createTaxpayer();

    $invoice = Invoice::create([
        'taxpayer_id' => $taxpayer->id,
        'invoice_no' => 'INV-TEST-1',
        'amount' => 100,
        'qty' => 1,
        'from_date' => now()->startOfYear()->toDateString(),
        'to_date' => now()->endOfYear()->toDateString(),
        'status' => 'APPROVED',
        'validity' => 'VALID',
    ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->getJson('/api/v1/sync/invoices?include=taxpayer,items')
        ->assertOk()
        ->assertJsonPath('data.0.uuid', $invoice->uuid)
        ->assertJsonPath('data.0.taxpayer.id', $taxpayer->id);
});

test('sync payments import creates payment and updates invoice pay_status', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    config(['sync.allowed_ips' => '127.0.0.1']);

    $taxpayer = createTaxpayer();

    $invoice = Invoice::create([
        'taxpayer_id' => $taxpayer->id,
        'invoice_no' => 'INV-TEST-2',
        'amount' => 100,
        'qty' => 1,
        'from_date' => now()->startOfYear()->toDateString(),
        'to_date' => now()->endOfYear()->toDateString(),
        'status' => 'APPROVED',
        'validity' => 'VALID',
    ]);

    $payload = [
        'payments' => [
            [
                'uuid' => 'pay-uuid-1',
                'invoice_uuid' => $invoice->uuid,
                'taxpayer_id' => $taxpayer->id,
                'amount' => 60,
                'payment_type' => PaymentTypeEnums::DIGI,
                'invoice_type' => 'TITRE',
                'reference' => 'REF-1',
                'description' => 'Paiement test',
                'remaining_amount' => 40,
                'status' => PaymentStatusEnums::DONE,
                'deposit' => null,
                'notes' => 'test',
            ],
        ],
    ];

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->postJson('/api/v1/sync/payments', $payload)
        ->assertOk()
        ->assertJsonPath('created', 1);

    $payment = Payment::where('uuid', 'pay-uuid-1')->first();
    expect($payment)->not()->toBeNull();

    $invoice->refresh();
    expect($invoice->pay_status)->toBe(InvoicePayStatusEnums::PART_PAID);
});

test('payment import service updates existing payment by uuid', function () {
    $taxpayer = createTaxpayer();

    $invoice = Invoice::create([
        'taxpayer_id' => $taxpayer->id,
        'invoice_no' => 'INV-TEST-3',
        'amount' => 100,
        'qty' => 1,
        'from_date' => now()->startOfYear()->toDateString(),
        'to_date' => now()->endOfYear()->toDateString(),
        'status' => 'APPROVED',
        'validity' => 'VALID',
    ]);

    Payment::create([
        'uuid' => 'pay-uuid-2',
        'invoice_id' => $invoice->id,
        'taxpayer_id' => $taxpayer->id,
        'amount' => 20,
        'payment_type' => PaymentTypeEnums::CASH,
        'status' => PaymentStatusEnums::PENDING,
    ]);

    $service = new PaymentImportService();
    $result = $service->import([
        [
            'uuid' => 'pay-uuid-2',
            'invoice_uuid' => $invoice->uuid,
            'taxpayer_id' => $taxpayer->id,
            'amount' => 100,
            'payment_type' => PaymentTypeEnums::CASH,
            'invoice_type' => 'TITRE',
            'reference' => null,
            'description' => null,
            'remaining_amount' => 0,
            'status' => PaymentStatusEnums::DONE,
            'deposit' => null,
            'notes' => null,
        ],
    ]);

    expect($result['updated'])->toBe(1);

    $payment = Payment::where('uuid', 'pay-uuid-2')->first();
    expect($payment->amount)->toBe(100.0);

    $invoice->refresh();
    expect($invoice->pay_status)->toBe(InvoicePayStatusEnums::PAID);
});
