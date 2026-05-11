<?php

namespace App\Providers;

use App\Models\Abonnement;
use App\Models\Declaration;
use App\Models\Planification;
use App\Observers\AbonnementsObserver;
use App\Policies\AbonnementPolicy;
use App\Policies\DeclarationPolicy;
use App\Policies\PlanificationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Produit;
use App\Observers\ProduitObserver;

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
    }
}
