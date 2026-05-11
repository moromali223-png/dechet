<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanificationRequest;
use App\Http\Requests\UpdatePlanificationRequest;
use App\Models\Abonnement;
use App\Models\Collecteur;
use App\Models\Declaration;
use App\Models\Planification;
use App\Models\User;
use App\Models\Zone;

class PlanificationController extends Controller
{
    public function index()
    {
        $planifications = Planification::with(['zone', 'collecteur.user', 'agent', 'declaration', 'abonnement'])
            ->latest('date_prevue')
            ->paginate(10);

        return view('admin.planifications.index', compact('planifications'));
    }

    public function create()
    {
        $zones = Zone::all();
        $collecteurs = Collecteur::with('user')->get();
        $declarations = Declaration::all();
        $abonnements = Abonnement::all();
        $agents = User::where('role', 'agent')->get();

        return view('admin.planifications.create', compact('zones', 'collecteurs', 'declarations', 'abonnements', 'agents'));
    }

    public function store(StorePlanificationRequest $request)
    {
        Planification::create($request->validated());

        return redirect()->route('admin.planifications.index')->with('success', 'Planification créée avec succès.');
    }

    public function show(Planification $planification)
    {
        $planification->load(['zone', 'collecteur.user', 'agent', 'declaration', 'abonnement']);

        return view('admin.planifications.show', compact('planification'));
    }

    public function edit(Planification $planification)
    {
        $zones = Zone::all();
        $collecteurs = Collecteur::with('user')->get();
        $declarations = Declaration::all();
        $abonnements = Abonnement::all();
        $agents = User::where('role', 'agent')->get();

        return view('admin.planifications.edit', compact('planification', 'zones', 'collecteurs', 'declarations', 'abonnements', 'agents'));
    }

    public function update(UpdatePlanificationRequest $request, Planification $planification)
    {
        $data = $request->validated();

        if (! empty($data['collecteur_id']) && $planification->statut === 'planifiee') {
            $data['statut'] = 'assignee';
        }

        $planification->update($data);

        return redirect()->route('admin.planifications.index')
            ->with('success', 'Planification mise à jour avec succès.');
    }

    public function destroy(Planification $planification)
    {
        $planification->delete();

        return redirect()->route('admin.planifications.index')->with('success', 'Planification supprimée avec succès.');
    }
}
