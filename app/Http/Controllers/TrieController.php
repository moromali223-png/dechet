<?php

namespace App\Http\Controllers;

use App\Models\Pesage;
use App\Models\Trie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrieController extends Controller
{
    public function index(): View
    {
        $tries = Trie::with('pesage')->latest()->paginate(15);

        return view('trie.index', compact('tries'));
    }

    public function create(): View
    {
        $pesages = Pesage::all();

        return view('trie.create', compact('pesages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pesage_id' => 'required|exists:pesage,id',
            'type_dechet' => 'required|string|max:100',
            'quantite_trier' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
        ]);

        Trie::create($validated);

        return redirect()->route('tries.index')
            ->with('success', 'Tri ajouté avec succès');
    }

    public function show(Trie $tri): View
    {
        $tri->load('pesage');

        return view('trie.show', compact('tri'));
    }

    public function edit(Trie $tri): View
    {
        $pesages = Pesage::all();

        return view('trie.edit', compact('tri', 'pesages'));
    }

    public function update(Request $request, Trie $tri): RedirectResponse
    {
        $validated = $request->validate([
            'pesage_id' => 'required|exists:pesage,id',
            'type_dechet' => 'required|string|max:100',
            'quantite_trier' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
        ]);

        $tri->update($validated);

        return redirect()->route('tries.index')
            ->with('success', 'Tri mis à jour avec succès');
    }

    public function destroy(Trie $tri): RedirectResponse
    {
        $tri->delete();

        return redirect()->route('tries.index')
            ->with('success', 'Tri supprimé avec succès');
    }
}
