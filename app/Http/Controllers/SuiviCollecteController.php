<?php

namespace App\Http\Controllers;

use App\Models\Planification;
use App\Models\Zone;
use App\Models\User;           // Ajouté
use Illuminate\Http\Request;

class SuiviCollecteController extends Controller
{
    public function index(Request $request)
{
    $type = $request->get('type', 'effectuees');

    $query = Planification::with([
        'zone',
        'collecteur',
        'agent',
        'abonnement.user',
        'collecte',           // relation définie dans Planification
        'collecte.pesages'
    ]);

    // Filtres
    if ($request->filled('date_filter')) {
        if ($request->date_filter === 'today') {
            $query->whereDate('date_prevue', today());
        } elseif ($request->date_filter === 'week') {
            $query->whereBetween('date_prevue', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->date_filter === 'month') {
            $query->whereMonth('date_prevue', now()->month);
        }
    }

    if ($request->filled('zone_id')) {
        $query->where('zone_id', $request->zone_id);
    }

    if ($request->filled('collecteur_id')) {
        $query->where('collecteur_id', $request->collecteur_id);
    }

    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // Type de collectes
    if ($type === 'effectuees') {
        $query->where('statut', 'terminee')
              ->whereHas('collecte');
    } else {
        $query->whereIn('statut', ['planifiee', 'assignee', 'en_route', 'en_cours'])
              ->doesntHave('collecte');
    }

    $collectes = $query->latest('date_prevue')->paginate(20);

    $zones = Zone::all();

    $collecteurs = User::where('role', 'collecteur')->get();

    // Statistiques
    $stats = [
        'total_effectuees' => Planification::where('statut', 'terminee')
                                           ->whereHas('collecte')
                                           ->count(),
        'total_planifiees' => Planification::doesntHave('collecte')->count(),
        'aujourdhui'      => Planification::whereDate('date_prevue', today())->count(),
    ];

    return view('admin.suivi_collecte.index', compact('collectes', 'zones', 'collecteurs', 'type', 'stats'));
}

    public function show($id)
    {
        $planification = Planification::with([
            'zone',
            'collecteur',
            'agent',
            'abonnement.user',
            'collecte',
            'collecte.pesages'
        ])->findOrFail($id);

        return view('admin.suivi_collecte.show', compact('planification'));
    }
}