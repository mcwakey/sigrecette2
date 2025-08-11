<?php

use Symfony\Component\HttpFoundation\Response;

describe('Authentication Features', function (): void {
    test('Authenticated Investor should be redirect to Dashboard if trying to login again', function (): void {
        $this->actingAs($this->user, 'web')
             ->get(route('login'))
             ->assertStatus(Response::HTTP_FOUND)
             ->assertRedirect(route('dashboard'));

        $this->actingAs($this->user, 'web')
             ->post(route('login'))
             ->assertStatus(Response::HTTP_FOUND)
             ->assertRedirect(route('dashboard'));
    });
});
