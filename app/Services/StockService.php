<?php

namespace App\Services;

use App\Models\Mouvement;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Entrée en stock
     */
    public function entreeStock(
        int $stockId,
        float $quantite,
        float $prixUnitaireBatch,
        string $type = 'production',
        string $description = null
    ) {
        return DB::transaction(function () use (
            $stockId,
            $quantite,
            $prixUnitaireBatch,
            $type,
            $description
        ) {

            $stock = Stock::findOrFail($stockId);

            // Anciennes valeurs
            $ancienneQuantite = $stock->quantite_disponible;
            $ancienPrix       = $stock->prix_unitaire;

            // Valeur ancien stock
            $ancienneValeur = $ancienneQuantite * $ancienPrix;
            $nouvelleValeur = $prixUnitaireBatch * $quantite;

            // Nouvelle quantité totale
            $quantiteTotale = $ancienneQuantite + $quantite;

            // Nouveau Prix Moyen Pondéré (Sécurité division par zéro)
            $nouveauPrix = $quantiteTotale > 0 
                ? ($ancienneValeur + $nouvelleValeur) / $quantiteTotale 
                : $prixUnitaireBatch;

            // Mise à jour stock
            $stock->update([
                'quantite_disponible' => $quantiteTotale,
                'prix_unitaire'       => $nouveauPrix,
            ]);

            // Mouvement
            Mouvement::create([
                'stock_id'        => $stock->id,
                'produit_id'      => $stock->produit_id, // Nécessite l'ajout au fillable de Mouvement
                'type_mouvement'  => 'ENTREE',
                'quantite'        => $quantite,
                'prix_unitaire'   => $prixUnitaireBatch,
                'montant_total'   => $nouvelleValeur,
                'source'          => $type,
                'description'     => $description,
                'date_mouvement'  => now()->toDateString(),
                'heure_mouvement' => now()->toTimeString(),
                'user_id'         => Auth::id(),
            ]);

            return $stock;
        });
    }
}