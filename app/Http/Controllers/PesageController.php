<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Pesage;   // ← Important : on utilise Collectes
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PesageController extends Controller
{
    /**
     * Afficher la liste des pesages.
     */
    public function index(): View
    {
        $pesages = Pesage::with('collecte')   // relation maintenant définie
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pesages.index', compact('pesages'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create(): View
    {
        $collectes = Collecte::all();

        return view('pesages.create', compact('collectes'));
    }

    /**
     * Sauvegarder un nouveau pesage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_collecte' => 'required|exists:collectes,id',
            'poids' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
            'description' => 'nullable|string',
            'statut' => 'required|string|max:50',
        ]);

        try {
            Pesage::create($validated);

            return redirect()
                ->route('pesages.index')
                ->with('success', 'Pesage enregistré avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur création pesage : '.$e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement.');
        }
    }

    /**
     * Afficher les détails d'un pesage.
     */
    public function show(Pesage $pesage): View
    {
        $pesage->load('collecte');   // Chargement de la relation

        return view('pesages.show', compact('pesage'));
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Pesage $pesage): View
    {
        $collectes = Collecte::all();

        return view('pesages.edit', compact('pesage', 'collectes'));
    }

    /**
     * Mettre à jour un pesage.
     */
    public function update(Request $request, Pesage $pesage): RedirectResponse
    {
        $validated = $request->validate([
            'id_collecte' => 'required|exists:collectes,id',
            'poids' => 'required|numeric|min:0',
            'unite' => 'required|string|max:10',
            'description' => 'nullable|string',
            'statut' => 'required|string|max:50',
        ]);

        try {
            $pesage->update($validated);

            return redirect()
                ->route('pesages.index')
                ->with('success', 'Pesage mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour pesage : '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Supprimer un pesage.
     */
    public function destroy(Pesage $pesage): RedirectResponse
    {
        $pesage->delete();

        return redirect()
            ->route('pesages.index')
            ->with('success', 'Pesage supprimé avec succès.');
    }
}
