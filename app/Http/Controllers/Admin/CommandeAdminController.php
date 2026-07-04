<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Mouvement;
use App\Models\Paiement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandeAdminController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->get('statut');

        $commandes = Commande::with(['user', 'produit'])   // Correction ici
            ->when($statut, function ($query) use ($statut) {
                return $query->where('statut', $statut);
            })
            ->latest('date_commande')   // Meilleure colonne de tri
            ->paginate(15);

        $stats = [
            'total'      => Commande::count(),
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'acceptee'   => Commande::where('statut', 'acceptee')->count(),
            'refusee'    => Commande::where('statut', 'refusee')->count(),
            'livree'     => Commande::where('statut', 'livree')->count(),
        ];

        return view('admin.commandes.index', compact('commandes', 'statut', 'stats'));
    }

    public function show(Commande $commande)
    {
        $commande->load(['user', 'produit', 'paiements']);   // Correction ici

        return view('admin.commandes.show', compact('commande'));
    }

    public function accepter(Commande $commande)
    {
        if ($commande->statut !== 'en_attente') {
            return back()->with('error', 'Cette commande ne peut pas être acceptée.');
        }

        $commande->load('produit.stock');

        $produit = $commande->produit;
        $stock = $produit?->stock;

        if (!$produit || !$stock) {
            return back()->with('error', 'Produit ou stock introuvable.');
        }

        if ($stock->quantite_disponible < $commande->quantite) {
            return back()->with('error', 'Stock insuffisant.');
        }

        try {
            DB::transaction(function () use ($commande, $produit, $stock) {

                // Décrémenter le stock
                $stock->decrement('quantite_disponible', $commande->quantite);

                // Créer paiement
                Paiement::create([
                    'mode_paiement'      => 'en_ligne',
                    'montant'            => $commande->montant_total,
                    'statut'             => 'valide',
                    'type_paiement'      => 'commande',
                    'reference_paiement' => 'PAY-' . strtoupper(Str::random(8)),
                    'commande_id'        => $commande->id,
                ]);

                // Créer mouvement de stock
                Mouvement::create([
                    'stock_id'        => $stock->id,
                    'type_mouvement'  => 'sortie',
                    'quantite'        => $commande->quantite,
                    'prix_unitaire'   => $produit->prix_unitaire,
                    'montant_total'   => $commande->montant_total,
                    'source'          => 'commande',
                    'description'     => 'Commande acceptée - ' . $commande->code_commande,
                    'commande_id'     => $commande->id,
                    'user_id'         => auth()->id(),
                    'date_mouvement'  => now()->toDateString(),
                    'heure_mouvement' => now()->toTimeString(),
                ]);

                // Mettre à jour le statut
                $commande->update(['statut' => 'acceptee']);
            });

            return back()->with('success', 'Commande acceptée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function refuser(Commande $commande)
    {
        if ($commande->statut !== 'en_attente') {
            return back()->with('error', 'Cette commande ne peut pas être refusée.');
        }

        $commande->update(['statut' => 'refusee']);

        return back()->with('success', 'Commande refusée.');
    }

    public function paiements()
    {
        $paiements = Paiement::with(['commande.user', 'commande.produit'])  // Correction ici
            ->latest()
            ->paginate(15);

        return view('admin.paiements.index', compact('paiements'));
    }

    public function produits()
    {
        $produits = Produit::with('stock')->paginate(15);

        return view('admin.produits.index', compact('produits'));
    }
}