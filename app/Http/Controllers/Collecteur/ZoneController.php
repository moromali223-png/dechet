<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Planification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'collecteur', 403);

        $zone = $user->zone;

        /*
        |--------------------------------------------------------------------------
        | Si aucune zone n'est affectée
        |--------------------------------------------------------------------------
        */
        if (!$zone) {

            return view('collecteur.zone.index', [
                'user'                  => $user,
                'zone'                  => null,
                'stats'                 => collect(),
                'totalCollectes'        => 0,
                'collectesAujourdhui'   => 0,
                'clients'               => new LengthAwarePaginator([], 0, 15),
                'recentCollectes'       => collect(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Statistiques
        |--------------------------------------------------------------------------
        */

        $stats = Planification::where('collecteur_id', $user->id)
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $totalCollectes = Collecte::whereHas('planification', function ($q) use ($user) {
            $q->where('collecteur_id', $user->id);
        })->count();

        $collectesAujourdhui = Collecte::whereHas('planification', function ($q) use ($user) {
            $q->where('collecteur_id', $user->id);
        })
        ->whereDate('created_at', today())
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Clients
        |--------------------------------------------------------------------------
        */

        $clients = User::query()
            ->where('role', 'client')
            ->where('zone_id', $zone->id)
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('telephone', 'like', "%{$search}%");

                });

            })
            ->orderBy('name')
            ->paginate(15);

        /*
        |--------------------------------------------------------------------------
        | Dernière collecte de chaque client
        |--------------------------------------------------------------------------
        */

        $clients->getCollection()->transform(function ($client) {

            $client->derniereCollecte = Collecte::whereHas(
                'planification.abonnement',
                function ($q) use ($client) {
                    $q->where('user_id', $client->id);
                }
            )
            ->latest()
            ->first();

            return $client;

        });

        /*
        |--------------------------------------------------------------------------
        | Collectes récentes
        |--------------------------------------------------------------------------
        */

        $recentCollectes = Collecte::with([
                'planification.zone',
                'planification.abonnement.user'
            ])
            ->whereHas('planification', function ($q) use ($user) {
                $q->where('collecteur_id', $user->id);
            })
            ->latest()
            ->take(10)
            ->get();

        return view('collecteur.zone.index', compact(
            'user',
            'zone',
            'stats',
            'totalCollectes',
            'collectesAujourdhui',
            'clients',
            'recentCollectes'
        ));
    }

    public function showClient(User $client)
    {
        abort_unless($client->role === 'client', 404);

        $client->load([
            'zone',
            'abonnements'
        ]);

        return view('collecteur.zone.show-client', compact('client'));
    }
}