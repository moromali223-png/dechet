<?php

namespace App\Http\Controllers;

use App\Models\Collecteur;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollecteurController extends Controller
{
    /**
     * Afficher la liste des collecteurs avec pagination
     */
    public function index()
    {
        $collecteurs = Collecteur::with(['user', 'zone'])
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
     * Enregistrer un nouveau collecteur et son utilisateur
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Création de l'utilisateur
            $user = User::create([
                'name' => $request->nom,
                'email' => $request->email,
                'password' => bcrypt($request->mot_de_passe),
                'telephone' => $request->telephone,
                'statut' => $request->statut ?? 'actif',
                'role' => 'collecteur',
                'address' => $request->address,
            ]);
            $lastCollecteur = Collecteur::latest()->first();
            $number = $lastCollecteur ? $lastCollecteur->id + 1 : 1;
            $matricules = 'COL-'.str_pad($number, 4, '0', STR_PAD_LEFT);

            // Création du collecteur lié
            Collecteur::create([
                'user_id' => $user->id,
                'numpermis' => $request->numpermis,
                'matricul' => $matricules,
                'zone_id' => $request->zone_id,
            ]);

        });

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur créé avec succès !');
    }

    /**
     * Afficher un collecteur
     */
    public function show(Collecteur $collecteur)
    {
        $collecteur->load(['user', 'zone']);

        return view('admin.collecteurs.show', compact('collecteur'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Collecteur $collecteur)
    {
        $zones = Zone::all();

        return view('admin.collecteurs.edit', compact('collecteur', 'zones'));
    }

    /**
     * Mettre à jour un collecteur et son utilisateur
     */
    public function update(Request $request, Collecteur $collecteur)
    {

        DB::transaction(function () use ($request, $collecteur) {
            // Mise à jour de l'utilisateur
            $collecteur->user->update([
                'name' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'address' => $request->address,
                'statut' => $request->statut ?? $collecteur->user->statut,
            ]);

            // Mise à jour du collecteur
            $collecteur->update([
                'numpermis' => $request->numpermis,
                'zone_id' => $request->zone_id,
            ]);

            // Mise à jour du mot de passe si fourni
            if ($request->filled('mot_de_passe')) {
                $collecteur->user->update([
                    'password' => bcrypt($request->mot_de_passe),
                ]);
            }
        });

        return redirect()->route('admin.collecteurs.index')
            ->with('success', 'Collecteur mis à jour avec succès !');
    }

    /**
     * Supprimer un collecteur et son utilisateur
     */
    public function destroy(Collecteur $collecteur)
    {
        DB::transaction(function () use ($collecteur) {
            $collecteur->user->delete(); // supprime l'utilisateur et donc le collecteur si cascade
        });

        return redirect()->route('admin.collecteurs.index')
            ->with('success', 'Collecteur supprimé avec succès !');
    }
}
