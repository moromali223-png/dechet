<?php

use App\Models\Abonnement;
use App\Models\Client;
use App\Models\Collectes;
use App\Models\Planification;
use App\Models\User;
use App\Models\Zone;

it('resolves the client through planification and abonnement on collecte', function () {
    $user = User::factory()->create();
    $zone = Zone::factory()->create();

    $client = Client::factory()->create([
        'user_id' => $user->id,
        'zone_id' => $zone->id,
    ]);

    $abonnement = Abonnement::create([
        'user_id' => $user->id,
        'type_abonnement' => 'standard',
        'type_dechet' => 'plastique',
        'frequence' => 'hebdomadaire',
        'jour_collecte' => 'lundi',
        'poids_estime' => 10.00,
        'montant' => 25.00,
        'date_debut' => today(),
        'date_fin' => today()->addMonth(),
        'statut' => 'actif',
        'rue' => 'Rue de Test',
        'quartier' => 'Quartier Test',
        'porte' => '1',
    ]);

    $planification = Planification::create([
        'code_planification' => 'TEST-001',
        'nom_tournee' => 'Tournée test',
        'jour_semaine' => 'lundi',
        'date_prevue' => today()->toDateString(),
        'periode' => 'HEBDOMADAIRE',
        'type_collecte' => 'SYSTEMATIQUE',
        'statut' => 'planifiee',
        'zone_id' => $zone->id,
        'collecteur_id' => null,
        'abonnement_id' => $abonnement->id,
        'agent_id' => null,
        'ordre_passage' => 1,
        'duree_estimee' => 60,
        'priorite' => 1,
    ]);

    $collecte = Collectes::create([
        'planification_id' => $planification->id,
        'photo' => null,
        'commentaire' => 'Test',
        'statut' => 'en_cours',
        'heure_depart' => null,
        'heure_fin' => null,
    ]);

    $collecte = Collectes::with('planification.abonnement.client')->findOrFail($collecte->id);

    expect($collecte->client)->not->toBeNull();
    expect($collecte->client->id)->toBe($client->id);
    expect($collecte->planification->client->id)->toBe($client->id);
    expect($collecte->client->user->id)->toBe($user->id);
});
