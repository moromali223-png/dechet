<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Zone;
use DB;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with(['user', 'zone'])->latest()->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::all();

        return view('admin.clients.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nom,
                'email' => $request->email,
                'password' => bcrypt($request->mot_de_passe),
                'telephone' => $request->telephone,
                'statut' => $request->statut ?? 'actif',
                'role' => 'client',
                'address' => $request->address,

            ]);
            // creation du client lié
            Client::create([
                'user_id' => $user->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'typeclient' => $request->typeclient,
                'zone_id' => $request->zone_id,
            ]);
        });

        return redirect()->route('admin.clients.index')->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['user', 'zone']);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $zones = Zone::all();

        return view('admin.clients.edit', compact('client', 'zones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:25',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'typeclient' => 'nullable|string|max:255',
            'zone_id' => 'nullable|exists:zones,id',
            'mot_de_passe' => 'nullable|string|min:8|confirmed',
        ]);

        $client->user->update([
            'name' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'address' => $request->address,
        ]);

        // Mise à jour du client lié
        $client->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'typeclient' => $request->typeclient,
            'zone_id' => $request->zone_id,
        ]);

        if ($request->filled('mot_de_passe')) {
            $client->user->update([
                'password' => bcrypt($request->mot_de_passe),
            ]);
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        DB::transaction(function () use ($client) {
            $client->user->delete();
            $client->delete();
        });
    }
}
