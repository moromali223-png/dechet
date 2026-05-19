<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CommandeClientController extends Controller
{
    public function produits()
    {
        $collection = Produit::where('statut', 'actif')
            ->where('quantite', '>', 0)
            ->latest()
            ->get()
            ->groupBy(fn($p) => strtolower(trim($p->nom)))
            ->map(fn($group) =>
                $group->first(fn($p) => !empty($p->photo)) ?? $group->first()
            )
            ->values();

        $page    = request('page', 1);
        $perPage = 12;

        $produits = new \Illuminate\Pagination\LengthAwarePaginator(
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

    public function commander(Request $request, Produit $produit)
    {
        $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $client = Client::where('user_id', auth()->id())->firstOrFail();

        // ⚠️ CORRECTION 1 : DB::transaction ne peut pas retourner une
        // Response redirect/back directement — on extrait le résultat
        $result = DB::transaction(function () use ($request, $produit, $client) {

            $produit = Produit::where('id', $produit->id)
                ->lockForUpdate()
                ->firstOrFail();

            // CORRECTION 2 : retourner un tableau au lieu d'un redirect
            // depuis la transaction (les redirects dans transaction causent
            // des comportements imprévisibles)
            if ($produit->quantite < $request->quantite) {
                return ['error' => 'Stock insuffisant pour cette commande.'];
            }

            $total = $produit->prix_unitaire * $request->quantite;

            Commande::create([
                'code_commande' => 'CMD-' . strtoupper(Str::random(8)),
                'produit'       => $produit->nom,
                'produit_id'    => $produit->id,
                'quantite'      => $request->quantite,
                'statut'        => 'en_attente',
                'client_id'     => $client->id,
                'date_commande' => now(),
                'montant_total' => $total,
            ]);

           

            return ['success' => true];
        });

        // CORRECTION 3 : gérer la réponse APRÈS la transaction
        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return redirect()
            ->route('client.commandes.index')
            ->with('success', 'Commande enregistrée avec succès.');
    }

    public function showProduit(Produit $produit)
    {
        return view('client.produits.show', compact('produit'));
    }

    public function showCommande(Commande $commande)
    {
        $client = Client::where('user_id', auth()->id())->firstOrFail();

        abort_if($commande->client_id !== $client->id, 403);

        // CORRECTION 4 : vérifier que la relation s'appelle bien
        // 'produitRelation' dans votre modèle Commande, sinon
        // remplacer par le bon nom (ex: 'produit' ou 'produitModel')
        $commande->load(['produitRelation', 'paiements']);

        return view('client.commandes.show', compact('commande'));
    }

    public function mesCommandes()
    {
        $client = Client::where('user_id', auth()->id())->firstOrFail();

        $commandes = Commande::with('produitRelation')
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(10);

        return view('client.commandes.index', compact('commandes'));
    }
}