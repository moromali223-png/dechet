<?php

namespace App\Http\Controllers;

use App\Models\Planification;
use Illuminate\Http\Request;

class TourneeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Planification::with([
            'zone',
            'agent',
            'collecteur.user',
            'declaration.user',
            'abonnement.client.user',
        ])
            ->whereDate('date_prevue', today())
            ->whereIn('statut', [
                'planifiee',
                'assignee',
                'en_route',
                'en_cours',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Filtrage selon le rôle
        |--------------------------------------------------------------------------
        */

        // Collecteur : uniquement ses tournées
        if ($user->role === 'collecteur' && $user->collecteur) {
            $query->where('collecteur_id', $user->collecteur->id);
        }

        // Agent : uniquement ses tournées
        elseif ($user->role === 'agent') {
            $query->where('agent_id', $user->id);
        }

        // Admin : voit toutes les tournées
        // Aucun filtre supplémentaire nécessaire

        /*
        |--------------------------------------------------------------------------
        | Filtres optionnels
        |--------------------------------------------------------------------------
        */
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Tri professionnel
        |--------------------------------------------------------------------------
        */
        $tournees = $query
            ->orderByDesc('priorite')
            ->orderBy('ordre_passage')
            ->orderBy('date_prevue')
            ->paginate(15)
            ->withQueryString();

        // Si c'est un collecteur, on utilise la vue simplifiée du dossier collecteurfiles
        if ($user->role === 'collecteur') {
            return view('collecteur.tournees', compact('tournees'));
        }

        return view('admin.tournees.index', compact('tournees'));
    }
}
