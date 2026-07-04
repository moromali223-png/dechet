<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Planification;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use App\Models\Zone;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = strtolower($user?->role ?? '');

        return match ($role) {
            'admin'      => $this->adminDashboard(),
            'agent'      => $this->agentDashboard(),
            'collecteur' => $this->collecteur(),
            'client'     => $this->client(),
            default      => $this->adminDashboard(),
        };
    }

    private function agentDashboard()
    {
        return redirect()->route('agent.dashboard');
    }

    private function adminDashboard(): View
    {
        $user = auth()->user();

        $clientsCount     = User::where('role', 'client')->count();
        $agentsCount      = User::where('role', 'agent')->count();
        $collecteursCount = User::where('role', 'collecteur')->count();

        $commandesPending = Commande::where('statut', 'acceptee')->count();

        $commandesByStatus = Commande::selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        $paiementsValidCount = Paiement::whereIn('statut', ['valide', 'paye'])->count();
        $totalRevenue = Paiement::whereIn('statut', ['valide', 'paye'])->sum('montant');

        $produitsCount = Produit::count();
        $abonnementsCount = Abonnement::count();
        $lowStockCount = Stock::enAlerte()->count();

        $recentCommandes = Commande::with(['user', 'produit'])
            ->latest('date_commande')
            ->take(5)
            ->get();

        $revenueByMonth = Paiement::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(montant) as total')
            ->whereIn('statut', ['valide', 'paye'])
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item) => $item->year . '-' . $item->month);

        $monthlyRevenue = collect();

        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths(11 - $i);
            $key = $date->year . '-' . $date->month;

            $monthlyRevenue->push([
                'month'  => $date->translatedFormat('M'),
                'amount' => $revenueByMonth[$key]->total ?? 0,
            ]);
        }

        $statusLabels = [
            'acceptee' => 'Acceptées',
            'refusee'  => 'Refusées',
            'livree'   => 'Livrées',
        ];

        $statusData = [];
        foreach ($statusLabels as $status => $label) {
            $statusData[] = $commandesByStatus[$status] ?? 0;
        }

        return view('dashboard', [
            'userName' => $user->name,
            'clientsCount' => $clientsCount,
            'agentsCount' => $agentsCount,
            'collecteursCount' => $collecteursCount,
            'commandesPending' => $commandesPending,
            'paiementsValidCount' => $paiementsValidCount,
            'totalRevenue' => $totalRevenue,
            'produitsCount' => $produitsCount,
            'abonnementsCount' => $abonnementsCount,
            'lowStockCount' => $lowStockCount,
            'recentCommandes' => $recentCommandes,
            'monthlyRevenue' => $monthlyRevenue,
            'commandesStatusLabels' => array_values($statusLabels),
            'commandesStatusData' => $statusData,
        ]);
    }

    // =========================
    // COLLECTEUR DASHBOARD
    // =========================
    public function collecteur(): View
    {
        $user = auth()->user();

        $collectesCount = Planification::where('collecteur_id', $user->id)
            ->whereHas('collecte')
            ->count();

        // ✅ CORRIGÉ : date_planifiee → date_prevue
        $collectesAujourdHui = Planification::where('collecteur_id', $user->id)
            ->whereDate('date_prevue', today())
            ->count();

        $collectesEnCours = Planification::where('collecteur_id', $user->id)
            ->whereHas('collecte', fn($q) => $q->where('statut', 'en_cours'))
            ->count();

       $zonesCount = Zone::whereHas('collecteurs', function ($q) use ($user) {
    $q->where('users.id', $user->id);
})->count();

        $totalPoidsCollecte = Planification::with(['collecte.pesage'])
    ->where('collecteur_id', $user->id)
    ->get()
    ->sum(function ($p) {
        return $p->collecte->pesage->poids ?? 0;
    });

        $tauxCompletion = 0;
        if ($collectesCount > 0) {
            $completed = Planification::where('collecteur_id', $user->id)
                ->whereHas('collecte', fn($q) => $q->where('statut', 'termine'))
                ->count();

            $tauxCompletion = round(($completed / $collectesCount) * 100);
        }

        $recentCollectes = Planification::with(['zone', 'collecte'])
            ->where('collecteur_id', $user->id)
            ->latest('date_prevue')
            ->take(6)
            ->get();

        $prochainesCollectes = Planification::with(['zone'])
            ->where('collecteur_id', $user->id)
            ->whereDate('date_prevue', '>=', today())
            ->orderBy('date_prevue')
            ->take(5)
            ->get();

        return view('dashboard-collecteur', compact(
            'collectesCount',
            'collectesAujourdHui',
            'collectesEnCours',
            'zonesCount',
            'totalPoidsCollecte',
            'tauxCompletion',
            'recentCollectes',
            'prochainesCollectes'
        ))->with('userName', $user->name);
    }

    public function client(): View
    {
        $user = auth()->user();

        $commandesCount = Commande::where('user_id', $user->id)->count();

        $commandesEnCours = Commande::where('user_id', $user->id)
            ->whereIn('statut', ['en_attente', 'acceptee', 'validee'])
            ->count();

        $commandesLivrees = Commande::where('user_id', $user->id)
            ->where('statut', 'livree')
            ->count();

        $paiementsCount = Paiement::whereHas('commande', fn($q) =>
            $q->where('user_id', $user->id)
        )->count();

        $abonnementsCount = Abonnement::where('user_id', $user->id)->count();

        $recentCommandes = Commande::with(['produit'])
            ->where('user_id', $user->id)
            ->latest('date_commande')
            ->take(5)
            ->get();

        return view('client.dashboard', compact(
            'commandesCount',
            'commandesEnCours',
            'commandesLivrees',
            'paiementsCount',
            'abonnementsCount',
            'recentCommandes'
        ))->with('userName', $user->name);
    }
}