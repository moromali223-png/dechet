<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
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
            ->latest()
            ->get()
            ->groupBy(fn ($produit) => strtolower(trim($produit->nom)))
            ->map(fn ($group) =>
                $group->first(fn ($produit) => !empty($produit->photo))
                ?? $group->first()
            )
            ->values();

        $page = request()->get('page', 1);
        $perPage = 12;

        $produits = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('client.produits.index', compact('produits'));
    }

    /**
     * Enregistrer une commande client.
     */
    public function commander(Request $request, Produit $produit)
    {
        $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $client = Client::where('user_id', auth()->id())
            ->firstOrFail();

        $result = DB::transaction(function () use ($request, $produit, $client) {

            $produit = Produit::with('stock')
                ->lockForUpdate()
                ->findOrFail($produit->id);

            $stock = $produit->stock;

            if (!$stock) {
                return [
                    'error' => 'Aucun stock disponible pour ce produit.'
                ];
            }

            if ($stock->quantite_disponible < $request->quantite) {
                return [
                    'error' => 'La quantité demandée dépasse le stock disponible.'
                ];
            }

            $montantTotal = $produit->prix_unitaire * $request->quantite;

            Commande::create([
                'code_commande' => 'CMD-' . strtoupper(Str::random(8)),
                'produit'       => $produit->nom,
                'produit_id'    => $produit->id,
                'quantite'      => $request->quantite,
                'statut'        => 'en_attente',
                'client_id'     => $client->id,
                'date_commande' => now(),
                'montant_total' => $montantTotal,
            ]);

            return [
                'success' => true
            ];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return redirect()
            ->route('client.commandes.index')
            ->with(
                'success',
                'Votre commande a été enregistrée et est en attente de validation.'
            );
    }

    /**
     * Afficher un produit.
     */
    public function showProduit(Produit $produit)
    {
        return view('client.produits.show', compact('produit'));
    }

    /**
     * Afficher le détail d'une commande.
     */
    public function showCommande(Commande $commande)
    {
        $client = Client::where('user_id', auth()->id())
            ->firstOrFail();

        abort_if($commande->client_id !== $client->id, 403);

        $commande->load([
            'produitRelation',
            'paiements'
        ]);

        return view('client.commandes.show', compact('commande'));
    }

    /**
     * Liste des commandes du client.
     */
    public function mesCommandes()
    {
        $client = Client::where('user_id', auth()->id())
            ->firstOrFail();

        $commandes = Commande::with('produitRelation')
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(10);

        return view('client.commandes.index', compact('commandes'));
    }
}