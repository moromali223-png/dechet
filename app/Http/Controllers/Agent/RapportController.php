<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Collecte;      // ← Correction principale
use App\Models\Pesage;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Trie;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index()
    {
        return view('agent.rapports.index');
    }

    public function journalier(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        $data = [
            'date' => $date,
            'collectes' => Collecte::whereDate('created_at', $date)->count(),
            'pesages'   => Pesage::whereDate('created_at', $date)->get(),
            'tries'     => Trie::whereDate('created_at', $date)->get(),
            'produits'  => Produit::whereDate('created_at', $date)->get(),
            'poids_total' => Pesage::whereDate('created_at', $date)->sum('poids'),
            'quantite_triee' => Trie::whereDate('created_at', $date)->sum('quantite_trier'),
        ];

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('agent.rapports.journalier_pdf', $data);
            return $pdf->download("rapport-journalier-{$date}.pdf");
        }

        return view('agent.rapports.journalier', $data);
    }

    public function mensuel(Request $request)
    {
        $mois = $request->get('mois', now()->format('Y-m'));

        $data = [
            'mois' => $mois,
            'collectes' => Collecte::whereYear('created_at', substr($mois, 0, 4))
                ->whereMonth('created_at', substr($mois, 5, 2))->count(),
            'pesages' => Pesage::whereYear('created_at', substr($mois, 0, 4))
                ->whereMonth('created_at', substr($mois, 5, 2))->get(),
            'tries' => Trie::whereYear('created_at', substr($mois, 0, 4))
                ->whereMonth('created_at', substr($mois, 5, 2))->get(),
            'produits' => Produit::whereYear('created_at', substr($mois, 0, 4))
                ->whereMonth('created_at', substr($mois, 5, 2))->get(),
            'stats_mensuelles' => $this->getStatsMensuelles($mois),
        ];

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('agent.rapports.mensuel_pdf', $data);
            return $pdf->download("rapport-mensuel-{$mois}.pdf");
        }

        return view('agent.rapports.mensuel', $data);
    }

    private function getStatsMensuelles($mois)
    {
        $year = substr($mois, 0, 4);
        $month = substr($mois, 5, 2);

        return [
            'poids_total'       => Pesage::whereYear('created_at', $year)
                                        ->whereMonth('created_at', $month)
                                        ->sum('poids'),
            'quantite_triee'    => Trie::whereYear('created_at', $year)
                                        ->whereMonth('created_at', $month)
                                        ->sum('quantite_trier'),
            'produits_fabriqués'=> Produit::whereYear('created_at', $year)
                                        ->whereMonth('created_at', $month)
                                        ->count(),
            'valeur_stock'      => Stock::sum(DB::raw('quantite_disponible * prix_unitaire')),
        ];
    }
}