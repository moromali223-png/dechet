<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Agents;
use App\Models\Client;
use App\Models\Collecteur;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Zone;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = strtolower($user?->role ?? '');

        return match ($role) {
            'admin' => $this->adminDashboard(),
            'agent' => $this->agent(),
            'collecteur' => $this->collecteur(),
            'client' => $this->client(),
            default => $this->adminDashboard(),
        };
    }

private function adminDashboard(): View
{
    $user = auth()->user();

    $clientsCount = Client::count();

    // Commandes (selon vos vrais statuts)
    $commandesPending = Commande::where('statut', 'acceptee')->count();

    $commandesByStatus = Commande::selectRaw('statut, COUNT(*) as total')
        ->groupBy('statut')
        ->pluck('total', 'statut')
        ->toArray();

    // Paiements
    $paiementsValidCount = Paiement::whereIn('statut', [
        'valide',
        'paye'
    ])->count();

    $totalRevenue = Paiement::whereIn('statut', [
        'valide',
        'paye'
    ])->sum('montant');

    // Divers
    $produitsCount = Produit::count();
    $agentsCount = Agents::count();
    $collecteursCount = Collecteur::count();
    $abonnementsCount = Abonnement::count();
    $lowStockCount = Stock::enAlerte()->count();

    // Dernières commandes
    $recentCommandes = Commande::with([
        'client.user'
    ])
    ->latest('created_at')
    ->take(5)
    ->get();

    // Revenu mensuel
    $revenueByMonth = Paiement::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(montant) as total')
        ->whereIn('statut', ['valide', 'paye'])
        ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->keyBy(fn($item) => $item->year . '-' . $item->month);

    $monthlyRevenue = collect();

    for ($i = 0; $i < 12; $i++) {
        $date = now()->subMonths(11 - $i);
        $key = $date->year . '-' . $date->month;

        $monthlyRevenue->push([
            'month' => $date->translatedFormat('M'),
            'amount' => $revenueByMonth[$key]->total ?? 0,
        ]);
    }

    // Statuts commandes (vrais statuts DB)
    $statusLabels = [
        'acceptee' => 'Acceptées',
        'refusee' => 'Refusées',
    ];

    $statusData = [];

    foreach ($statusLabels as $status => $label) {
        $statusData[] = $commandesByStatus[$status] ?? 0;
    }

    return view('dashboard', [
        'userName' => $user->name,
        'clientsCount' => $clientsCount,
        'commandesPending' => $commandesPending,
        'paiementsValidCount' => $paiementsValidCount,
        'totalRevenue' => $totalRevenue,
        'produitsCount' => $produitsCount,
        'agentsCount' => $agentsCount,
        'collecteursCount' => $collecteursCount,
        'abonnementsCount' => $abonnementsCount,
        'lowStockCount' => $lowStockCount,
        'recentCommandes' => $recentCommandes,
        'monthlyRevenue' => $monthlyRevenue,
        'commandesStatusLabels' => array_values($statusLabels),
        'commandesStatusData' => $statusData,
    ]);
}
    public function agent(): View
    {
        $user = auth()->user();

        // Données spécifiques à l'agent
        $clientsCount = Client::count(); // Ou filtrer par agent si nécessaire
        $commandesPending = Commande::where('statut', 'en_attente')->count();
        $recentCommandes = Commande::with('client')
            ->orderByDesc('date_commande')
            ->limit(5)
            ->get();

        return view('dashboard-agent', [
            'userName' => $user?->name ?? 'Agent',
            'clientsCount' => $clientsCount,
            'commandesPending' => $commandesPending,
            'recentCommandes' => $recentCommandes,
        ]);
    }

    public function collecteur(): View
    {
        $user = auth()->user();

        // Données spécifiques au collecteur
        $collectesCount = Collecteur::count(); // Ajuster selon les besoins
        $zonesCount = Zone::count();

        return view('dashboard-collecteur', [
            'userName' => $user?->name ?? 'Collecteur',
            'collectesCount' => $collectesCount,
            'zonesCount' => $zonesCount,
        ]);
    }
public function client(): View
{
    $user = auth()->user();

    $client = Client::where('user_id', $user->id)->first();

    if (!$client) {
        return view('client.dashboard', [
            'userName' => $user->name ?? 'Client',
            'commandesCount' => 0,
            'paiementsCount' => 0,
            'recentCommandes' => collect(),
            'abonnementsCount' => 0,
            'commandesEnCours' => 0,
            'commandesLivrees' => 0,
        ]);
    }

    $commandesCount = Commande::where('client_id', $client->id)->count();

    $commandesEnCours = Commande::where('client_id', $client->id)
        ->whereIn('statut', ['en_attente', 'validée'])
        ->count();

    $commandesLivrees = Commande::where('client_id', $client->id)
        ->where('statut', 'livrée')
        ->count();

    $paiementsCount = Paiement::whereHas('commande', function ($query) use ($client) {
        $query->where('client_id', $client->id);
    })->count();

    $abonnementsCount = Abonnement::whereHas('client', function ($q) use ($user) {
        $q->where('user_id', $user->id);
    })->count();

    $recentCommandes = Commande::where('client_id', $client->id)
        ->latest('date_commande')
        ->take(5)
        ->get();

    return view('client.dashboard', compact(
        'commandesCount',
        'paiementsCount',
        'recentCommandes',
        'abonnementsCount',
        'commandesEnCours',
        'commandesLivrees'
    ))->with('userName', $user->name);
}
}
