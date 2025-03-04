<?php

use App\Livewire\Activity\AddActivityModal;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('AddActivityModal Component', function () {

    test('it renders successfully', function () {
        Livewire::test(AddActivityModal::class)
            ->assertStatus(200);
    });

    test('it validates required fields', function () {
        Livewire::test(AddActivityModal::class)
            ->call('submit')
            ->assertHasErrors(['category_id', 'name', 'status']);
    });

    test('it creates a new activity', function () {
        $category = Category::factory()->create();

        Livewire::test(AddActivityModal::class)
            ->set('category_id', $category->id)
            ->set('name', 'Nouvelle activité')
            ->set('status', 'active')
            ->call('submit');

        expect(Activity::where('name', 'Nouvelle activité')->exists())->toBeTrue();
    });

    test('it updates an existing activity', function () {
        $category = Category::factory()->create();
        $activity = Activity::factory()->create([
            'category_id' => $category->id,
            'name' => 'Ancienne activité',
            'status' => 'inactive',
        ]);

        Livewire::test(AddActivityModal::class)
            ->call('updateActivity', $activity->id)
            ->set('name', 'Activité mise à jour')
            ->set('status', 'active')
            ->call('submit');

        $activity->refresh();

        expect($activity->name)->toBe('Activité mise à jour');
        expect($activity->status)->toBe('active');
    });

    test('it deletes an activity', function () {
        $activity = Activity::factory()->create();

        Livewire::test(AddActivityModal::class)
            ->call('deleteUser', $activity->id);

        expect(Activity::find($activity->id))->toBeNull();
    });

});
