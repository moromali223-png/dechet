<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    public function index()
    {
        $agents = User::where('role', 'agent')
            ->latest()
            ->paginate(15);

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string',
            'mot_de_passe' => 'required|min:8|confirmed',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => bcrypt($request->mot_de_passe),
            'role' => 'agent',
            'statut' => 'actif',
            'address' => $request->address,
        ]);

        return redirect()->route('agents.index')
            ->with('success', 'Agent créé avec succès !');
    }

    public function show(User $agent)
    {
        return view('admin.agents.show', compact('agent'));
    }

    public function edit(User $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'telephone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $agent->update([
            'name' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'address' => $request->address,
            'role' => 'agent',
        ]);

        if ($request->filled('mot_de_passe')) {
            $agent->update([
                'password' => bcrypt($request->mot_de_passe),
            ]);
        }

        return redirect()->route('agents.index')
            ->with('success', 'Agent mis à jour avec succès !');
    }

    public function destroy(User $agent)
    {
        $agent->delete();

        return redirect()->route('agents.index')
            ->with('success', 'Agent supprimé avec succès !');
    }
}