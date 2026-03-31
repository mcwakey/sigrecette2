<?php

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesPermissionsSeeder::class);
});

function createUserWithRole(string $roleName): User
{
    $user = User::factory()->create();
    $user->assignRole($roleName);
    return $user;
}

describe('Invoice Authorization', function () {

    test('any authenticated user can view invoices list', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('invoices.index'))
            ->assertStatus(Response::HTTP_OK);
    });

    test('any authenticated user can view a single invoice', function () {
        $user = User::factory()->create();
        $taxpayer = Taxpayer::create([
            'name' => 'Auth Test',
            'gender' => 'Homme',
            'id_type' => 'CNI',
            'id_number' => 'AUTH-' . uniqid(),
            'mobilephone' => '90000003',
            'address' => 'Test',
            'type' => 'TITRE',
            'password' => bcrypt('secret'),
        ]);
        $invoice = Invoice::create([
            'taxpayer_id' => $taxpayer->id,
            'invoice_no' => 'INV-AUTH-' . uniqid(),
            'amount' => 5000,
            'qty' => 1,
            'from_date' => now()->startOfYear()->toDateString(),
            'to_date' => now()->endOfYear()->toDateString(),
            'status' => 'APPROVED',
            'type' => 'TITRE',
        ]);

        $this->actingAs($user)
            ->get(route('invoices.show', $invoice))
            ->assertStatus(Response::HTTP_OK);
    });
});

describe('Taxpayer Authorization', function () {

    test('any authenticated user can view taxpayers list', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('taxpayers.index'))
            ->assertStatus(Response::HTTP_OK);
    });

    test('unauthenticated user cannot view taxpayers', function () {
        $this->get(route('taxpayers.index'))
            ->assertRedirect(route('login'));
    });
});

describe('Taxpayer Model Scopes', function () {

    test('ofType scope filters by type', function () {
        Taxpayer::create([
            'name' => 'Titre Taxpayer',
            'gender' => 'Homme',
            'id_type' => 'CNI',
            'id_number' => 'SCOPE-1',
            'mobilephone' => '90000010',
            'address' => 'Test',
            'type' => 'TITRE',
            'password' => bcrypt('secret'),
        ]);
        Taxpayer::create([
            'name' => 'Comptant Taxpayer',
            'gender' => 'Femme',
            'id_type' => 'CNI',
            'id_number' => 'SCOPE-2',
            'mobilephone' => '90000011',
            'address' => 'Test',
            'type' => 'COMPTANT',
            'password' => bcrypt('secret'),
        ]);

        expect(Taxpayer::ofType('TITRE')->count())->toBe(1);
        expect(Taxpayer::ofType('COMPTANT')->count())->toBe(1);
    });

    test('active scope excludes rejected and pending', function () {
        Taxpayer::create([
            'name' => 'Active Taxpayer',
            'gender' => 'Homme',
            'id_type' => 'CNI',
            'id_number' => 'SCOPE-3',
            'mobilephone' => '90000012',
            'address' => 'Test',
            'type' => 'TITRE',
            'from_mobile_and_validate_state' => 'APPROVED',
            'password' => bcrypt('secret'),
        ]);
        Taxpayer::create([
            'name' => 'Pending Taxpayer',
            'gender' => 'Homme',
            'id_type' => 'CNI',
            'id_number' => 'SCOPE-4',
            'mobilephone' => '90000013',
            'address' => 'Test',
            'type' => 'TITRE',
            'from_mobile_and_validate_state' => 'PENDING',
            'password' => bcrypt('secret'),
        ]);
        Taxpayer::create([
            'name' => 'No State Taxpayer',
            'gender' => 'Homme',
            'id_type' => 'CNI',
            'id_number' => 'SCOPE-5',
            'mobilephone' => '90000014',
            'address' => 'Test',
            'type' => 'TITRE',
            'from_mobile_and_validate_state' => null,
            'password' => bcrypt('secret'),
        ]);

        expect(Taxpayer::active()->count())->toBe(2);
    });
});
