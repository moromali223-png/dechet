<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Collecte;     // ← Singulier
use App\Models\User;         // Le client est maintenant un User
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function show(User $client)
    {
        // Vérification de sécurité : le collecteur ne voit que les clients de ses tournées
        $user = Auth::user();

        if ($user->role !== 'collecteur') {
            abort(403, 'Accès refusé.');
        }

        $client->load(['zone']);

        $collectes = Collecte::with([
            'planification.zone',
            'planification.abonnement'
        ])
        ->whereHas('planification.abonnement', function ($q) use ($client) {
            $q->where('user_id', $client->id);   // ← Correction importante
        })
        ->latest()
        ->paginate(10);

        return view('collecteur.client.show', compact('client', 'collectes'));
    }
}