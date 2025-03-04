<?php


use App\Models\Commune;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can retrieve the first commune', function () {
    // Création de quelques communes
    $commune1 = Commune::factory()->create(['name' => 'Commune A']);
    $commune2 = Commune::factory()->create(['name' => 'Commune B']);

    // Vérification que getFirstCommune retourne la première
    $firstCommune = Commune::getFirstCommune();
    expect($firstCommune->id)->toBe($commune1->id);
});

it('returns the correct image URL', function () {
    $commune = Commune::factory()->create([
        'logo_path' => 'logos/test.png',
        'sign_path' => 'signs/test.png',
    ]);

    expect($commune->getImageUrlAttribute('logo'))->toBe('storage/logos/test.png');
    expect($commune->getImageUrlAttribute('sign'))->toBe('storage/signs/test.png');
});
