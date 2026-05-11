<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Liste des commandes
     */
    public function index()
    {
        $commandes = Commande::with(['client.user', 'produit'])
            ->latest()
            ->paginate(20);

        $stats = [
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'acceptee' => Commande::where('statut', 'acceptee')->count(),
            'refusee' => Commande::where('statut', 'refusee')->count(),
            'livree' => Commande::where('statut', 'livree')->count(),
        ];

        return view('admin.commandes.index', compact('commandes', 'stats'));
    }

    /**
     * Accepter une commande
     */
    public function accepter($id)
    {
        $commande = Commande::with('produit')->findOrFail($id);

        if ($commande->statut !== 'en_attente') {
            return back()->withErrors([
                'error' => 'Cette commande ne peut pas être acceptée.',
            ]);
        }

        $stock = Stock::where('produit_id', $commande->produit_id)->first();

        if (! $stock) {
            return back()->withErrors([
                'error' => 'Stock non trouvé pour ce produit.',
            ]);
        }

        if ($stock->quantite_disponible < $commande->quantite) {
            return back()->withErrors([
                'error' => "Stock insuffisant. Disponible : {$stock->quantite_disponible} {$stock->unite_mesure}",
            ]);
        }

        try {

            DB::transaction(function () use ($commande, $stock) {

                $this->stockService->sortieStock(
                    $stock->id,
                    $commande->quantite,
                    'commande_client',
                    'Sortie liée à la commande '.$commande->code_commande,
                    $commande->id
                );

                $commande->update([
                    'statut' => 'acceptee',
                ]);
            });

            return back()->with('success', 'Commande acceptée et stock mis à jour.');

        } catch (\Exception $e) {

            return back()->withErrors([
                'error' => 'Erreur lors du traitement de la commande.',
            ]);
        }
    }

    /**
     * Refuser une commande
     */
    public function refuser($id)
    {
        $commande = Commande::findOrFail($id);

        if ($commande->statut !== 'en_attente') {
            return back()->withErrors([
                'error' => 'Cette commande ne peut pas être refusée.',
            ]);
        }

        $commande->update([
            'statut' => 'refusee',
        ]);

        return back()->with('success', 'Commande refusée avec succès.');
    }
}
