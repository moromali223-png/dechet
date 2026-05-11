<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Planification;
use Illuminate\Support\Facades\Auth;

class TourneeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $collecteur = $user->collecteurs;

        if (! $collecteur) {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $tournees = Planification::with([
            'zone',
            'declaration.user',
            'abonnement.client.user',
            'collecteur.user',
        ])
            ->where('collecteur_id', $collecteur->id)
            ->whereDate('date_prevue', today())
            ->orderBy('ordre_passage')
            ->get();

        return view('collecteur.tournees.index', compact('tournees'));
    }

    public function show($id)
    {
        $planification = Planification::with([
            'zone',
            'collecteur.user',
            'abonnement',
            'abonnement.client',
            'abonnement.client.user',
            'declaration',
            'declaration.user',
            'collecte',
        ])->findOrFail($id);

        return view('collecteur.tournees.show', compact('planification'));
    }
}
