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

$query->when($type !== 'toutes', function ($q) use ($type) {

    if ($type === 'en_cours') {
        $q->whereIn('statut', ['assignee', 'en_route', 'en_cours']);
    }

    elseif ($type === 'terminee') {
        $q->where('statut', 'terminee');
    }

    elseif ($type === 'annulee') {
        $q->where('statut', 'annulee');
    }

    else {
        $q->where('statut', $type);
    }
});

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