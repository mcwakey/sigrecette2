<?php

namespace Tests\Unit;

use App\Models\AnneeService;
use App\Models\Year;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AnneeServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_year()
    {
        DB::beginTransaction();
        $anneeServiceData = [
            'name' => '2023',

        ];

        $anneeService = Year::create($anneeServiceData);

        $this->assertInstanceOf(Year::class, $anneeService);
        $this->assertEquals(2023, $anneeService->name);
        DB::rollBack();
    }
}
