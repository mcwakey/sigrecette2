<?php

namespace Tests\Fixtures\TestCases;
use App\Models\User;
use Database\Seeders\GendersSeeder;
use Database\Seeders\IdTypesSeeder;
use Database\Seeders\RolesPermissionsSeeder;
use Database\Seeders\TaxLabelsSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTestCase extends TestCase
{
    use RefreshDatabase;


    public User $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TaxLabelsSeeder::class);
        $this->seed(RolesPermissionsSeeder::class);
        $this->seed(GendersSeeder::class);
        $this->seed(GendersSeeder::class);
        $this->seed(IdTypesSeeder::class);



        $this->user = User::factory()->create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // After each test ...
    }
}
