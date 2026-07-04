<?php

namespace App\Http\Controllers\Collecteur;

use App\Http\Controllers\Controller;
use App\Models\Planification;
use Illuminate\Support\Facades\Auth;

class TourneeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Vérification du rôle
        if ($user->role !== 'collecteur') {
            abort(403, 'Accès refusé. Vous devez être collecteur.');
        }

        $tournees = Planification::with([
            'zone',
            'declaration.user',           // Client via déclaration
            'abonnement.user',            // Client via abonnement
            'collecteur',                 // Le collecteur (User)
            'agent',                      // Agent assigné
            'collecte'                    // Si la collecte a été faite
        ])
        ->where('collecteur_id', $user->id)   // Directement sur user_id
        ->whereDate('date_prevue', today())
        ->orderBy('ordre_passage')
        ->get();

        return view('collecteur.tournees.index', compact('tournees'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $planification = Planification::with([
            'zone',
            'collecteur',
            'agent',
            'abonnement.user',
            'declaration.user',
            'collecte',
            'collecte.pesages'
        ])
        ->where('collecteur_id', $user->id)   // Sécurité : seulement ses tournées
        ->findOrFail($id);

        return view('collecteur.tournees.show', compact('planification'));
    }
}