<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Collectes;
use App\Models\Commande;
use App\Models\Pesage;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Trie;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== STATISTIQUES ====================
        $stats = [
            'collectes_today' => Collectes::whereDate('created_at', today())->count(),
            'pesages_today' => Pesage::whereDate('created_at', today())->count(),
            'tries_today' => Trie::whereDate('created_at', today())->count(),
            'produits_total' => Produit::count(),
            'stock_total' => Stock::sum('quantite_disponible'),
            'collectes_en_cours' => Collectes::where('statut', 'en_cours')->count(),
            'alertes_stock' => Stock::whereColumn('quantite_disponible', '<=', 'seuil_alerte')->count(),
        ];

        $poids_today = Pesage::whereDate('created_at', today())->sum('poids');
        $quantite_triee_today = Trie::whereDate('created_at', today())->sum('quantite_trier');
        $produits_fabriqués_today = Produit::whereDate('created_at', today())->count();

        // ==================== DONNÉES POUR LES KPI EN HAUT ====================
        $clientsCount = Client::count();
        $commandesPending = Commande::where('statut', 'en_attente')->count();
        $recentCommandes = Commande::with('client')
            ->latest()
            ->take(5)
            ->get();

        // ==================== COLLECTES RÉCENTES ====================
        $collectes_recentes = Collectes::with('planification.abonnement.client')
            ->latest()
            ->take(6)
            ->get();

        // ==================== STOCKS FAIBLES ====================
        $stocks_faibles = Stock::with('produit')
            ->whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->orderBy('quantite_disponible', 'asc')
            ->take(5)
            ->get();

        // ==================== ACTIVITÉS RÉCENTES (Sécurisé) ====================
        $pesages_recent = Pesage::with('collecte.planification.abonnement.client')
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($pesage) {
                $clientName = optional(optional(optional($pesage->collecte?->planification?->abonnement)?->client))->nom ?? 'Client inconnu';

                return [
                    'type' => 'pesage',
                    'message' => "Pesage effectué : {$pesage->poids} {$pesage->unite}",
                    'date' => $pesage->created_at,
                    'client' => $clientName,
                ];
            });

        $tries_recent = Trie::with('pesage.collecte.planification.abonnement.client')
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($tri) {
                $clientName = optional(optional(optional($tri->pesage?->collecte?->planification?->abonnement)?->client))->nom ?? 'Client inconnu';

                return [
                    'type' => 'tri',
                    'message' => "Tri effectué : {$tri->quantite_trier} {$tri->unite} de {$tri->type_dechet}",
                    'date' => $tri->created_at,
                    'client' => $clientName,
                ];
            });

        $activites = $pesages_recent->concat($tries_recent)
            ->sortByDesc('date')
            ->take(8);

        return view('agent.dashboard.index', compact(
            'stats',
            'poids_today',
            'quantite_triee_today',
            'produits_fabriqués_today',
            'clientsCount',
            'commandesPending',
            'recentCommandes',
            'collectes_recentes',
            'stocks_faibles',
            'activites'
        ));
    }
}
