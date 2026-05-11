<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Collectes;
use App\Models\Pesage;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Trie;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard de l'agent
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'collectes_today' => Collectes::whereDate('created_at', today())->count(),
            'pesages_today' => Pesage::whereDate('created_at', today())->count(),
            'tries_today' => Trie::whereDate('created_at', today())->count(),
            'produits_total' => Produit::count(),
            'stock_total' => Stock::sum('quantite_disponible'),
            'collectes_en_cours' => Collectes::where('statut', 'en_cours')->count(),
            'alertes_stock' => Stock::whereColumn('quantite_disponible', '<=', 'seuil_alerte')->count(),
        ];

        // Poids collecté aujourd'hui
        $poids_today = Pesage::whereDate('created_at', today())->sum('poids');

        // Quantité triée aujourd'hui
        $quantite_triee_today = Trie::whereDate('created_at', today())->sum('quantite_trier');

        // Produits fabriqués aujourd'hui (si il y a une date_fabrication ou created_at)
        $produits_fabriqués_today = Produit::whereDate('created_at', today())->count();

        // Collectes récentes
        $collectes_recentes = Collectes::with('planification.client')
            ->latest()
            ->take(5)
            ->get();

        // Stocks faibles
        $stocks_faibles = Stock::with('produit')
            ->whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->get();

        // Activités récentes (combinaison de différentes actions)
        $activites = collect();

        // Ajouter les pesages récents
        $pesages_recent = Pesage::with('collecte.planification.client')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($pesage) {
                return [
                    'type' => 'pesage',
                    'message' => "Pesage effectué: {$pesage->poids} {$pesage->unite}",
                    'date' => $pesage->created_at,
                    'client' => $pesage->collecte->planification->client->nom ?? 'N/A',
                ];
            });

        // Ajouter les tris récents
        $tries_recent = Trie::with('pesage.collecte.planification.client')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($tri) {
                return [
                    'type' => 'tri',
                    'message' => "Tri effectué: {$tri->quantite_trier} {$tri->unite} de {$tri->type_dechet}",
                    'date' => $tri->created_at,
                    'client' => $tri->pesage->collecte->planification->client->nom ?? 'N/A',
                ];
            });

        $activites = $pesages_recent->concat($tries_recent)->sortByDesc('date')->take(6);

        return view('agent.dashboard.index', compact(
            'stats',
            'poids_today',
            'quantite_triee_today',
            'produits_fabriqués_today',
            'collectes_recentes',
            'stocks_faibles',
            'activites'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
