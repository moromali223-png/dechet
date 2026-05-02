<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockEntreeController extends Controller
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Afficher le formulaire d'entrée de stock
     */
    public function create()
    {
        $stocks = Stock::with('produit')->get();

        return view('stock.entree.create', compact('stocks'));
    }

    /**
     * Enregistrer une entrée de stock
     */
    public function store(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantite' => 'required|numeric|min:0.01',
            'source' => 'required|in:Source du produit,Retour client,Retour de stock,Fournisseur,Ajustement inventaire,Autre',
            'description' => 'nullable|string|max:255',
            'client_nom' => 'nullable|string|max:100', // Nouveau champ optionnel pour le nom du client
        ]);

        try {
            $description = $request->description;
            if ($request->source === 'Retour client' && $request->client_nom) {
                $description = 'Retour du client : '.$request->client_nom.'. '.($description ?? '');
            }

            $this->stockService->entreeStock(
                $request->stock_id,
                $request->quantite,
                $request->source,
                $description
            );

            return redirect()->route('stock.entree.create')
                ->with('success', 'Entrée de stock enregistrée avec succès !');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Erreur lors de l\'enregistrement: '.$e->getMessage()]);
        }
    }
}
