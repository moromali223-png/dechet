<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Declaration;
use App\Models\Pesage;
use App\Models\Planification;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Trie;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_unless($user && $user->role === 'agent', 403);

        // ====================== STATISTIQUES ======================
        $stats = [
            'collectes_today' => Collecte::whereDate('created_at', today())->count(),
            'pesages_today'   => Pesage::whereDate('created_at', today())->count(),
            'tries_today'     => Trie::whereDate('created_at', today())->count(),
            'produits_total'  => Produit::count(),
            'stock_total'     => Stock::sum('quantite_disponible'),
            'collectes_en_cours' => Collecte::where('statut', 'en_cours')->count(),
            'alertes_stock'   => Stock::whereColumn('quantite_disponible', '<=', 'seuil_alerte')->count(),
        ];

        // ====================== KPI manquants ======================
        $poids_today = Pesage::whereDate('created_at', today())->sum('poids') ?? 0;

        $quantite_triee_today = Trie::whereDate('created_at', today())->sum('quantite_trier') ?? 0;

        $produits_fabriqués_today = DB::table('produits')
            ->whereDate('created_at', today())
            ->count();

        // ====================== KPI GÉNÉRAUX ======================
        $clientsCount = User::where('role', 'client')->count();

        $planificationsCount = Planification::where('agent_id', $user->id)->count();

        $planificationsEnCours = Planification::where('agent_id', $user->id)
            ->whereIn('statut', ['planifiee', 'assignee', 'en_route', 'en_cours'])
            ->count();

        $declarationsEnAttente = Declaration::where('statut', 'en_attente')->count();

        // ====================== RELATIONS ======================
        $planifications = Planification::with(['zone', 'abonnement.user', 'collecteur'])
            ->where('agent_id', $user->id)
            ->latest('date_prevue')
            ->take(5)
            ->get();

        $collectesRecentes = Collecte::with(['planification.abonnement.user'])
            ->latest()
            ->take(6)
            ->get();

        $stocksFaibles = Stock::with('produit')
            ->whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->orderBy('quantite_disponible')
            ->take(5)
            ->get();

        // ====================== ACTIVITÉS RÉCENTES ======================
        $pesages = Pesage::with(['collecte.planification.abonnement.user'])
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($pesage) {
                return [
                    'type'    => 'Pesage',
                    'message' => "Pesage : {$pesage->poids} {$pesage->unite}",
                    'client'  => optional($pesage->collecte?->planification?->abonnement?->user)->name ?? 'Client inconnu',
                    'date'    => $pesage->created_at,
                ];
            });

        $tries = Trie::with(['pesage.collecte.planification.abonnement.user'])
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($tri) {
                return [
                    'type'    => 'Tri',
                    'message' => "Tri : {$tri->quantite_trier} {$tri->unite}",
                    'client'  => optional($tri->pesage?->collecte?->planification?->abonnement?->user)->name ?? 'Client inconnu',
                    'date'    => $tri->created_at,
                ];
            });

        $activites = $pesages->concat($tries)
            ->sortByDesc('date')
            ->take(8);

        return view('agent.dashboard.index', compact(
            'stats',
            'poids_today',
            'quantite_triee_today',
            'produits_fabriqués_today',
            'clientsCount',
            'planificationsCount',
            'planificationsEnCours',
            'declarationsEnAttente',
            'planifications',
            'collectesRecentes',
            'stocksFaibles',
            'activites'
        ));
    }
}