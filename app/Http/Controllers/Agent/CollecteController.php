<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Collectes;
use Illuminate\Http\Request;

class CollecteController extends Controller
{
    /**
     * Afficher la liste des collectes reçues
     */
    public function index(Request $request)
    {
        $query = Collectes::with([
            'planification.abonnement.client',
            'pesage',                    // ← singulier
        ]);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('client')) {
            $query->whereHas('planification.abonnement.client', function ($q) use ($request) {
                $q->where('nom', 'like', '%'.$request->client.'%');
            });
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('planification.abonnement.client', function ($subQ) use ($search) {
                    $subQ->where('nom', 'like', '%'.$search.'%');
                })
                    ->orWhere('commentaire', 'like', '%'.$search.'%')
                    ->orWhere('statut', 'like', '%'.$search.'%');
            });
        }

        $collectes = $query->latest()->paginate(15);

        $statuts = [
            'en_cours' => 'En cours',
            'terminee' => 'Terminée',
            'arrive_au_centre' => 'Arrivée au centre',
            'pesee' => 'Pesée',
            'triee' => 'Triée',
        ];

        return view('agent.collectes.index', compact('collectes', 'statuts'));
    }

    /**
     * Afficher les détails d'une collecte
     */
    public function show(Collectes $collecte)
    {
        $collecte->load([
            'planification.abonnement.client',
            'planification.collecteur.user',
            'pesage.tries',        // ← singulier
        ]);
        // Timeline des activités
        $timeline = collect();

        // Collecte créée
        $timeline->push([
            'type' => 'creation',
            'title' => 'Collecte créée',
            'description' => 'La collecte a été planifiée',
            'date' => $collecte->created_at,
            'icon' => 'bx-package',
        ]);

        // Statuts successifs
        if ($collecte->heure_depart) {
            $timeline->push([
                'type' => 'depart',
                'title' => 'Départ du collecteur',
                'description' => 'Le collecteur a commencé la collecte',
                'date' => $collecte->heure_depart,
                'icon' => 'bx-play',
            ]);
        }

        if ($collecte->heure_fin) {
            $timeline->push([
                'type' => 'arrivee',
                'title' => 'Arrivée au centre',
                'description' => 'Le collecteur est arrivé au centre de tri',
                'date' => $collecte->heure_fin,
                'icon' => 'bx-check',
            ]);
        }

        // Pesages associés
        foreach ($collecte->pesages as $pesage) {
            $timeline->push([
                'type' => 'pesage',
                'title' => 'Pesage effectué',
                'description' => "Poids: {$pesage->poids} {$pesage->unite}",
                'date' => $pesage->created_at,
                'icon' => 'bx-trending-up',
            ]);

            // Tris associés au pesage
            foreach ($pesage->tries as $tri) {
                $timeline->push([
                    'type' => 'tri',
                    'title' => 'Tri effectué',
                    'description' => "Type: {$tri->type_dechet}, Quantité: {$tri->quantite_trier} {$tri->unite}",
                    'date' => $tri->created_at,
                    'icon' => 'bx-filter-alt',
                ]);
            }
        }

        $timeline = $timeline->sortBy('date');

        return view('agent.collectes.show', compact('collecte', 'timeline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
