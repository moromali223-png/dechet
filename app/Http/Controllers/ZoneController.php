<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ZoneController extends Controller
{
    /**
     * Afficher la liste des zones.
     */
    public function index(): View
    {
        // Ajout de pagination et eager loading si nécessaire
        $zones = Zone::latest()
            ->paginate(15)
            ->withQueryString(); // Conserve les paramètres de requête

        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create(): View
    {
        // Vérification des permissions (exemple)
        // $this->authorize('create', Zone::class);

        return view('admin.zones.create');
    }

    /**
     * Sauvegarder une nouvelle zone.
     */
    public function store(Request $request): RedirectResponse
    {
        // Vérification des permissions
        // $this->authorize('create', Zone::class);

        // Validation avec messages personnalisés
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:zones,nom',
            'ville' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'nom.required' => 'Le nom de la zone est obligatoire.',
            'nom.unique' => 'Cette zone existe déjà.',
            'ville.required' => 'La ville est obligatoire.',
        ]);

        try {
            $zone = Zone::create($validated);

            return redirect()
                ->route('admin.zones.index')
                ->with('success', "La zone '{$zone->nom}' a été créée avec succès !");

        } catch (\Exception $e) {
            // Log de l'erreur pour le développeur
            Log::error('Erreur création zone : '.$e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la zone.');
        }
    }

    /**
     * Afficher une zone spécifique (optionnel).
     */
    public function show(Zone $zone): View
    {
        // $this->authorize('view', $zone);

        return view('admin.zones.show', compact('zone'));
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Zone $zone): View
    {
        // $this->authorize('update', $zone);

        return view('admin.zones.edit', compact('zone'));
    }

    /**
     * Mettre à jour une zone.
     */
    public function update(Request $request, Zone $zone): RedirectResponse
    {
        // $this->authorize('update', $zone);

        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:zones,nom,'.$zone->id,
            'ville' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $zone->update($validated);

            return redirect()
                ->route('admin.zones.index')
                ->with('success', "La zone '{$zone->nom}' a été mise à jour.");

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour zone : '.$e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Supprimer une zone.
     */
    public function destroy(Zone $zone): RedirectResponse
    {
        // $this->authorize('delete', $zone);

        try {
            $zoneName = $zone->nom;
            $zone->delete();

            return redirect()
                ->route('qdmin.zones.index')
                ->with('success', "La zone '{$zoneName}' a été supprimée.");

        } catch (\Exception $e) {
            Log::error('Erreur suppression zone : '.$e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer cette zone.');
        }
    }
}
