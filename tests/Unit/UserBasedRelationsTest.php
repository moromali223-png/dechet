<?php

use App\Models\Abonnement;
use App\Models\Commande;
use App\Models\Planification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('commande can resolve its client through the user relationship', function () {
    $user = User::factory()->create();

    $commande = Commande::create([
        'code_commande' => 'CMD-1001',
        'produit' => 'Plastique',
        'quantite' => 10,
        'statut' => 'en_attente',
        'client_id' => $user->id,
        'date_commande' => now()->toDateString(),
    ]);

    expect($commande->client()->exists())->toBeTrue();
    expect($commande->client()->first()->id)->toBe($user->id);
});

test('abonnement and planification can use the user-based relationship', function () {
    $user = User::factory()->create();

    $abonnement = Abonnement::create([
        'user_id' => $user->id,
        'type_abonnement' => 'mensuelle',
        'type_dechet' => 'plastique',
        'frequence' => 'mensuelle',
        'jour_collecte' => 'lundi',
        'poids_estime' => 10,
        'montant' => 100,
        'date_debut' => now()->toDateString(),
        'date_fin' => now()->addMonth()->toDateString(),
        'statut' => 'actif',
    ]);

    $planification = Planification::create([
        'abonnement_id' => $abonnement->id,
        'date_prevue' => now()->toDateString(),
        'statut' => 'planifiee',
    ]);

    expect($abonnement->user()->first()->id)->toBe($user->id);
    expect($planification->abonnement()->first()->id)->toBe($abonnement->id);
});
