<?php

use App\Http\Controllers\AbonnementsController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\Agent\CollecteController;
use App\Http\Controllers\Agent\MatiereController;
use App\Http\Controllers\Agent\StockController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\AlertesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Collecteur\CollecteController as CollecteurCollecteController;
use App\Http\Controllers\Collecteur\HistoriqueController as CollecteurHistoriqueController;
use App\Http\Controllers\Collecteur\TourneeController as CollecteurTourneeController;
use App\Http\Controllers\Collecteur\ZoneController as CollecteurZoneController;
use App\Http\Controllers\CollecteurController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PesageController;
use App\Http\Controllers\PlanificationController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\StockEntreeController;
use App\Http\Controllers\SuiviCollecteController;
use App\Http\Controllers\TourneeController;
use App\Http\Controllers\TrieController;
use App\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal accessible à tous les rôles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboards spécifiques (URL directes si besoin)
    Route::middleware('agent')->get('/dashboard/agent', [DashboardController::class, 'agent'])->name('dashboard.agent');
    Route::middleware('collecteur')->get('/dashboard/collecteur', [DashboardController::class, 'collecteur'])->name('dashboard.collecteur');
    Route::middleware('client')->get('/dashboard/client', [DashboardController::class, 'client'])->name('dashboard.client');
    Route::middleware(['auth'])->group(function () {

        // === ADMIN ===
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/declarations', [DeclarationController::class, 'adminIndex'])
                ->name('declarations.index');

            Route::post('/declarations/{declaration}/valider', [DeclarationController::class, 'valider'])
                ->name('declarations.valider');

            Route::post('/declarations/{declaration}/rejeter', [DeclarationController::class, 'rejeter'])
                ->name('declarations.rejeter');
        });

        // === PLANIFICATIONS ===
        Route::resource('planifications', PlanificationController::class)
            ->only(['index', 'edit', 'update']);
    });
    // Gestion des Zones
    Route::resource('zones', ZoneController::class);
    // Gestion des Collecteurs
    Route::resource('collecteurs', CollecteurController::class);
    // Gestion des Agents
    Route::resource('agents', AgentsController::class);
    // Gestion des Clients
    Route::resource('clients', ClientController::class);
    // Gestion des Produits
    Route::resource('produits', ProduitController::class);
    // Gestion de l'Inventaire
    Route::resource('inventaire', InventaireController::class);
    // Gestion des Entrées de Stock
    Route::resource('stock-entree', StockEntreeController::class)->parameters([
        'stock-entree' => 'stockEntree',
    ]);
    // Gestion des Mouvements de Stock
    Route::resource('mouvements', MouvementController::class);
    // Gestion des Alertes de Stock
    Route::get('alertes', [AlertesController::class, 'index'])->name('alertes.index');
    Route::get('alertes/rapport', [AlertesController::class, 'rapport'])->name('alertes.rapport');
    Route::post('alertes/{alerte}/traiter', [AlertesController::class, 'marquerTraitee'])->name('alertes.traiter');
    // Gestion des Planifications
    Route::resource('planifications', PlanificationController::class);
    Route::middleware('admin')->group(function () {
        Route::get('affectations', [AffectationController::class, 'index'])->name('affectations.index');
        Route::post('affectations/{planification}/assign', [AffectationController::class, 'assign'])->name('affectations.assign');
        Route::post('declarations/{declaration}/valider', [DeclarationController::class, 'valider'])->name('declarations.valider');
    });

    // Tournées pour l'admin/agent
    Route::get('tournees-du-jour', [TourneeController::class, 'index'])->name('tournees.index');

    Route::patch('planifications/{planification}/statut', [AffectationController::class, 'updateStatus'])->name('planifications.status.update');
    Route::resource('pesages', PesageController::class);
    Route::resource('tries', TrieController::class)
        ->parameters(['tries' => 'tri']);
    // Gestion des suivi de collecte

    // ...

    // ====================== SUIVI DES COLLECTES ======================
    Route::resource('suivi/collectes', SuiviCollecteController::class)
        ->only(['index', 'show'])
        ->names('suivi_collecte');

    Route::get('/stock/entree', [StockEntreeController::class, 'create'])->name('stock.entree.create');
    Route::post('/stock/entree', [StockEntreeController::class, 'store'])->name('stock.entree.store');
    // Rapport
    Route::get('/rapports', [RapportController::class, 'index'])
        ->name('rapports.index');
    // commande
    Route::get('commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::post('/commandes/{id}/accepter', [CommandeController::class, 'accepter'])->name('commandes.accepter');
    Route::post('/commandes/{id}/refuser', [CommandeController::class, 'refuser'])->name('commandes.refuser');

    // Paiements
    Route::resource('paiements', PaiementController::class);

    // Abonnements
    Route::resource('abonnements', AbonnementsController::class);
    Route::patch('abonnements/{abonnement}/activer', [AbonnementsController::class, 'activer'])->name('abonnements.activer');
    Route::get('abonnements/{abonnement}/rejeter', [AbonnementsController::class, 'rejeterForm'])->name('abonnements.rejeter.form');
    Route::patch('abonnements/{abonnement}/rejeter', [AbonnementsController::class, 'rejeter'])->name('abonnements.rejeter');

    // Déclarations de déchets
    Route::resource('declarations', DeclarationController::class);

    // Configuration
    Route::view('/parametres', 'parametres.index')->name('parametres.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // === MODULE COLLECTEUR ===
    Route::prefix('collecteur')->name('collecteur.')->group(function () {
        Route::get('/tournees', [CollecteurTourneeController::class, 'index'])->name('tournees');
        Route::get('/collecte/encours', [CollecteurCollecteController::class, 'encours'])->name('collecte.encours');
        Route::get('/collecte/terminees', [CollecteurCollecteController::class, 'terminees'])->name('collecte.terminees');
        Route::get('/historique', [CollecteurHistoriqueController::class, 'index'])->name('historique');
        Route::get('/zone', [CollecteurZoneController::class, 'index'])->name('zone');
        Route::get('/tournee/{planification}', [CollecteurTourneeController::class, 'show'])
            ->name('show');
        // Actions de changement de statut
        Route::post('/planification/{planification}/start', [CollecteurCollecteController::class, 'start'])->name('start');
        Route::post('/planification/{planification}/arrive', [CollecteurCollecteController::class, 'arrive'])->name('arrive');
        Route::post('/planification/{planification}/finish', [CollecteurCollecteController::class, 'finish'])->name('finish');
        Route::get('/historique/{id}', [CollecteurHistoriqueController::class, 'show'])
            ->name('historique.show');
    });
});

// === MODULE AGENT ===
Route::prefix('agent')->name('agent.')->middleware(['auth', 'agent'])->group(function () {
    // Dashboard Agent
    Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

    // Collectes reçues
    Route::resource('collectes', CollecteController::class)->only(['index', 'show']);

    // Pesages
    Route::resource('pesages', App\Http\Controllers\Agent\PesageController::class);

    // Tris des déchets
    Route::resource('tries', App\Http\Controllers\Agent\TrieController::class)->parameters(['tries' => 'tri']);

    // Matières premières
    Route::resource('matieres', MatiereController::class)->only(['index', 'show']);

    // Produits
    Route::resource('produits', App\Http\Controllers\Agent\ProduitController::class);

    // Stocks produits finis
    Route::resource('stocks', StockController::class)->only(['index', 'show']);

    // Rapports
    Route::get('rapports', [App\Http\Controllers\Agent\RapportController::class, 'index'])->name('rapports.index');
    Route::get('rapports/journalier', [App\Http\Controllers\Agent\RapportController::class, 'journalier'])->name('rapports.journalier');
    Route::get('rapports/mensuel', [App\Http\Controllers\Agent\RapportController::class, 'mensuel'])->name('rapports.mensuel');
    Route::get('rapports/export/{type}', [App\Http\Controllers\Agent\RapportController::class, 'export'])->name('rapports.export');
});

// Profil Utilisateur
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

require __DIR__.'/auth.php';
