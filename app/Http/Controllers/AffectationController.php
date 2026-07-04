<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignAffectationRequest;
use App\Http\Requests\UpdatePlanificationStatusRequest;
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
     * Liste des planifications
     */
    public function index(Request $request)
    {
        $planifications = Planification::with([
            'zone',
            'agent',
            'collecteur',
            'declaration.user',
            'abonnement.user',
            'abonnement.user.zone',
        ])
        ->whereIn('statut', [
            'planifiee',
            'assignee',
        ])
        ->orderBy('date_prevue')
        ->orderBy('priorite')
        ->paginate(15);

        $agents = User::where('role', 'agent')
            ->orderBy('name')
            ->get();

        $collecteurs = User::where('role', 'collecteur')
            ->orderBy('name')
            ->get();

        return view(
            'admin.affectations.index',
            compact(
                'planifications',
                'agents',
                'collecteurs'
            )
        );
    }

    /**
     * Affecter une planification
     */
    public function assign(
        AssignAffectationRequest $request,
        Planification $planification
    ) {

        $planification->update([
            'agent_id' => $request->agent_id,
            'collecteur_id' => $request->collecteur_id,
            'ordre_passage' => $request->ordre_passage,
            'priorite' => $request->priorite,
            'duree_estimee' => $request->duree_estimee,
            'statut' => 'assignee',
        ]);

        $planification->load([
            'agent',
            'collecteur',
            'declaration.user',
            'abonnement.user',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notification Collecteur
        |--------------------------------------------------------------------------
        */

        if ($planification->collecteur) {
            $planification->collecteur->notify(
                new CollecteurAffectationNotification($planification)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Notification Agent
        |--------------------------------------------------------------------------
        */

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
     * Mise à jour du statut
     */
    public function updateStatus(
        UpdatePlanificationStatusRequest $request,
        Planification $planification
    ) {

        $this->authorize('update', $planification);

        $planification->update([
            'statut' => $request->statut,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notification Client
        |--------------------------------------------------------------------------
        */

        if ($planification->statut === 'en_route') {

            $client =
                optional($planification->declaration)->user
                ?? optional($planification->abonnement)->user;

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