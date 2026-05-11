<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Pesage;
use Illuminate\Http\Request;

class PesageController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesage::with(['collecte.planification.abonnement.client']);

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('client')) {
            $query->whereHas('collecte.planification.abonnement.client', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->client . '%');
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhereHas('collecte.planification.abonnement.client', function ($subQ) use ($search) {
                      $subQ->where('nom', 'like', '%' . $search . '%');
                  });
            });
        }

        $pesages = $query->latest()->paginate(15);

        $stats = [
            'total_pesages' => Pesage::count(),
            'poids_total' => Pesage::sum('poids'),
            'pesages_today' => Pesage::whereDate('created_at', today())->count(),
            'poids_today' => Pesage::whereDate('created_at', today())->sum('poids'),
        ];

        return view('agent.pesages.index', compact('pesages', 'stats'));
    }

    public function create()
    {
        $collectes = \App\Models\Collectes::with('planification.abonnement.client')
            ->whereDoesntHave('pesages')
            ->orWhereHas('pesages', function ($query) {
                $query->where('statut', '!=', 'termine');
            })
            ->get();

        return view('agent.pesages.create', compact('collectes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_collecte' => 'required|exists:collectes,id',
            'poids' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
            'description' => 'nullable|string|max:255',
        ]);

        Pesage::create([
            'id_collecte' => $request->id_collecte,
            'poids' => $request->poids,
            'unite' => $request->unite,
            'description' => $request->description,
            'statut' => 'termine',
        ]);

        return redirect()->route('agent.pesages.index')
            ->with('success', 'Pesage enregistré avec succès.');
    }

    public function show(Pesage $pesage)
    {
        $pesage->load([
            'collecte.planification.abonnement.client',
            'tries'
        ]);

        return view('agent.pesages.show', compact('pesage'));
    }

    public function edit(Pesage $pesage)
    {
        return view('agent.pesages.edit', compact('pesage'));
    }

    public function update(Request $request, Pesage $pesage)
    {
        $request->validate([
            'poids' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
            'description' => 'nullable|string|max:255',
            'statut' => 'required|string|in:en_cours,termine',
        ]);

        $pesage->update(
            $request->only(['poids', 'unite', 'description', 'statut'])
        );

        return redirect()->route('agent.pesages.index')
            ->with('success', 'Pesage mis à jour avec succès.');
    }

    public function destroy(Pesage $pesage)
    {
        $pesage->delete();

        return redirect()->route('agent.pesages.index')
            ->with('success', 'Pesage supprimé avec succès.');
    }
}