<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Collectes;

class ClientController extends Controller
{
    public function show(Client $client)
    {
        $client->load('user', 'zone');

        $collectes = Collectes::whereHas('planification.abonnement', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })
        ->latest()
        ->paginate(10);

        return view('collecteur.client.show', compact('client', 'collectes'));
    }
}