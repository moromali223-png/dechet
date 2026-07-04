<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Liste des clients
     */
    public function index()
    {
        $clients = User::with('zone')
            ->where('role', 'client')
            ->latest()
            ->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        $zones = Zone::all();

        return view('admin.clients.create', compact('zones'));
    }

    /**
     * Enregistrer client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|unique:users,telephone',
            'address' => 'nullable|string',
            'zone_id' => 'nullable|exists:zones,id',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'address' => $validated['address'] ?? null,
            'zone_id' => $validated['zone_id'] ?? null,
            'role' => 'client',
            'statut' => 'actif',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Afficher un client
     */
    public function show(User $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Formulaire édition
     */
    public function edit(User $client)
    {
        $zones = Zone::all();

        return view('admin.clients.edit', compact('client', 'zones'));
    }

    /**
     * Mise à jour client
     */
    public function update(Request $request, User $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->id,
            'telephone' => 'required|string|unique:users,telephone,' . $client->id,
            'address' => 'nullable|string',
            'zone_id' => 'nullable|exists:zones,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $client->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'address' => $validated['address'] ?? null,
            'zone_id' => $validated['zone_id'] ?? null,
            'role' => 'client',
        ]);

        if (!empty($validated['password'])) {
            $client->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Supprimer client
     */
    public function destroy(User $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}