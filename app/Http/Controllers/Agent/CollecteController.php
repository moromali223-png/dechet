<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use Illuminate\Http\Request;

class CollecteController extends Controller
{
    /**
     * Afficher la liste des collectes reçues
     */
    public function index(Request $request)
    {
        $query = Collecte::with([
            'planification.abonnement.user',   // client → user
            'pesages',                         // pluriel cohérent
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('planification.abonnement.user', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                ->orWhere('commentaire', 'like', "%{$search}%")
                ->orWhere('statut', 'like', "%{$search}%");
            });
        }

        $collectes = $query->latest()->paginate(15);

        $statuts = [
            'en_cours'         => 'En cours',
            'terminee'         => 'Terminée',
            'arrive_au_centre' => 'Arrivée au centre',
            'pesee'            => 'Pesée',
            'triee'            => 'Triée',
        ];

        return view('agent.collectes.index', compact('collectes', 'statuts'));
    }

    /**
     * Afficher les détails d'une collecte
     */
    public function show(Collecte $collecte)
    {
        $collecte->load([
            'planification.abonnement.user',
            'planification.collecteur',
            'pesages.tries',
        ]);

        // Construction de la timeline
        $timeline = collect();

        // Création
        $timeline->push([
            'type'        => 'creation',
            'title'       => 'Collecte créée',
            'description' => 'La collecte a été planifiée',
            'date'        => $collecte->created_at,
            'icon'        => 'bx-package',
        ]);

        // Départ
        if ($collecte->heure_depart) {
            $timeline->push([
                'type'        => 'depart',
                'title'       => 'Départ du collecteur',
                'description' => 'Le collecteur a commencé la collecte',
                'date'        => $collecte->heure_depart,
                'icon'        => 'bx-play',
            ]);
        }

        // Arrivée
        if ($collecte->heure_fin) {
            $timeline->push([
                'type'        => 'arrivee',
                'title'       => 'Arrivée au centre',
                'description' => 'Le collecteur est arrivé au centre de tri',
                'date'        => $collecte->heure_fin,
                'icon'        => 'bx-check',
            ]);
        }

        // Pesages et Tris
        foreach ($collecte->pesages ?? [] as $pesage) {
            $timeline->push([
                'type'        => 'pesage',
                'title'       => 'Pesage effectué',
                'description' => "Poids : " . ($pesage->poids ?? 0) . " " . ($pesage->unite ?? 'kg'),
                'date'        => $pesage->created_at,
                'icon'        => 'bx-trending-up',
            ]);

            foreach ($pesage->tries ?? [] as $tri) {
                $timeline->push([
                    'type'        => 'tri',
                    'title'       => 'Tri effectué',
                    'description' => "Type : " . ($tri->type_dechet ?? 'N/A') . ", Quantité : " . ($tri->quantite_trier ?? 0) . " " . ($tri->unite ?? 'kg'),
                    'date'        => $tri->created_at,
                    'icon'        => 'bx-filter-alt',
                ]);
            }
        }

        $timeline = $timeline->sortBy('date')->values();

        return view('agent.collectes.show', compact('collecte', 'timeline'));
    }
}