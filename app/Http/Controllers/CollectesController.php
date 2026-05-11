<?php

namespace App\Http\Controllers;

use App\Models\Collectes;
use App\Models\Planification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectesController extends Controller
{
    /**
     * Liste des tournées du jour pour le collecteur connecté
     */
    public function mesTournees()
    {
        $user = Auth::user();

        // Sécurité : vérifier collecteur
        if (! $user->collecteur) {
            abort(403, 'Accès refusé');
        }

        $tournees = Planification::with([
            'zone',
            'declaration.user',
            'abonnement.client.user',
        ])
            ->where('collecteur_id', $user->collecteur->id)
            ->whereDate('date_prevue', today())
            ->whereIn('statut', [
                'assignee',
                'en_route',
                'en_cours',
                'terminee', // 🔥 IMPORTANT (affichage complet)
            ])
            ->orderBy('ordre_passage')
            ->get();

        return view('collecteur.tournees', compact('tournees'));
    }

    /**
     * Démarrer la tournée
     */
    public function start(Planification $planification)
    {
        $this->authorizeForUser(Auth::user(), 'update', $planification);

        if ($planification->statut !== 'assignee') {
            return back()->with('error', 'Action non autorisée.');
        }

        // éviter double clic / bug
        if ($planification->heure_depart) {
            return back()->with('error', 'Déjà démarrée.');
        }

        $planification->update([
            'statut' => 'en_route',
            'heure_depart' => now(),
        ]);

        return back()->with('success', 'Tournée démarrée ✅');
    }

    /**
     * Arrivée sur site
     */
    public function arrive(Planification $planification)
    {
        $this->authorizeForUser(Auth::user(), 'update', $planification);

        if ($planification->statut !== 'en_route') {
            return back()->with('error', 'Action non autorisée.');
        }

        // éviter double arrivée
        if ($planification->heure_arrivee) {
            return back()->with('error', 'Arrivée déjà enregistrée.');
        }

        $planification->update([
            'statut' => 'en_cours',
            'heure_arrivee' => now(),
        ]);

        return back()->with('success', 'Arrivée enregistrée ✅');
    }

    /**
     * Terminer la collecte
     */
    public function finish(Request $request, Planification $planification)
    {
        $this->authorizeForUser(Auth::user(), 'update', $planification);

        if ($planification->statut !== 'en_cours') {
            return back()->with('error', 'Action non autorisée.');
        }

        // validation propre
        $validated = $request->validate([
            'photo' => 'nullable|image|max:2048',
            'commentaire' => 'nullable|string|max:500',
        ]);

        // sécurité : éviter double collecte
        if ($planification->collecte) {
            return back()->with('error', 'Collecte déjà enregistrée.');
        }

        // création collecte
        Collectes::create([
            'planification_id' => $planification->id,
            'photo' => $request->file('photo')
                ? $request->file('photo')->store('collectes', 'public')
                : null,
            'commentaire' => $validated['commentaire'] ?? null,
            'statut' => 'terminee',
            'heure_depart' => $planification->heure_depart,
            'heure_fin' => now(),
        ]);

        // mise à jour planification
        $planification->update([
            'statut' => 'terminee',
            'heure_fin' => now(),
        ]);

        return back()->with('success', 'Collecte terminée avec succès ✅');
    }
}
