<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Trie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('trie')
            ->actif()
            ->latest()
            ->paginate(15);

        return view('produits.index', compact('produits'));
    }

    public function create()
    {
        $tries = Trie::all();

        return view('produits.create', compact('tries'));
    }

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('produits', 'public');
        }

        Produit::create($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produit $produit)
    {
        $produit->load('trie');

        return view('produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        $tries = Trie::all();

        return view('produits.edit', compact('produit', 'tries'));
    }

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {

            if ($produit->photo && Storage::disk('public')->exists($produit->photo)) {
                Storage::disk('public')->delete($produit->photo);
            }

            $validated['photo'] = $request->file('photo')->store('produits', 'public');
        }

        $produit->update($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit)
    {
        if ($produit->photo && Storage::disk('public')->exists($produit->photo)) {
            Storage::disk('public')->delete($produit->photo);
        }

        $produit->delete();

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}