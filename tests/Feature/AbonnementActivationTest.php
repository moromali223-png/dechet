<?php

use App\Models\Abonnement;
use App\Models\Client;
use App\Models\Planification;
use App\Models\User;
use App\Models\Zone;
use App\Notifications\AbonnementRejectedNotification;
use Illuminate\Support\Facades\Notification;

it('activates an abonnement and generates planifications', function () {
    Notification::fake();

    $zone = Zone::factory()->create();
    $clientUser = User::factory()->create(['role' => 'client']);
    $client = Client::create([
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
        'date_debut' => now()->toDateString(),
        'date_fin' => now()->addWeeks(4)->toDateString(),
        'statut' => 'en_attente',
    ]);

    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->patch(route('abonnements.activer', $abonnement));

    $abonnement->refresh();
    expect($abonnement->statut)->toEqual('actif');
    expect(Planification::where('abonnement_id', $abonnement->id)->count())->toBeGreaterThan(0);
});

it('rejects an abonnement and notifies the client', function () {
    Notification::fake();

    $zone = Zone::factory()->create();
    $clientUser = User::factory()->create(['role' => 'client']);
    $client = Client::create([
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
        'date_debut' => now()->toDateString(),
        'date_fin' => now()->addWeeks(4)->toDateString(),
        'statut' => 'en_attente',
    ]);

    $admin = User::factory()->create(['role' => 'admin']);

    $this->withoutExceptionHandling()->actingAs($admin)->patch(route('abonnements.rejeter', $abonnement), [
        'motif_rejet' => 'Capacité insuffisante',
    ]);

    $abonnement->refresh();
    expect($abonnement->statut)->toEqual('rejete');
    expect($abonnement->motif_rejet)->toEqual('Capacité insuffisante');
    Notification::assertSentTo($clientUser, AbonnementRejectedNotification::class);
});

it('sets abonnement status to en_attente on creation', function () {
    $zone = Zone::factory()->create();
    $clientUser = User::factory()->create(['role' => 'client']);
    $client = Client::create([
        'user_id' => $clientUser->id,
        'zone_id' => $zone->id,
        'latitude' => '0.0',
        'longitude' => '0.0',
        'typeclient' => 'professionnel',
    ]);

    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('abonnements.store'), [
        'client_id' => $client->id,
        'type_abonnement' => 'Premium',
        'type_dechet' => 'plastique',
        'frequence' => 'mensuelle',
        'jour_collecte' => '15',
        'poids_estime' => 50,
        'montant' => 200.00,
        'date_debut' => now()->toDateString(),
        'date_fin' => now()->addMonths(6)->toDateString(),
    ]);

    $abonnement = Abonnement::latest()->first();
    expect($abonnement->statut)->toEqual('en_attente');
});
