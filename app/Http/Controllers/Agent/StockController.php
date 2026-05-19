<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Trie;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * LISTE STOCKS
     */
    public function index(Request $request)
    {
        $query = Stock::with('produit');

        /**
         * SEARCH
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhereHas('produit', function ($p) use ($search) {
                        $p->where('nom', 'like', "%{$search}%");
                    });
            });
        }

        /**
         * ALERT FILTER
         */
        if ($request->filled('alerte')) {
            $query->whereColumn('quantite_disponible', '<=', 'seuil_alerte');
        }

        $stocks = $query->orderByDesc('quantite_disponible')
            ->paginate(20);

        /**
         * STATISTIQUES
         */
        $totalProduits = Stock::count();

        $stockTotal = Stock::sum('quantite_disponible');

        /**
         * VALEUR STOCK (SAFE VERSION)
         */
        $valeurTotale = Stock::get()->sum(function ($stock) {
            return ($stock->quantite_disponible ?? 0)
                 * ($stock->prix_unitaire ?? 0);
        });

        /**
         * ALERTES
         */
        $produitsEnAlerte = Stock::whereColumn(
            'quantite_disponible',
            '<=',
            'seuil_alerte'
        )->count();

        return view('agent.stocks.index', compact(
            'stocks',
            'totalProduits',
            'stockTotal',
            'valeurTotale',
            'produitsEnAlerte'
        ));
    }

    /**
     * DETAIL STOCK
     */
    public function show($type)
    {
        $tries = Trie::where('type_dechet', $type)
            ->latest()
            ->get();

        if ($tries->isEmpty()) {
            abort(404);
        }

        $quantiteTotale = $tries->sum('quantite_trier');

        return view('agent.matieres.show', compact(
            'tries',
            'type',
            'quantiteTotale'
        ));
    }

    private function getStatusColor($statut)
    {
        return match ($statut) {
            'terminee' => 'success',
            'en_cours' => 'warning',
            'annulee' => 'danger',
            default => 'secondary',
        };
    }
}
