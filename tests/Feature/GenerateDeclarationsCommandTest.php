<?php

use App\Models\Abonnement;
use App\Models\Declaration;
use App\Models\User;
use Illuminate\Support\Carbon;

it('generates scheduled declarations from active subscriptions', function () {
    $user = User::factory()->create();

    $today = Carbon::parse('2026-05-04');
    Carbon::setTestNow($today);

    $abonnement = Abonnement::create([
        'user_id' => $user->id,
        'type_abonnement' => 'Plastique',
        'frequence' => 'mensuelle',
        'jour_collecte' => '4',
        'poids_estime' => 12.50,
        'montant' => 0,
        'date_debut' => $today->subDays(7)->toDateString(),
        'date_fin' => $today->addMonth()->toDateString(),
        'statut' => 'actif',
    ]);

    $this->artisan('abonnements:generate-declarations')
        ->expectsOutput('1 déclarations générées pour la journée.')
        ->assertSuccessful();

    $declaration = Declaration::where('abonnement_id', $abonnement->id)->first();

    expect($declaration)->not()->toBeNull();
    expect($declaration->type_dechet)->toBe('Plastique');
    expect($declaration->user_id)->toBe($user->id);
    expect($declaration->abonnement_id)->toBe($abonnement->id);
});
