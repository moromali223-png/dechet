<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Collectes;
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $collecteur = $user->collecteurs;

        if (! $collecteur) {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $collectes = Collectes::with([
            'planification.zone',
            'planification.declaration.user',
            'planification.abonnement.client.user',
        ])
            ->whereHas('planification', function ($query) use ($collecteur) {
                $query->where('collecteur_id', $collecteur->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('collecteur.historique.index', compact('collectes'));
    }

    public function show($id)
    {
        $collecte = Collectes::with([
            'planification.declaration.user',
            'planification.abonnement.client.user',
            'planification.zone',
        ])->findOrFail($id);

        return view('collecteur.historique.show', compact('collecte'));
    }
}
