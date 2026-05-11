<?php

namespace App\Http\Controllers;

use App\Models\Stock;

class InventaireController extends Controller
{
    public function index()
    {
        /**
         * STOCKS AVEC PRODUITS
         */
        $stocks = Stock::with('produit')
            ->orderByDesc('quantite_disponible')
            ->paginate(20);

        /**
         * TOTAL PRODUITS (stocks uniques)
         */
        $totalProduits = Stock::count();

        /**
         * QUANTITÉ TOTALE EN STOCK
         */
        $stockTotal = Stock::sum('quantite_disponible');

        /**
         * VALEUR TOTALE DU STOCK (SAFE VERSION)
         */
        $valeurTotale = Stock::get()->sum(function ($stock) {
            return ($stock->quantite_disponible ?? 0) * ($stock->prix_unitaire ?? 0);
        });

        /**
         * PRODUITS EN ALERTE
         */
        $produitsEnAlerte = Stock::whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->count();

        return view('inventaire.index', compact(
            'stocks',
            'totalProduits',
            'stockTotal',
            'valeurTotale',
            'produitsEnAlerte'
        ));
    }
}