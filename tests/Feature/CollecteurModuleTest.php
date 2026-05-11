<?php

use App\Models\Collectes;
use App\Models\Collecteur;
use App\Models\Planification;
use App\Models\User;
use App\Models\Zone;

beforeEach(function () {
    // Use French locale or default, but code does not depend on locale-specific formatting.
});

test('collecteur can view tournées du jour', function () {
    $user = User::factory()->create([
        'role' => 'collecteur',
        'email_verified_at' => now(),
    ]);

    $zone = Zone::factory()->create();

    $collecteur = Collecteur::create([
        'user_id' => $user->id,
        'zone_id' => $zone->id,
        'numpermis' => 'TEST-001',
        'matricul' => 'MAT-001',
    ]);

    $planification = Planification::create([
        'code_planification' => 'COL-001',
        'nom_tournee' => 'Tournée test',
        'jour_semaine' => 'lundi',
        'date_prevue' => today()->toDateString(),
        'periode' => 'HEBDOMADAIRE',
        'type_collecte' => 'SYSTEMATIQUE',
        'statut' => 'assignee',
        'zone_id' => $zone->id,
        'collecteur_id' => $collecteur->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('collecteur.tournees'));

    file_put_contents(storage_path('app/debug-collecteur.html'), $response->getContent());

    $response->assertOk();
    $response->assertSeeText('Mes tournées du jour');
    $response->assertSeeText('Tournée test');
    $response->assertSeeText('Démarrer');
});

test('collecteur can terminer une collecte et créer un enregistrement', function () {
    $user = User::factory()->create([
        'role' => 'collecteur',
        'email_verified_at' => now(),
    ]);

    $zone = Zone::factory()->create();

    $collecteur = Collecteur::create([
        'user_id' => $user->id,
        'zone_id' => $zone->id,
        'numpermis' => 'TEST-002',
        'matricul' => 'MAT-002',
    ]);

    $planification = Planification::create([
        'code_planification' => 'COL-002',
        'nom_tournee' => 'Tournée collecte',
        'jour_semaine' => 'mardi',
        'date_prevue' => today()->toDateString(),
        'periode' => 'HEBDOMADAIRE',
        'type_collecte' => 'SYSTEMATIQUE',
        'statut' => 'en_cours',
        'zone_id' => $zone->id,
        'collecteur_id' => $collecteur->id,
    ]);

    $response = $this->actingAs($user)
        ->post(route('collecteur.finish', $planification));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect(Collectes::where('planification_id', $planification->id)->exists())->toBeTrue();
    expect($planification->fresh()->statut)->toBe('terminee');
});
