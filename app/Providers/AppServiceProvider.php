<?php

namespace App\Providers;

use App\Models\Abonnement;
use App\Models\Commande;
use App\Models\Declaration;
use App\Models\Planification;
use App\Models\Produit;
use App\Observers\AbonnementsObserver;
use App\Observers\ProduitObserver;
use App\Policies\AbonnementPolicy;
use App\Policies\CommandePolicy;
use App\Policies\DeclarationPolicy;
use App\Policies\PlanificationPolicy;
use App\Policies\ProduitPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrement de l'Observer
        Abonnement::observe(AbonnementsObserver::class);
        Produit::observe(ProduitObserver::class);

        Gate::policy(Declaration::class, DeclarationPolicy::class);
        Gate::policy(Planification::class, PlanificationPolicy::class);
        Gate::policy(Abonnement::class, AbonnementPolicy::class);
        Gate::policy(Commande::class, CommandePolicy::class);
        Gate::policy(Produit::class, ProduitPolicy::class);
    }
}
