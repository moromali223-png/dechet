<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Trie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventaireController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['produit', 'trie'])
            ->latest()
            ->paginate(20);

        $totalProduits = Stock::count();
        $stockTotal = Stock::sum('quantite_disponible');
        $valeurTotale = Stock::all()->sum(fn($stock) => $stock->valeur_totale ?? 0);
        $produitsEnAlerte = Stock::enAlerte()->count();

        return view('inventaire.index', compact(
            'stocks', 'totalProduits', 'stockTotal', 'valeurTotale', 'produitsEnAlerte'
        ));
    }

   public function create()
{
    $produits = Produit::select('id', 'nom', 'prix_unitaire', 'unite_mesure')
                       ->where('statut', 'actif')
                       ->orderBy('nom')
                       ->get();

    // Correction ici :
    $tries = Trie::orderBy('type_dechet')   // ← Colonne qui existe
                 ->orderBy('qualite')
                 ->get();

    return view('inventaire.create', compact('produits', 'tries'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id'          => 'required|exists:produits,id',
            'trie_id'             => 'nullable|exists:tries,id',
            'quantite_disponible' => 'required|numeric|min:0.01',
            'prix_unitaire'       => 'required|numeric|min:0',
            'unite_mesure'        => 'required|string|max:50',
            'seuil_alerte'        => 'required|numeric|min:0',
        ]);

        // Vérification de sécurité pour l'utilisateur
        if (!auth()->check()) {
            return back()->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        DB::beginTransaction();

        try {
            $trieId = $validated['trie_id'] ?: null;

            // Recherche stock existant
            $stock = Stock::where('produit_id', $validated['produit_id'])
                ->where('trie_id', $trieId)
                ->first();

            if ($stock) {
                $stock->quantite_disponible += $validated['quantite_disponible'];
                $stock->prix_unitaire = $validated['prix_unitaire'];
                $stock->unite_mesure = $validated['unite_mesure'];
                $stock->seuil_alerte = $validated['seuil_alerte'];
                $stock->save();
            } else {
                $stock = Stock::create([
                    'code_stock'          => 'STK-' . strtoupper(Str::random(8)),
                    'quantite_disponible' => $validated['quantite_disponible'],
                    'prix_unitaire'       => $validated['prix_unitaire'],
                    'unite_mesure'        => $validated['unite_mesure'],
                    'seuil_alerte'        => $validated['seuil_alerte'],
                    'produit_id'          => $validated['produit_id'],
                    'trie_id'             => $trieId,
                ]);
            }

            // ==================== MOUVEMENT ====================
            Mouvement::create([
                'stock_id'       => $stock->id,
                'type_mouvement' => 'entree',
                'quantite'       => $validated['quantite_disponible'],
                'prix_unitaire'  => $validated['prix_unitaire'],
                'montant_total'  => $validated['quantite_disponible'] * $validated['prix_unitaire'],
                'source'         => 'Entrée manuelle',
                'description'    => 'Entrée de stock',
                'user_id'        => auth()->id(),
                'date_mouvement' => now(),
                'heure_mouvement'=> now()->format('H:i:s'),
            ]);

            DB::commit();

            return redirect()
                ->route('inventaire.index')
                ->with('success', 'Stock ajouté avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // ==================== UPDATE ====================
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantite_disponible' => 'required|numeric|min:0',
            'prix_unitaire'       => 'required|numeric|min:0',
            'unite_mesure'        => 'required|string|max:50',
            'seuil_alerte'        => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $stock = Stock::findOrFail($id);

            $ancienne = $stock->quantite_disponible;
            $nouvelle = $validated['quantite_disponible'];
            $diff = $nouvelle - $ancienne;

            $stock->update([
                'quantite_disponible' => $nouvelle,
                'prix_unitaire'       => $validated['prix_unitaire'],
                'unite_mesure'        => $validated['unite_mesure'],
                'seuil_alerte'        => $validated['seuil_alerte'],
            ]);

            if ($diff != 0) {
                Mouvement::create([
                    'stock_id'       => $stock->id,
                    'type_mouvement' => $diff > 0 ? 'entree' : 'sortie',
                    'quantite'       => abs($diff),
                    'prix_unitaire'  => $validated['prix_unitaire'],
                    'montant_total'  => abs($diff) * $validated['prix_unitaire'],
                    'source'         => 'Ajustement inventaire',
                    'description'    => 'Ajustement inventaire',
                    'user_id'        => auth()->id(),
                    'date_mouvement' => now(),
                    'heure_mouvement'=> now()->format('H:i:s'),
                ]);
            }

            DB::commit();

            return redirect()->route('inventaire.index')
                ->with('success', 'Stock mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // ==================== SORTIE ====================
    public function sortie(Request $request, $id)
    {
        $validated = $request->validate([
            'quantite' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $stock = Stock::findOrFail($id);

            if ($stock->quantite_disponible < $validated['quantite']) {
                return back()->with('error', 'Quantité insuffisante en stock.');
            }

            $stock->quantite_disponible -= $validated['quantite'];
            $stock->save();

            Mouvement::create([
                'stock_id'       => $stock->id,
                'type_mouvement' => 'sortie',
                'quantite'       => $validated['quantite'],
                'prix_unitaire'  => $stock->prix_unitaire,
                'montant_total'  => $validated['quantite'] * $stock->prix_unitaire,
                'source'         => 'Sortie manuelle',
                'description'    => 'Sortie de stock',
                'user_id'        => auth()->id(),
                'date_mouvement' => now(),
                'heure_mouvement'=> now()->format('H:i:s'),
            ]);

            DB::commit();

            return back()->with('success', 'Sortie effectuée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
    /**
 * Afficher les détails d'un stock
 */
public function show($id)
{
    $stock = Stock::with([
        'produit',
        'trie',
        'mouvements'
    ])->findOrFail($id);

    return view('inventaire.show', compact('stock'));
}
public function edit($id)
{
    $stock = Stock::with(['produit', 'trie'])->findOrFail($id);

    $produits = Produit::select('id', 'nom', 'prix_unitaire', 'unite_mesure')
                       ->where('statut', 'actif')
                       ->orderBy('nom')
                       ->get();

    $tries = Trie::orderBy('type_dechet')
                 ->orderBy('qualite')
                 ->get();

    return view('inventaire.edit', compact('stock', 'produits', 'tries'));
}
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $stock = Stock::findOrFail($id);
            $stock->mouvements()->delete();
            $stock->delete();

            DB::commit();

            return back()->with('success', 'Stock supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}