<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class CommandeClientController extends Controller
{
    /**
     * Catalogue des produits disponibles.
     */
    public function produits()
    {
        $collection = Produit::where('statut', 'actif')
            ->whereHas('stock', function ($query) {
                $query->where('quantite_disponible', '>', 0);
            })
            ->with('stock')
            ->latest()
            ->get()
            ->groupBy(fn ($produit) => strtolower(trim($produit->nom)))
            ->map(fn ($group) =>
                $group->firstWhere('photo', '!=', null)
                ?? $group->first()
            )
            ->values();

        $page = request()->integer('page', 1);
        $perPage = 12;

        $produits = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('client.produits.index', compact('produits'));
    }

    /**
     * Enregistrer une commande.
     */
  public function commander(Request $request, Produit $produit)
{
    $validated = $request->validate([
        'quantite' => ['required', 'integer', 'min:1'],
    ]);

    $user = auth()->user();

    $result = DB::transaction(function () use ($validated, $produit, $user) {

        $produit = Produit::with('stock')
            ->lockForUpdate()
            ->findOrFail($produit->id);

        $stock = $produit->stock;

        if (!$stock) {
            return ['error' => 'Aucun stock disponible pour ce produit.'];
        }

        // Vérification uniquement
        if ($stock->quantite_disponible < $validated['quantite']) {
            return ['error' => 'Stock insuffisant.'];
        }

        $montantTotal = $produit->prix_unitaire * $validated['quantite'];

        Commande::create([
            'code_commande' => 'CMD-' . strtoupper(Str::random(8)),
            'user_id'       => $user->id,
            'produit_id'    => $produit->id,
            'quantite'      => $validated['quantite'],
            'prix_unitaire' => $produit->prix_unitaire,
            'montant_total' => $montantTotal,
            'statut'        => 'en_attente',
            'date_commande' => now(),
        ]);

        // IMPORTANT :
        // On ne diminue PAS le stock ici.
        // Le stock sera diminué uniquement lorsque
        // l'administrateur acceptera la commande.

        return ['success' => true];
    });

    if (isset($result['error'])) {
        return back()->with('error', $result['error']);
    }

    return redirect()
        ->route('client.commandes.index')
        ->with('success', 'Commande envoyée avec succès. Elle est en attente de validation.');
}
    /**
     * Afficher un produit.
     */
    public function showProduit(Produit $produit)
    {
        $produit->load('stock');

        return view('client.produits.show', compact('produit'));
    }

    /**
     * Afficher une commande.
     */
    public function showCommande(Commande $commande)
    {
        abort_if(
            $commande->user_id !== auth()->id(),
            403,
            'Accès non autorisé.'
        );

        $commande->load([
            'produit',
            'paiements',
        ]);

        return view('client.commandes.show', compact('commande'));
    }

    /**
     * Historique des commandes du client.
     */
    public function mesCommandes()
    {
        $commandes = Commande::with([
                'produit',
                'paiements',
            ])
            ->where('user_id', auth()->id())
            ->latest('date_commande')
            ->paginate(10);

        return view('client.commandes.index', compact('commandes'));
    }
}