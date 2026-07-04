<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;   // ← Import OBLIGATOIRE

class CollecteurController extends Controller
{
    /**
     * Liste des collecteurs
     */
    public function index()
    {
        $collecteurs = User::where('role', 'collecteur')
            ->with('zone')
            ->latest()
            ->paginate(15);

        return view('admin.collecteurs.index', compact('collecteurs'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $zones = Zone::all();
        return view('admin.collecteurs.create', compact('zones'));
    }

    /**
     * Enregistrement d'un collecteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                  => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email',
            'telephone'            => 'nullable|string|max:20',
            'address'              => 'nullable|string|max:255',
            'zone_id'              => 'nullable|exists:zones,id',
            'password'             => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'       => $validated['nom'],
            'email'      => $validated['email'],
            'telephone'  => $validated['telephone'] ?? null,
            'address'    => $validated['address'] ?? null,
            'zone_id'    => $validated['zone_id'] ?? null,
            'role'       => 'collecteur',
            'statut'     => 'actif',
            'password'   => Hash::make($validated['password']),   // ← Correction ici
        ]);

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur créé avec succès !');
    }

    /**
     * Affichage
     */
    public function show(User $collecteur)
    {
        return view('admin.collecteurs.show', compact('collecteur'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(User $collecteur)
    {
        $zones = Zone::all();
        return view('admin.collecteurs.edit', compact('collecteur', 'zones'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, User $collecteur)
    {
        DB::transaction(function () use ($request, $collecteur) {

            $collecteur->update([
                'name'      => $request->nom,
                'email'     => $request->email,
                'telephone' => $request->telephone,
                'address'   => $request->address,
                'statut'    => $request->statut ?? $collecteur->statut,
                'zone_id'   => $request->zone_id,
                'role'      => 'collecteur',
                'numpermis' => $request->numpermis ?? $collecteur->numpermis,
            ]);

            if ($request->filled('mot_de_passe')) {
                $collecteur->update([
                    'password' => Hash::make($request->mot_de_passe),
                ]);
            }
        });

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur mis à jour avec succès !');
    }

    /**
     * Suppression
     */
    public function destroy(User $collecteur)
    {
        $collecteur->delete();

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur supprimé avec succès !');
    }
}