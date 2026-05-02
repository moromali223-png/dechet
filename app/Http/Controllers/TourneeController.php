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

        if ($user->role === 'collecteur' && $user->collecteur) {
            $query->where('collecteur_id', $user->collecteur->id);
        } elseif ($user->role === 'agent') {
            $query->where('agent_id', $user->id);
        }

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
        |
        | Suppression de heure_prevue car la colonne n'existe pas
        |
        */

        $tournees = $query
            ->orderByDesc('priorite')
            ->orderByRaw('COALESCE(ordre_passage, 999999) ASC')
            ->orderBy('date_prevue')
            ->paginate(15)
            ->withQueryString();

        return view('tournees.index', compact('tournees'));
    }
}