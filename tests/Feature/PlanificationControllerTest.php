<?php

use App\Models\Abonnement;
use App\Models\Collecteur;
use App\Models\Declaration;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

it('redirects to the planifications index after storing a planification', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->withoutMiddleware(VerifyCsrfToken::class);
    $zone = Zone::factory()->create();
    $collecteurUser = User::factory()->create(['role' => 'collecteur']);
    $collecteur = Collecteur::create([
        'user_id' => $collecteurUser->id,
        'zone_id' => $zone->id,
        'numpermis' => 'C-123',
        'matricul' => 'M-123',
    ]);
    $clientUser = User::factory()->create(['role' => 'client']);
    $declaration = Declaration::create([
        'type_dechet' => 'plastique',
        'poids_estime' => 10,
        'description' => 'Collecte test',
        'statut' => 'en_attente',
        'user_id' => $clientUser->id,
    ]);
    $abonnement = Abonnement::create([
        'user_id' => $clientUser->id,
        'type_abonnement' => 'Standard',
        'type_dechet' => 'plastique',
        'frequence' => 'hebdomadaire',
        'jour_collecte' => 'lundi',
        'poids_estime' => 20,
        'montant' => 50.00,
        'date_debut' => now()->toDateString(),
        'date_fin' => now()->addWeek()->toDateString(),
        'statut' => 'actif',
    ]);

    $response = $this->actingAs($admin)->post(route('planifications.store'), [
        'code_planification' => 'TEST-123',
        'nom_tournee' => 'Centre ville',
        'jour_semaine' => 'mardi',
        'date_prevue' => now()->addDay()->toDateString(),
        'periode' => 'HEBDOMADAIRE',
        'type_collecte' => 'plastique',
        'statut' => 'planifiee',
        'zone_id' => $zone->id,
        'collecteur_id' => $collecteur->id,
        'declaration_id' => $declaration->id,
        'abonnement_id' => $abonnement->id,
        'agent_id' => $admin->id,
        'ordre_passage' => 1,
        'duree_estimee' => 60,
        'priorite' => 1,
    ]);

    $response->assertRedirect(route('planifications.index'));
    $this->assertDatabaseHas('planifications', ['code_planification' => 'TEST-123']);
});
