<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Trie;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index(Request $request)
    {
        // Grouper les tris par type de déchet pour obtenir les matières premières disponibles
        $matieres = Trie::select('type_dechet', 'unite')
            ->selectRaw('SUM(quantite_trier) as quantite_totale')
            ->selectRaw('COUNT(*) as nombre_tries')
            ->groupBy('type_dechet', 'unite')
            ->get()
            ->map(function ($matiere) {
                // Calculer la quantité utilisée (dans les produits - à implémenter selon logique métier)
                $quantite_utilisee = 0; // TODO: Calculer depuis les produits fabriqués

                return [
                    'type_dechet' => $matiere->type_dechet,
                    'quantite_totale' => $matiere->quantite_totale,
                    'quantite_utilisee' => $quantite_utilisee,
                    'quantite_restante' => $matiere->quantite_totale - $quantite_utilisee,
                    'unite' => $matiere->unite,
                    'disponibilite' => ($matiere->quantite_totale - $quantite_utilisee) > 0 ? 'disponible' : 'epuisee',
                ];
            });

        // Statistiques
        $stats = [
            'total_matieres' => $matieres->count(),
            'quantite_totale' => $matieres->sum('quantite_totale'),
            'matieres_disponibles' => $matieres->where('disponibilite', 'disponible')->count(),
            'matieres_epuisees' => $matieres->where('disponibilite', 'epuisee')->count(),
        ];

        return view('agent.matieres.index', compact('matieres', 'stats'));
    }

    public function show($type_dechet)
    {
        $tries = Trie::where('type_dechet', $type_dechet)
            ->with(['pesage.collecte.planification.client'])
            ->latest()
            ->get();

        $stats = [
            'quantite_totale' => $tries->sum('quantite_trier'),
            'nombre_tries' => $tries->count(),
            'derniere_entree' => $tries->first()?->created_at,
        ];

        return view('agent.matieres.show', compact('type_dechet', 'tries', 'stats'));
    }
}
