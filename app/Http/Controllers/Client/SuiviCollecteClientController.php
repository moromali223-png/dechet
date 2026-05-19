<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Planification;
use Illuminate\Http\Request;

class SuiviCollecteClientController extends Controller
{
    public function index(Request $request)
    {
        $clientUserId = auth()->id();
        $type = $request->get('type', 'toutes');

        $query = Planification::with([
            'zone',
            'collecteur.user',
            'collecte',
            'abonnement.client.user',
        ])->whereHas('abonnement.client', function ($q) use ($clientUserId) {
            $q->where('user_id', $clientUserId);
        });

        if ($type === 'en_cours') {
            $query->whereIn('statut', ['planifiee', 'assignee', 'en_route', 'en_cours']);
        }

        if ($type === 'terminees') {
            $query->where('statut', 'terminee')->whereHas('collecte');
        }

        if ($type === 'annulees') {
            $query->where('statut', 'annulee');
        }

        $collectes = $query->latest('date_prevue')->paginate(10);

        return view('client.suivi_collecte.index', compact('collectes', 'type'));
    }

    public function show($id)
    {
        $clientUserId = auth()->id();

        $planification = Planification::with([
            'zone',
            'collecteur.user',
            'collecte',
            'abonnement.client.user',
        ])
        ->whereHas('abonnement.client', function ($q) use ($clientUserId) {
            $q->where('user_id', $clientUserId);
        })
        ->findOrFail($id);

        return view('client.suivi_collecte.show', compact('planification'));
    }
}