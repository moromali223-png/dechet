<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeAdminController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->statut;

        $commandes = Commande::with(['client.user', 'produitRelation'])
            ->when($statut, function ($query) use ($statut) {
                return $query->where('statut', $statut);
            })
            ->latest()
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
        $commande->load(['client.user', 'produitRelation', 'paiements']);

        return view('admin.commandes.show', compact('commande'));
    }

    public function accepter(Commande $commande)
{
    if ($commande->statut !== 'en_attente') {
        return back()->with(
            'error',
            'Cette commande ne peut pas être acceptée.'
        );
    }

    try {

        DB::transaction(function () use ($commande) {

            $commande->load('produitRelation.stock');

            $produit = $commande->produitRelation;

            if (!$produit) {
                throw new \Exception('Produit introuvable.');
            }

            $stock = $produit->stock;

            if (!$stock) {
                throw new \Exception('Stock introuvable.');
            }

            if ($stock->quantite_disponible < $commande->quantite) {
                throw new \Exception('Stock insuffisant.');
            }

            $stock->decrement(
                'quantite_disponible',
                $commande->quantite
            );

            $commande->update([
                'statut' => 'acceptee'
            ]);

            Paiement::create([
                'commande_id'   => $commande->id,
                'mode_paiement' => 'en_ligne',
                'montant'       => $commande->montant_total,
                'statut'        => 'valide',
            ]);
        });

        return back()->with(
            'success',
            'Commande acceptée avec succès.'
        );

    } catch (\Exception $e) {

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}

    public function refuser(Commande $commande)
    {
        if ($commande->statut !== 'en_attente') {
            return back()->with('error', 'Cette commande ne peut pas être refusée.');
        }

        $commande->update([
            'statut' => 'refusee'
        ]);

        return back()->with('success', 'Commande refusée.');
    }
}