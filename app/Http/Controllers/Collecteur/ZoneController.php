<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Collectes;
use App\Models\Planification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $collecteur = $user->collecteurs;

        if (! $collecteur) {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $zone = $collecteur->zone;

        if (! $zone) {
            return view('collecteur.zone.index', [
                'collecteur' => $collecteur,
                'zone' => null,
                'stats' => collect(),
                'totalCollectes' => 0,
                'collectesAujourdhui' => 0,
                'clients' => collect(),
                'recentCollectes' => collect(),
            ]);
        }

        // Statistiques
        $stats = Planification::where('collecteur_id', $collecteur->id)
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $totalCollectes = Collectes::whereHas('planification', fn ($q) => $q->where('collecteur_id', $collecteur->id)
        )->count();

        $collectesAujourdhui = Collectes::whereHas('planification', fn ($q) => $q->where('collecteur_id', $collecteur->id)
        )->whereDate('created_at', today())->count();

        // Clients de la zone
      $clients = Client::with('user')
    ->where('zone_id', $zone->id)
    ->when($request->search, function ($query, $search) {
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    })
    ->paginate(15);

$clients->getCollection()->transform(function ($client) {
    $client->derniereCollecte = Collectes::whereHas('planification.abonnement', function ($q) use ($client) {
        $q->where('user_id', $client->user_id);
    })
    ->latest('created_at')
    ->first();

    return $client;
});

        // Dernières collectes
        $recentCollectes = Collectes::with(['planification.abonnement.client.user'])
            ->whereHas('planification', fn ($q) => $q->where('collecteur_id', $collecteur->id)
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->take(10)
            ->get();

        return view('collecteur.zone.index', compact(
            'collecteur',
            'zone',
            'stats',
            'totalCollectes',
            'collectesAujourdhui',
            'clients',
            'recentCollectes'
        ));
    }

    public function showClient(Client $client)
{
    $client->load([
        'user',
        'zone',
        'abonnements',
    ]);

    return view('collecteur.zone.show-client', compact('client'));
}
}

