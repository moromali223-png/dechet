<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Commande;
use App\Models\Notification;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with([
            'commande.client.user',
            'abonnement.user',
        ])->latest()->paginate(20);

        return view('paiements.index', compact('paiements'));
    }

    public function create()
    {
        $commandes = Commande::latest()->get();
        $abonnements = Abonnement::with('user')->latest()->get();

        return view('paiements.create', compact('commandes', 'abonnements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mode_paiement' => ['required', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0'],
            'type_paiement' => ['nullable', 'string', 'max:255'],
            'reference_paiement' => ['nullable', 'string', 'max:255'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
            'abonnement_id' => ['nullable', 'exists:abonnements,id'],
            'statut' => ['required', 'in:en_attente,valide,echoue'],
        ]);

        $paiement = Paiement::create($validated);

        // Notification
        $commande = $validated['commande_id'] ? Commande::find($validated['commande_id']) : null;
        $commandeLabel = $commande ? "commande {$commande->code_commande}" : 'une commande';

        Notification::record(
            'paiement',
            "Paiement enregistré pour {$commandeLabel} : ".number_format($paiement->montant, 2, ',', ' ').' FCFA.',
            '🔔',
            ['paiement_id' => $paiement->id, 'commande_id' => $validated['commande_id'] ?? null]
        );

        return redirect()->route('paiements.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    // === NOUVELLES MÉTHODES AJOUTÉES ===

    public function show(Paiement $paiement)
    {
        $paiement->load('commande');

        return view('paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement)
    {
        $commandes = Commande::latest()->get();

        return view('paiements.edit', compact('paiement', 'commandes'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([
            'mode_paiement' => ['required', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0'],
            'type_paiement' => ['nullable', 'string', 'max:255'],
            'reference_paiement' => ['nullable', 'string', 'max:255'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
            'abonnement_id' => ['nullable', 'exists:abonnements,id'],
            'statut' => ['required', 'in:en_attente,valide,echoue'],
        ]);
        $paiement->update($validated);

        return redirect()->route('paiements.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();

        return redirect()->route('paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}
