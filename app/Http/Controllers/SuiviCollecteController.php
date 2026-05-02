<?php

namespace App\Http\Controllers;

use App\Models\Collecteur;
use App\Models\Planification;
use App\Models\Zone;
use Illuminate\Http\Request;

class SuiviCollecteController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'effectuees');

        $query = Planification::with([
            'zone',
            'collecteur.user', // ✅ TRÈS IMPORTANT
            'collecte',
        ]);

        // FILTRE DATE
        if ($request->date_filter == 'today') {
            $query->whereDate('date_prevue', now());
        } elseif ($request->date_filter == 'week') {
            $query->whereBetween('date_prevue', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->date_filter == 'month') {
            $query->whereMonth('date_prevue', now()->month);
        }

        // FILTRE ZONE
        if ($request->zone_id) {
            $query->where('zone_id', $request->zone_id);
        }

        // FILTRE COLLECTEUR
        if ($request->collecteur_id) {
            $query->where('collecteur_id', $request->collecteur_id);
        }

        // FILTRE STATUT
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }

        // TYPE
        if ($type === 'effectuees') {
            $query->where('statut', 'terminee');
        } else {
            $query->whereIn('statut', ['planifiee', 'assignee', 'en_route', 'en_cours']);
        }

        $collectes = $query->latest('date_prevue')->paginate(15);

        $zones = Zone::all();
        $collecteurs = Collecteur::with('user')->get();

        return view('suivi_collecte.index', compact('collectes', 'zones', 'collecteurs', 'type'));
    }
}
