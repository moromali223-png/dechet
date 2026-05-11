<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Collectes;
use App\Models\Planification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollecteController extends Controller
{
    public function encours()
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
        ])
            ->where('collecteur_id', $collecteur->id)
            ->whereIn('statut', ['en_route', 'en_cours'])
            ->orderBy('ordre_passage')
            ->get();

        return view('collecteur.collectes.encours', compact('tournees'));
    }

    public function terminees()
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
                $query->where('collecteur_id', $collecteur->id)
                    ->where('statut', 'terminee');
            })
            ->orderByDesc('created_at')
            ->get();

        return view('collecteur.collectes.terminees', compact('collectes'));
    }

    public function start(Planification $planification)
    {
        $this->authorizeForUser(Auth::user(), 'update', $planification);

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
        $this->authorizeForUser(Auth::user(), 'update', $planification);

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
        $this->authorizeForUser(Auth::user(), 'update', $planification);

        if ($planification->statut !== 'en_cours') {
            return back()->with('error', 'Action non autorisée.');
        }

        $request->validate([
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('collectes', 'public')
            : null;

        Collectes::create([
            'planification_id' => $planification->id,
            'photo' => $photoPath,
            'statut' => 'terminee',
        ]);

        $planification->update([
            'statut' => 'terminee',
            'heure_fin' => now(),
        ]);

        return back()->with('success', 'Collecte enregistrée avec succès.');
    }
}
