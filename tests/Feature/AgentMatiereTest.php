<?php

use App\Models\User;

beforeEach(function () {
    $this->agent = User::factory()->create([
        'role' => 'agent',
        'email_verified_at' => now(),
    ]);
});

test('agent can view matieres show page', function () {
    $response = $this
        ->actingAs($this->agent)
        ->get(route('agent.matieres.show', ['matiere' => 'Plastique']));

    $response
        ->assertOk()
        ->assertSee('Matière première')
        ->assertSee('Plastique')
        ->assertSee('Quantité totale triée');
});
