<?php

use App\Models\Abonnement;
use App\Models\Client;
use App\Models\Collecteur;
use App\Models\Declaration;
use App\Models\Planification;
use App\Models\User;
use App\Models\Zone;
use App\Notifications\AgentAffectationNotification;
use App\Notifications\CollecteurAffectationNotification;
use Illuminate\Support\Facades\Notification;

it('creates a planification when an admin validates a declaration', function () {
    $zone = Zone::factory()->create();
    $clientUser = User::factory()->create(['role' => 'client']);
    $collecteurUser = User::factory()->create(['role' => 'collecteur']);

    $declaration = Declaration::create([
        'type_dechet' => 'plastique',
        'poids_estime' => 12.5,
        'description' => 'Collecte ponctuelle',
        'statut' => 'en_attente',
        'user_id' => $clientUser->id,
    ]);

    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)
        ->post(route('declarations.valider', $declaration));

    $response->assertRedirect(route('declarations.show', $declaration));
    expect(Planification::where('declaration_id', $declaration->id)->exists())->toBeTrue();
});

it('generates planifications automatically for an active abonnement', function () {
    $zone = Zone::factory()->create();
    $clientUser = User::factory()->create(['role' => 'client']);
    Client::create([
        'user_id' => $clientUser->id,
        'zone_id' => $zone->id,
        'latitude' => '0.0',
        'longitude' => '0.0',
        'typeclient' => 'professionnel',
    ]);

    $abonnement = Abonnement::create([
        'user_id' => $clientUser->id,
        'type_abonnement' => 'Standard',
        'type_dechet' => 'papier',
        'frequence' => 'hebdomadaire',
        'jour_collecte' => 'lundi',
        'poids_estime' => 30,
        'montant' => 120.00,
        'date_debut' => now()->subWeek()->toDateString(),
        'date_fin' => now()->addWeek()->toDateString(),
        'statut' => 'actif',
    ]);

    expect($abonnement->planifications()->count())->toBeGreaterThan(0);
});

it('assigns agent and collecteur and sends notifications', function () {
    Notification::fake();

    $zone = Zone::factory()->create();
    $admin = User::factory()->create(['role' => 'admin']);
    $agent = User::factory()->create(['role' => 'agent']);
    $collecteurUser = User::factory()->create(['role' => 'collecteur']);
    $collecteur = Collecteur::create(['user_id' => $collecteurUser->id, 'zone_id' => $zone->id, 'numpermis' => 'C-123', 'matricul' => 'M-123']);

    $planification = Planification::create([
        'code_planification' => 'TEST-001',
        'nom_tournee' => 'Tournée test',
        'jour_semaine' => 'lundi',
        'date_prevue' => now()->toDateString(),
        'periode' => 'PONCTUELLE',
        'type_collecte' => 'plastique',
        'statut' => 'planifiee',
        'zone_id' => $zone->id,
        'collecteur_id' => null,
        'agent_id' => null,
        'ordre_passage' => 1,
        'duree_estimee' => 60,
        'priorite' => 1,
    ]);

    $this->actingAs($admin)->post(route('affectations.assign', $planification), [
        'agent_id' => $agent->id,
        'collecteur_id' => $collecteur->id,
        'ordre_passage' => 2,
        'duree_estimee' => 45,
        'priorite' => 2,
    ]);

    expect($planification->fresh()->statut)->toEqual('assignee');
    Notification::assertSentTo($collecteurUser, CollecteurAffectationNotification::class);
    Notification::assertSentTo($agent, AgentAffectationNotification::class);
});
