<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignAffectationRequest;
use App\Http\Requests\UpdatePlanificationStatusRequest;
use App\Models\Collecteur;
use App\Models\Planification;
use App\Models\User;
use App\Notifications\AgentAffectationNotification;
use App\Notifications\ClientCollecteDemarreeNotification;
use App\Notifications\CollecteurAffectationNotification;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only([
            'index',
            'assign',
        ]);
    }

    /**
     * Liste des planifications à affecter
     */
    public function index(Request $request)
    {
        $planifications = Planification::with([
                'zone',
                'agent',
                'collecteur.user',
                'declaration.user',
                'abonnement.client.user',
            ])
            ->whereIn('statut', ['planifiee', 'assignee'])
            ->orderBy('date_prevue')
            ->orderBy('priorite')
            ->paginate(15);

        $agents = User::where('role', 'agent')
            ->orderBy('name')
            ->get();

        $collecteurs = Collecteur::with('user')
            ->whereHas('user')
            ->orderBy('id')
            ->get();

        return view('affectations.index', compact(
            'planifications',
            'agents',
            'collecteurs'
        ));
    }

    /**
     * Affecter un agent et un collecteur
     */
    public function assign(
        AssignAffectationRequest $request,
        Planification $planification
    ) {
        $planification->update([
            'agent_id'        => $request->agent_id,
            'collecteur_id'   => $request->collecteur_id,
            'ordre_passage'   => $request->ordre_passage,
            'priorite'        => $request->priorite,
            'duree_estimee'   => $request->duree_estimee,
            'statut'          => 'assignee',
        ]);

        $planification->load([
            'agent',
            'collecteur.user',
            'declaration.user',
            'abonnement.client.user',
        ]);

        // Notification au collecteur
        if ($planification->collecteur?->user) {
            $planification->collecteur->user->notify(
                new CollecteurAffectationNotification($planification)
            );
        }

        // Notification à l'agent
        if ($planification->agent) {
            $planification->agent->notify(
                new AgentAffectationNotification($planification)
            );
        }

        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'La planification a été affectée avec succès.'
            );
    }

    /**
     * Mise à jour du statut par agent/collecteur
     */
    public function updateStatus(
        UpdatePlanificationStatusRequest $request,
        Planification $planification
    ) {
        $this->authorize('update', $planification);

        $planification->update([
            'statut' => $request->statut,
        ]);

        // Notification client lorsque la collecte démarre
        if ($planification->statut === 'en_route') {
            $client = optional($planification->declaration)->user
                ?? optional(optional($planification->abonnement)->client)->user;

            if ($client) {
                $client->notify(
                    new ClientCollecteDemarreeNotification($planification)
                );
            }
        }

        return back()->with(
            'success',
            'Le statut de la planification a été mis à jour.'
        );
    }
}