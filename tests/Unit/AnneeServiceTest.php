<?php

namespace Tests\Unit;

use App\Models\AnneeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnneeServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_annee_service()
    {
        $anneeServiceData = [
            'annee' => 2023,
        ];

        $anneeService = AnneeService::create($anneeServiceData);

        $this->assertInstanceOf(AnneeService::class, $anneeService);
        $this->assertEquals(2023, $anneeService->annee);
        // Ajoutez d'autres assertions au besoin
    }
}
