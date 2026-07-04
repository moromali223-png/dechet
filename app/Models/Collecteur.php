<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CollecteurController extends Controller
{
    public function index()
    {
        $collecteurs = User::with('zone')
            ->where('role', 'collecteur')
            ->latest()
            ->paginate(15);

        return view('admin.collecteurs.index', compact('collecteurs'));
    }

    public function create()
    {
        $zones = Zone::all();
        return view('admin.collecteurs.create', compact('zones'));
    }

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
            'role' => 'collecteur',
            'statut' => 'actif',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur créé avec succès.');
    }

    public function show(User $collecteur)
    {
        return view('admin.collecteurs.show', compact('collecteur'));
    }

    public function edit(User $collecteur)
    {
        $zones = Zone::all();
        return view('admin.collecteurs.edit', compact('collecteur', 'zones'));
    }

    public function update(Request $request, User $collecteur)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $collecteur->id,
            'telephone' => 'required|string|unique:users,telephone,' . $collecteur->id,
            'address' => 'nullable|string',
            'zone_id' => 'nullable|exists:zones,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $collecteur->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'address' => $validated['address'] ?? null,
            'zone_id' => $validated['zone_id'] ?? null,
            'role' => 'collecteur',
        ]);

        if (!empty($validated['password'])) {
            $collecteur->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur mis à jour avec succès.');
    }

    public function destroy(User $collecteur)
    {
        $collecteur->delete();

        return redirect()->route('collecteurs.index')
            ->with('success', 'Collecteur supprimé avec succès.');
    }
}