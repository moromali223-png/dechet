<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::actif()
            ->latest()
            ->paginate(15);

        return view('produits.index', compact('produits'));
    }

    public function create()
    {
        return view('produits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'unite_mesure' => 'required|string|max:20',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'required|in:actif,inactif',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('produits', 'public');
        }

        Produit::create($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produit $produit)
    {
        return view('produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        return view('produits.edit', compact('produit'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'unite_mesure' => 'required|string|max:20',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'required|in:actif,inactif',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {

            if ($produit->photo &&
                Storage::disk('public')->exists($produit->photo)) {

                Storage::disk('public')->delete($produit->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('produits', 'public');
        }

        $produit->update($validated);

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit)
    {
        if ($produit->photo &&
            Storage::disk('public')->exists($produit->photo)) {

            Storage::disk('public')->delete($produit->photo);
        }

        $produit->delete();

        return redirect()
            ->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}