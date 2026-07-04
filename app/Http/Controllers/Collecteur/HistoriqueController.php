<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Collecte;        // ← Singulier et correct
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'collecteur') {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $collectes = Collecte::with([
            'planification.zone',
            'planification.declaration.user',
            'planification.abonnement.user',     // ← Correction : client → user
        ])
        ->whereHas('planification', function ($query) use ($user) {
            $query->where('collecteur_id', $user->id);
        })
        ->orderByDesc('created_at')
        ->get();

        return view('collecteur.historique.index', compact('collectes'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $collecte = Collecte::with([
            'planification.declaration.user',
            'planification.abonnement.user',     // ← Correction
            'planification.zone',
        ])
        ->whereHas('planification', function ($query) use ($user) {
            $query->where('collecteur_id', $user->id);
        })
        ->findOrFail($id);

        return view('collecteur.historique.show', compact('collecte'));
    }
}