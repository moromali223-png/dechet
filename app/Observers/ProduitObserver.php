<?php

namespace App\Observers;

use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Support\Str;

class ProduitObserver
{
    /**
     * CREATE → entrée stock
     */
    public function created(Produit $produit): void
    {
        $stock = Stock::where('nom', $produit->nom)->first();

        if ($stock) {
            // Produit existe déjà en stock, on augmente la quantité
            $ancienneQuantite = $stock->quantite_disponible;
            $ancienPrix = $stock->prix_unitaire;
            $ancienneValeur = $ancienneQuantite * $ancienPrix;
            $nouvelleValeur = $produit->quantite * $produit->prix_unitaire;
            $totalQuantite = $ancienneQuantite + $produit->quantite;
            $nouveauPrix = $totalQuantite > 0 ? ($ancienneValeur + $nouvelleValeur) / $totalQuantite : $produit->prix_unitaire;

            $stock->update([
                'quantite_disponible' => $totalQuantite,
                'prix_unitaire' => $nouveauPrix,
            ]);
        } else {
            // Premier produit de ce nom, créer le stock
            Stock::create([
                'code_stock' => 'STK-'.strtoupper(Str::random(6)),
                'nom' => $produit->nom,
                'quantite_disponible' => $produit->quantite,
                'prix_unitaire' => $produit->prix_unitaire,
                'unite_mesure' => $produit->unite_mesure,
                'seuil_alerte' => 10,
                'produit_id' => $produit->id,
            ]);
        }
    }

    /**
     * UPDATE → synchronisation stock
     */
    public function updated(Produit $produit): void
    {
        // Trouver le stock par nom actuel
        $stock = Stock::where('nom', $produit->nom)->first();

        if ($stock) {
            // Mettre à jour les informations du stock
            $stock->update([
                'quantite_disponible' => $produit->quantite, // ou ajuster selon logique
                'prix_unitaire' => $produit->prix_unitaire,
                'unite_mesure' => $produit->unite_mesure,
            ]);
        }
    }

    /**
     * DELETE → retrait stock propre
     */
    public function deleted(Produit $produit): void
    {
        $stock = Stock::where('nom', $produit->nom)->first();

        if ($stock) {
            // Décrémenter la quantité au lieu de supprimer le stock
            $nouvelleQuantite = max(0, $stock->quantite_disponible - $produit->quantite);
            $stock->update(['quantite_disponible' => $nouvelleQuantite]);

            // Si quantité devient 0, peut-être supprimer ou laisser
            if ($nouvelleQuantite == 0) {
                $stock->delete();
            }
        }
    }
}
