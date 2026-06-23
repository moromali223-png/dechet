<?php

namespace App\Observers;

use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Support\Str;

class ProduitObserver
{
    /**
     * UPDATE
     */
    public function updated(Produit $produit): void
    {
        $stock = Stock::where('produit_id', $produit->id)
            ->first();

        if ($stock) {
            $stock->update([
                'nom'           => $produit->nom,
                'prix_unitaire' => $produit->prix_unitaire,
                'unite_mesure'  => $produit->unite_mesure,
            ]);
        }
    }

    /**
     * DELETE
     */
    public function deleted(Produit $produit): void
    {
        $stock = Stock::where('produit_id', $produit->id)
            ->first();

        if ($stock) {

            /*
            |--------------------------------------------------------------------------
            | Supprimer stock lié
            |--------------------------------------------------------------------------
            */

            $stock->delete();
        }
    }
}