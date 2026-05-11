<?php

namespace App\Http\Controllers;

use App\Models\Stock;

class AlertesController extends Controller
{
    /**
     * Afficher toutes les alertes de stock
     */
    public function index()
    {
        // Récupérer tous les stocks en alerte
        $stocksEnAlerte = Stock::with('produit')
            ->enAlerte()
            ->orderBy('quantite_disponible', 'asc')
            ->get();

        // Statistiques des alertes
        $totalAlertes = $stocksEnAlerte->count();
        $alertesCritiques = $stocksEnAlerte->where('quantite_disponible', '<=', 0)->count();
        $alertesModerees = $totalAlertes - $alertesCritiques;

        // Grouper par niveau d'urgence
        $alertesParUrgence = [
            'critique' => $stocksEnAlerte->where('quantite_disponible', '<=', 0),
            'elevee' => $stocksEnAlerte->filter(function ($stock) {
                return $stock->quantite_disponible > 0 && $stock->quantite_disponible <= $stock->seuil_alerte * 0.5;
            }),
            'moderee' => $stocksEnAlerte->filter(function ($stock) {
                return $stock->quantite_disponible > $stock->seuil_alerte * 0.5 && $stock->quantite_disponible <= $stock->seuil_alerte;
            }),
        ];

        return view('admin.alertes.index', compact(
            'stocksEnAlerte',
            'totalAlertes',
            'alertesCritiques',
            'alertesModerees',
            'alertesParUrgence'
        ));
    }

    /**
     * Marquer une alerte comme traitée (temporairement)
     */
    public function marquerTraitee($id)
    {
        $stock = Stock::findOrFail($id);

        // Ici on pourrait ajouter un champ 'alerte_traitee' ou utiliser un système de notifications
        // Pour l'instant, on redirige simplement avec un message

        return redirect()->route('admin.alertes.index')
            ->with('success', "Alerte pour {$stock->nom} marquée comme consultée");
    }

    /**
     * Générer un rapport des alertes (pour export ou impression)
     */
    public function rapport()
    {
        $stocksEnAlerte = Stock::with('produit')
            ->enAlerte()
            ->orderBy('quantite_disponible', 'asc')
            ->get();

        return view('admin.alertes.rapport', compact('stocksEnAlerte'));
    }
}
