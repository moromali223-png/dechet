<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Stock;
use App\Models\Trie;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Liste des produits
     */
    public function index()
    {
        $produits = Produit::with('trie')
            ->actif()
            ->latest()
            ->paginate(15);

        return view('produits.index', compact('produits'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $tries = Trie::all();

        return view('produits.create', compact('tries'));
    }

    /**
     * Enregistrement produit + gestion stock
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'unite_mesure' => 'required|string|max:20',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'in:actif,inactif',
            'trie_id' => 'required|exists:tries,id',
        ]);

        DB::transaction(function () use ($validated) {
            // 🔹 1. Création produit (historique de production)
            $produit = Produit::create($validated);

            // 🔹 2. Vérifier si stock existe (par nom)
            $stock = Stock::where('nom', $validated['nom'])->first();

            if ($stock) {
                // ✅ Utilisation du service pour la traçabilité (mouvements)
                $this->stockService->entreeStock(
                    $stock->id,
                    $validated['quantite'],
                    'production',
                    'Entrée issue de la production : '.$produit->nom
                );
            } else {
                // ✅ CRÉATION INITIALE DU STOCK
                $stock = Stock::create([
                    'code_stock' => 'STK-'.strtoupper(Str::random(5)),
                    'nom' => $validated['nom'],
                    'quantite_disponible' => 0, // Initialisé à 0 pour laisser le service gérer l'entrée
                    'prix_unitaire' => $validated['prix_unitaire'],
                    'unite_mesure' => $validated['unite_mesure'],
                    'seuil_alerte' => 10,
                    'produit_id' => $produit->id,
                    'commande_id' => null,
                ]);

                // ✅ Enregistrement du mouvement initial via le service
                if ($validated['quantite'] > 0) {
                    $this->stockService->entreeStock(
                        $stock->id,
                        $validated['quantite'],
                        'production',
                        'Stock initial issu de la production : '.$produit->nom
                    );
                }
            }
        });

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Formulaire modification
     */
    public function edit(Produit $produit)
    {
        $tries = Trie::all();

        return view('produits.edit', compact('produit', 'tries'));
    }

    /**
     * Mise à jour produit
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'unite_mesure' => 'required|string|max:20',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'in:actif,inactif',
            'trie_id' => 'required|exists:tries,id',
        ]);

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Suppression produit
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}
