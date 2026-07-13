<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\Trie;   // ← Ajoute cette ligne
class ProduitController extends Controller
{
    /**
     * LISTE PRODUITS
     */
    public function index(Request $request)
    {
        $query = Produit::query();

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
        return view('agent.produits.create');
    }

    /**
     * STORE
     */
 

public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'unite_mesure' => 'required|string|max:50',
        'description' => 'nullable|string',
        'prix_unitaire' => 'nullable|numeric|min:0',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')
            ->store('produits', 'public');
    }

    Produit::create([
        'nom' => $validated['nom'],
        'type' => $validated['type'],
        'unite_mesure' => $validated['unite_mesure'],
        'description' => $validated['description'] ?? null,
        'prix_unitaire' => $validated['prix_unitaire'] ?? 0,
        'photo' => $validated['photo'] ?? null,
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
        return view('agent.produits.show', compact('produit'));
    }

    /**
     * EDIT
     */
    /**
 * EDIT
 */
public function edit(Produit $produit)
{
    return view('agent.produits.edit', compact('produit'));
}
    /**
     * UPDATE
     */
    /**
 * UPDATE
 */
/**
 * UPDATE
 */
public function update(Request $request, Produit $produit)
{
    $validated = $request->validate([
        'nom'           => 'required|string|max:255',
        'unite_mesure'  => 'required|string|max:50',
        'prix_unitaire' => 'nullable|numeric|min:0',
        'statut'        => 'required|in:en_production,termine,stocke',
        'description'   => 'nullable|string|max:1000',
    ]);

    $produit->update($validated);

    return redirect()
        ->route('agent.produits.index')
        ->with('success', 'Produit mis à jour avec succès.');
}

    /**
     * DELETE
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()
            ->route('agent.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}