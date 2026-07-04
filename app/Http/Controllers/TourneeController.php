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
            'collecteur',
            'declaration.user',
            'abonnement.user',
            'abonnement.user.zone',
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
        if ($user->role === 'collecteur') {
            $query->where('collecteur_id', $user->id);
        }

        // Agent : uniquement ses tournées
        elseif ($user->role === 'agent') {
            $query->where('agent_id', $user->id);
        }

        // Admin : toutes les tournées

        /*
        |--------------------------------------------------------------------------
        | Filtres
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
        | Tri
        |--------------------------------------------------------------------------
        */

        $tournees = $query
            ->orderByDesc('priorite')
            ->orderBy('ordre_passage')
            ->orderBy('date_prevue')
            ->paginate(15)
            ->withQueryString();

        if ($user->role === 'collecteur') {
            return view('collecteur.tournees', compact('tournees'));
        }

        return view('admin.tournees.index', compact('tournees'));
    }
}