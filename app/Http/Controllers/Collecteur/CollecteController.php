<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Collecte;        // ← Note : Collecte (singulier)
use App\Models\Planification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollecteController extends Controller
{
    public function encours()
    {
        $user = Auth::user();

        if ($user->role !== 'collecteur') {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $tournees = Planification::with([
            'zone',
            'declaration.user',
            'abonnement.user',        // ← Correction : client → user
            'collecteur',
        ])
        ->where('collecteur_id', $user->id)
        ->whereIn('statut', ['en_route', 'en_cours'])
        ->orderBy('ordre_passage')
        ->get();

        return view('collecteur.collectes.encours', compact('tournees'));
    }

    public function terminees()
    {
        $user = Auth::user();

        if ($user->role !== 'collecteur') {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $collectes = Collecte::with([
            'planification.zone',
            'planification.declaration.user',
            'planification.abonnement.user',   // ← Correction
        ])
        ->whereHas('planification', function ($query) use ($user) {
            $query->where('collecteur_id', $user->id)
                  ->where('statut', 'terminee');
        })
        ->orderByDesc('created_at')
        ->get();

        return view('collecteur.collectes.terminees', compact('collectes'));
    }

    public function start(Planification $planification)
    {
        $user = Auth::user();

        if ($user->role !== 'collecteur' || $planification->collecteur_id !== $user->id) {
            abort(403, 'Action non autorisée.');
        }

        if ($planification->statut !== 'assignee') {
            return back()->with('error', 'Action non autorisée.');
        }

        $planification->update([
            'statut' => 'en_route',
            'heure_depart' => now(),
        ]);

        return back()->with('success', 'Tournée démarrée.');
    }

    public function arrive(Planification $planification)
    {
        $user = Auth::user();

        if ($user->role !== 'collecteur' || $planification->collecteur_id !== $user->id) {
            abort(403, 'Action non autorisée.');
        }

        if ($planification->statut !== 'en_route') {
            return back()->with('error', 'Action non autorisée.');
        }

        $planification->update([
            'statut' => 'en_cours',
            'heure_arrivee' => now(),
        ]);

        return back()->with('success', 'Collecte en cours.');
    }

    public function finish(Request $request, Planification $planification)
    {
        $user = Auth::user();

        if ($user->role !== 'collecteur' || $planification->collecteur_id !== $user->id) {
            abort(403, 'Action non autorisée.');
        }

        if ($planification->statut !== 'en_cours') {
            return back()->with('error', 'Action non autorisée.');
        }

        $request->validate([
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('collectes', 'public')
            : null;

        Collecte::create([
            'planification_id' => $planification->id,
            'photo'            => $photoPath,
            'statut'           => 'terminee',
        ]);

        $planification->update([
            'statut'    => 'terminee',
            'heure_fin' => now(),
        ]);

        return back()->with('success', 'Collecte enregistrée avec succès.');
    }
}