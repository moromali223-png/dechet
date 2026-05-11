<?php

namespace App\Http\Controllers;

use App\Models\Agents;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = Agents::with('user')->latest()->paginate(15);

        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.agents.create');
    }

    /**
     * Store a newly created resource in storage.
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
                'role' => 'agent',
                'address' => $request->address,
            ]);

            $lastAgent = Agents::latest()->first();
            $number = $lastAgent ? $lastAgent->id + 1 : 1;
            $matricule = 'AGT-'.str_pad($number, 4, '0', STR_PAD_LEFT);
            // Création de l'agent lié
            Agents::create([
                'user_id' => $user->id,
                'matricul' => $matricule,
                'qualification' => $request->qualification,
            ]);
        });

        return redirect()->route('admin.agents.index')->with('success', 'Agent créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agents $agent)
    {
        $agent->load('user'); // relation

        return view('admin.agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agents $agent)
    {
        $agent->load('user');

        return view('admin.agents.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agents $agent)   // ← change ici aussi
    {
        DB::transaction(function () use ($request, $agent) {
            $agent->user->update([
                'name' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'statut' => $request->statut ?? $agent->user->statut,
                'address' => $request->address,
            ]);

            $agent->update([
                'matricul' => $request->matricul,
                'qualification' => $request->qualification,
            ]);

            if ($request->filled('mot_de_passe')) {
                $agent->user->update([
                    'password' => bcrypt($request->mot_de_passe),
                ]);
            }
        });

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agents $agent)
    {
        DB::transaction(function () use ($agent) {
            // Supprimer le modèle agent d'abord
            $agent->delete();

            // Puis supprimer l'utilisateur lié
            if ($agent->user) {
                $agent->user->delete();
            }
        });

        return redirect()->route('admin.agents.index')->with('success', 'Agent supprimé avec succès !');
    }
}
