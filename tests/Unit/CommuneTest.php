<?php

namespace Tests\Unit;

use App\Models\Commune;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommuneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_only_one_commune_record()
    {
        $communeData = [
            'mayor_name' => 'Honore Kodjo',
            'mayor_phone_number' => '90001124',
            'mayor_address' => 'Kopeme',
            'treasury_phone_number' => '987-654-3210',
            'treasury_name' => 'Agou',
            'treasury_address' => 'Agou',
            'treasury_rib' => '123456789',
        ];

        Commune::create($communeData);

        $this->assertEquals(1, Commune::count());

        $commune = Commune::first();
        $this->assertInstanceOf(Commune::class, $commune);
        $this->assertEquals('Honore Kodjo', $commune->mayor_name);
    }
}
