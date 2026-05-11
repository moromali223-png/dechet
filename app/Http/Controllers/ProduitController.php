<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Trie;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * LISTE PRODUITS
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
     * FORM CREATE
     */
    public function create()
    {
        $tries = Trie::all();

        return view('produits.create', compact('tries'));
    }

    /**
     * STORE → Observer gère stock automatiquement
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

        Produit::create($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * EDIT
     */
    public function edit(Produit $produit)
    {
        $tries = Trie::all();

        return view('produits.edit', compact('produit', 'tries'));
    }

    /**
     * UPDATE → Observer synchronise stock automatiquement
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

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * DELETE → Observer supprime stock automatiquement
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}