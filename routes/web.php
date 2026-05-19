<?php

use App\Http\Controllers\AbonnementsController;
use App\Http\Controllers\Admin\CommandeAdminController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\Agent\CollecteController;
use App\Http\Controllers\Agent\MatiereController;
use App\Http\Controllers\Agent\StockController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\AlertesController;
use App\Http\Controllers\Client\CommandeClientController;
use App\Http\Controllers\Client\SuiviCollecteClientController;
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

    // Dashboards spécifiques
    Route::middleware('agent')->get('/dashboard/agent', [DashboardController::class, 'agent'])->name('dashboard.agent');
    Route::middleware('collecteur')->get('/dashboard/collecteur', [DashboardController::class, 'collecteur'])->name('dashboard.collecteur');
    Route::middleware('client')->get('/dashboard/client', [DashboardController::class, 'client'])->name('dashboard.client');

    // === ADMIN ===
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/declarations', [DeclarationController::class, 'adminIndex'])
            ->name('declarations.index');

        Route::post('/declarations/{declaration}/valider', [DeclarationController::class, 'valider'])
            ->name('declarations.valider');

        Route::post('/declarations/{declaration}/rejeter', [DeclarationController::class, 'rejeter'])
            ->name('declarations.rejeter');

        // Module e-commerce
        Route::get('/commandes', [CommandeAdminController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/{commande}', [CommandeAdminController::class, 'show'])->name('commandes.show');
        Route::post('/commandes/{commande}/accepter', [CommandeAdminController::class, 'accepter'])->name('commandes.accepter');
        Route::post('/commandes/{commande}/refuser', [CommandeAdminController::class, 'refuser'])->name('commandes.refuser');
        Route::get('/paiements', [CommandeAdminController::class, 'paiements'])->name('paiements.index');
        Route::get('/produits', [CommandeAdminController::class, 'produits'])->name('produits.index');
    });

    // === RESSOURCES PARTAGÉES ===
    Route::resource('zones', ZoneController::class);
    Route::resource('collecteurs', CollecteurController::class);
    Route::resource('agents', AgentsController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('produits', ProduitController::class);
    Route::resource('inventaire', InventaireController::class);
    Route::resource('stock-entree', StockEntreeController::class)->parameters(['stock-entree' => 'stockEntree']);
    Route::resource('mouvements', MouvementController::class);
    Route::resource('planifications', PlanificationController::class);
    Route::resource('pesages', PesageController::class);

    // Gestion des Affectations
    Route::get('affectations', [AffectationController::class, 'index'])->name('affectations.index');
    Route::post('affectations/{planification}/assign', [AffectationController::class, 'assign'])->name('affectations.assign');

    // TRIS → Partagé entre Admin et Agent (le plus important)
    Route::resource('tries', TrieController::class)
        ->parameters(['tries' => 'tri'])
        ->names('tries');

    // Autres ressources partagées
    Route::get('alertes', [AlertesController::class, 'index'])->name('alertes.index');
    Route::get('alertes/rapport', [AlertesController::class, 'rapport'])->name('alertes.rapport');
    Route::post('alertes/{alerte}/traiter', [AlertesController::class, 'marquerTraitee'])->name('alertes.traiter');

    Route::get('tournees-du-jour', [TourneeController::class, 'index'])->name('tournees.index');
    Route::patch('planifications/{planification}/statut', [AffectationController::class, 'updateStatus'])->name('planifications.status.update');

    // Suivi des collectes
    Route::resource('suivi/collectes', SuiviCollecteController::class)
        ->only(['index', 'show'])
        ->names('suivi_collecte');

    // Commandes, Rapports, etc.
    Route::get('/stock/entree', [StockEntreeController::class, 'create'])->name('stock.entree.create');
    Route::post('/stock/entree', [StockEntreeController::class, 'store'])->name('stock.entree.store');
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('commandes', [CommandeAdminController::class, 'index'])->name('commandes.index');
    Route::post('/commandes/{id}/accepter', [CommandeAdminController::class, 'accepter'])->name('commandes.accepter');
    Route::post('/commandes/{id}/refuser', [CommandeAdminController::class, 'refuser'])->name('commandes.refuser');

    Route::resource('paiements', PaiementController::class);
    Route::resource('abonnements', AbonnementsController::class);
    Route::resource('declarations', DeclarationController::class);

    Route::view('/parametres', 'parametres.index')->name('parametres.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // === MODULE COLLECTEUR ===
    Route::prefix('collecteur')->name('collecteur.')->group(function () {
        Route::get('/tournees', [CollecteurTourneeController::class, 'index'])->name('tournees');
        Route::get('/collecte/encours', [CollecteurCollecteController::class, 'encours'])->name('collecte.encours');
        Route::get('/collecte/terminees', [CollecteurCollecteController::class, 'terminees'])->name('collecte.terminees');
        Route::get('/historique', [CollecteurHistoriqueController::class, 'index'])->name('historique');
        Route::get('/zone', [CollecteurZoneController::class, 'index'])->name('zone');
        Route::get('/tournee/{planification}', [CollecteurTourneeController::class, 'show'])->name('show');

        Route::post('/planification/{planification}/start', [CollecteurCollecteController::class, 'start'])->name('start');
        Route::post('/planification/{planification}/arrive', [CollecteurCollecteController::class, 'arrive'])->name('arrive');
        Route::post('/planification/{planification}/finish', [CollecteurCollecteController::class, 'finish'])->name('finish');
        Route::get('/historique/{id}', [CollecteurHistoriqueController::class, 'show'])->name('historique.show');
        Route::get('/client/{client}', [CollecteurZoneController::class, 'showClient'])
          ->name('client.show');
        });

    
    /// ---- bock client ---- /////////
   Route::prefix('client')->name('client.')->group(function () {

    Route::get('/produits', [CommandeClientController::class, 'produits'])
        ->name('produits.index');

    Route::get('/produits/{produit}', [CommandeClientController::class, 'showProduit'])
        ->name('produits.show');

    // ✅ Une seule définition, bon contrôleur
    Route::post('/produits/{produit}/commander', [CommandeClientController::class, 'commander'])
        ->name('produits.commander');

    Route::get('/mes-commandes', [CommandeClientController::class, 'mesCommandes'])
        ->name('commandes.index');

    Route::get('/mes-commandes/{commande}', [CommandeClientController::class, 'showCommande'])
        ->name('commandes.show');
});

Route::prefix('client')->middleware(['auth'])->group(function () {

    Route::get('/suivi-collecte', [SuiviCollecteClientController::class, 'index'])
        ->name('client.suivi_collecte.index');

    Route::get('/suivi-collecte/{id}', [SuiviCollecteClientController::class, 'show'])
        ->name('client.suivi_collecte.show');
});
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/mon-compte', [App\Http\Controllers\Client\CompteClientController::class, 'index'])
        ->name('compte.index');

    Route::put('/mon-compte', [App\Http\Controllers\Client\CompteClientController::class, 'update'])
        ->name('compte.update');
});

});

// === MODULE AGENT ===
Route::prefix('agent')->name('agent.')->middleware(['auth', 'agent'])->group(function () {
    // Dashboard Agent
    Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

    // Collectes reçues
    Route::resource('collectes', CollecteController::class)->only(['index', 'show']);

    // Pesages Agent (si tu veux un controller différent)
    Route::resource('pesages', App\Http\Controllers\Agent\PesageController::class);

    // Matières premières
Route::get('/matieres', [MatiereController::class, 'index'])->name('matieres.index');

Route::get('/matieres/{matiere}', [MatiereController::class, 'show'])
    ->where('matiere', '.*')
    ->name('matieres.show');
    // Produits
    Route::resource('produits', App\Http\Controllers\Agent\ProduitController::class);

    // Stocks produits finis
    Route::resource('stocks', StockController::class)->only(['index', 'show']);
    Route::prefix('agent')
        ->middleware(['auth'])
        ->name('agent.')
        ->group(function () {
            Route::get('/collectes', [CollecteController::class, 'index'])
                ->name('collectes.index');
        });
    // Rapports Agent
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
