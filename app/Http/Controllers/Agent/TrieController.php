<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Pesage;
use App\Models\Trie;
use Illuminate\Http\Request;

class TrieController extends Controller
{
    public function index(Request $request)
    {
        $query = Trie::with(['pesage.collecte.planification.abonnement.client']);

        // Filtres
        if ($request->filled('type_dechet')) {
            $query->where('type_dechet', $request->type_dechet);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $tries = $query->latest()->paginate(15);

        $types_dechets = Trie::distinct('type_dechet')->pluck('type_dechet');

        $stats = [
            'total_tries' => Trie::count(),
            'quantite_totale' => Trie::sum('quantite_trier'),
            'tries_today' => Trie::whereDate('created_at', today())->count(),
        ];

        return view('agent.tries.index', compact('tries', 'types_dechets', 'stats'));
    }

    public function create()
    {
        $pesages = Pesage::with('collecte.planification.abonnement.client')
            ->whereDoesntHave('tries')
            ->get();

        return view('agent.tries.create', compact('pesages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesage_id' => 'required|exists:pesage,id',
            'type_dechet' => 'required|string|max:255',
            'quantite_trier' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
            'qualite' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Trie::create($request->all());

        return redirect()->route('agent.tries.index')
            ->with('success', 'Tri enregistré avec succès.');
    }

    public function show(Trie $tri)
    {
        $tri->load(['pesage.collecte.planification.abonnement.client']);

        return view('agent.tries.show', compact('tri'));
    }

    public function edit(Trie $tri)
    {
        return view('agent.tries.edit', compact('tri'));
    }

    public function update(Request $request, Trie $tri)
    {
        $request->validate([
            'type_dechet' => 'required|string|max:255',
            'quantite_trier' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
            'qualite' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $tri->update($request->all());

        return redirect()->route('agent.tries.index')
            ->with('success', 'Tri mis à jour avec succès.');
    }

    public function destroy(Trie $tri)
    {
        $tri->delete();

        return redirect()->route('agent.tries.index')
            ->with('success', 'Tri supprimé avec succès.');
    }
}