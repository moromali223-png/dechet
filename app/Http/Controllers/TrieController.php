<?php

namespace App\Http\Controllers;

use App\Models\Pesage;
use App\Models\Trie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrieController extends Controller
{
    /**
     * Liste des tris
     */
    public function index(): View
    {
        $tries = Trie::with('pesage')
            ->latest()
            ->paginate(15);

        return view('trie.index', compact('tries'));
    }

    /**
     * Formulaire création
     */
    public function create(): View
    {
        $pesages = Pesage::latest()->get();

        return view('trie.create', compact('pesages'));
    }

    /**
     * Enregistrement
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pesage_id' => 'required|exists:pesage,id',
            'type_dechet' => 'required|string|max:100',
            'quantite_trier' => 'required|numeric|min:0',
            'unite' => 'required|in:kg,g,T,L',
            'qualite' => 'required|in:Excellent,Bon,Moyen,Mauvais',
            'destination' => 'nullable|in:Recyclé,Revendu,Stocké,Déchet final',
            'valeur_estimee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        Trie::create($validated);

        return redirect()
            ->route('tries.index')
            ->with('success', 'Tri ajouté avec succès.');
    }

    /**
     * Supprimer un tri
     */
    public function destroy(Trie $tri): RedirectResponse
    {
        $tri->delete();

        return redirect()
            ->route('tries.index')
            ->with('success', 'Tri supprimé avec succès.');
    }

    /**
     * Afficher un tri
     */
    public function show(Trie $tri): View
    {
        return view('trie.show', compact('tri'));
    }

    /**
     * Formulaire modification
     */
    public function edit(Trie $tri): View
    {
        $pesages = Pesage::latest()->get();

        return view('trie.edit', compact('tri', 'pesages'));
    }

    public function update(Request $request, Trie $tri): RedirectResponse
    {
        $validated = $request->validate([
            'pesage_id' => 'required|exists:pesage,id',
            'type_dechet' => 'required|string|max:100',
            'quantite_trier' => 'required|numeric|min:0',
            'unite' => 'required|in:kg,g,T,L',
            'qualite' => 'required|in:Excellent,Bon,Moyen,Mauvais',
            'destination' => 'nullable|in:Recyclé,Revendu,Stocké,Déchet final',
            'valeur_estimee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tri->update($validated);

        return redirect()
            ->route('tries.index')
            ->with('success', 'Tri modifié avec succès.');
    }
}
