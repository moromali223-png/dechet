<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Trie;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * LISTE PRODUITS
     */
    public function index(Request $request)
    {
        $query = Produit::with('trie');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        $produits = $query->latest()->paginate(15);

        $stats = [
            'total_produits' => Produit::count(),
            'produits_recents' => Produit::whereDate(
                'created_at',
                '>=',
                now()->subDays(30)
            )->count(),
        ];

        return view('agent.produits.index', compact('produits', 'stats'));
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        $tries = Trie::latest()->get();

        return view('agent.produits.create', compact('tries'));
    }

    /**
     * STORE → Observer gère stock automatiquement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'trie_id' => 'required|exists:tries,id',
            'quantite' => 'required|numeric|min:0.01',
            'type' => 'required|string|max:255',
            'unite_mesure' => 'required|string|max:50',
            'description' => 'nullable|string',
            'prix_unitaire' => 'nullable|numeric|min:0',
        ]);

        Produit::create([
            'nom' => $request->nom,
            'trie_id' => $request->trie_id,
            'quantite' => $request->quantite,
            'type' => $request->type,
            'unite_mesure' => $request->unite_mesure,
            'description' => $request->description,
            'prix_unitaire' => $request->prix_unitaire ?? 0,
            'statut' => 'actif',
        ]);

        return redirect()
            ->route('agent.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * SHOW
     */
    public function show(Produit $produit)
    {
        $produit->load('trie');

        return view('agent.produits.show', compact('produit'));
    }

    /**
     * EDIT
     */
    public function edit(Produit $produit)
    {
        $tries = Trie::latest()->get();

        return view('agent.produits.edit', compact('produit', 'tries'));
    }

    /**
     * UPDATE → Observer synchronise stock
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'trie_id' => 'required|exists:tries,id',
            'quantite' => 'required|numeric|min:0',
            'unite_mesure' => 'required|string|max:50',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'statut' => 'required|in:actif,termine,stocke',
        ]);

        $produit->update($validated);

        return redirect()
            ->route('agent.produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * DELETE → Observer supprime stock
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()
            ->route('agent.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}