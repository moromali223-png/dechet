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
        ?float $prixUnitaireBatch = null,
        string $source = 'production',
        string $description = null
    ) {
        return DB::transaction(function () use (
            $stockId,
            $quantite,
            $prixUnitaireBatch,
            $source,
            $description
        ) {

            $stock = Stock::findOrFail($stockId);

            return $stock->addQuantity(
                $quantite,
                $prixUnitaireBatch ?? $stock->prix_unitaire,
                $source,
                $description
            );
        });
    }
}