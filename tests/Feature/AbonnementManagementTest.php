<?php

use App\Models\Client;
use App\Models\User;

it('shows the abonnement create form for admin with client list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $client = Client::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->get(route('abonnements.create'));

    $response->assertStatus(200);
    $response->assertSee($client->user->name);
});

it('creates an abonnement with the required fields', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('abonnements.store'), [
            'type_abonnement' => 'Mensuel',
            'type_dechet' => 'Plastique',
            'frequence' => 'hebdomadaire',
            'jour_collecte' => 'lundi',
            'poids_estime' => 12,
            'montant' => 50000,
            'date_debut' => now()->toDateString(),
            'date_fin' => now()->addMonth()->toDateString(),
            'statut' => 'en_attente',
        ]);

    $response->assertRedirect(route('abonnements.index'));

    $this->assertDatabaseHas('abonnements', [
        'user_id' => $user->id,
        'type_abonnement' => 'Mensuel',
        'type_dechet' => 'Plastique',
        'frequence' => 'hebdomadaire',
        'jour_collecte' => 'lundi',
        'montant' => 50000,
        'statut' => 'en_attente',
    ]);
});
