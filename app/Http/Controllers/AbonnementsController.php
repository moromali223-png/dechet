<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbonnementRequest;
use App\Http\Requests\UpdateAbonnementRequest;
use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AbonnementsController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->user()->role === 'admin';
    }

    private function isClient(): bool
    {
        return auth()->user()->role === 'client';
    }

    /**
     * Liste des abonnements
     */
    public function index()
    {
        $query = Abonnement::with([
            'user',
            'user.zone',
            'planifications',
        ]);

        if ($this->isClient()) {
            $query->where('user_id', auth()->id());
        }

        $abonnements = $query->latest()->paginate(20);

        return view('admin.abonnements.index', compact('abonnements'));
    }

    /**
     * Formulaire de création (Admin seulement)
     */
    public function create()
    {
        if (! $this->isAdmin()) {
            abort(403);
        }

        $clients = User::with('zone')
            ->where('role', 'client')
            ->orderBy('name')
            ->get();

        return view('admin.abonnements.create', compact('clients'));
    }

    /**
     * Enregistrement d'un abonnement + création automatique de Déclaration + Planification
     */
 /**
 * Enregistrement d'un abonnement + création automatique de Déclaration + Planification
 */
public function store(StoreAbonnementRequest $request)
{
    $data = $request->validated();

    if ($this->isAdmin()) {
        $client = User::where('role', 'client')->findOrFail($request->client_id);
        $data['user_id'] = $client->id;
        $data['statut'] = 'actif';
        $data['date_activation'] = now();
    } else {
        $data['user_id'] = auth()->id();
        $data['statut'] = 'en_attente';
    }

    $data = $this->prefillAddressFromClient($data, $client ?? auth()->user());

    $abonnement = DB::transaction(function () use ($data) {
        $abonnement = Abonnement::create($data);

        // // Création INITIALE seulement
        // if ($abonnement->statut === 'actif') {
        //     // Correction ici : namespace complet
        //     app(\App\Services\PlanificationService::class)
        //         ->createNextPlanification($abonnement);
        // }

        return $abonnement;
    });

    return redirect()
        ->route('abonnements.index')
        ->with('success', 'Abonnement créé avec succès (1 déclaration + 1 planification générées).');
}
    /**
     * Affichage
     */
    public function show(Abonnement $abonnement)
    {
        if ($this->isClient() && $abonnement->user_id !== auth()->id()) {
            abort(403);
        }

        return view('admin.abonnements.show', compact('abonnement'));
    }

    /**
     * Modification (Admin seulement)
     */
    public function edit(Abonnement $abonnement)
    {
        if (! $this->isAdmin()) {
            abort(403);
        }

        return view('admin.abonnements.edit', compact('abonnement'));
    }

    /**
     * Mise à jour
     */
    public function update(UpdateAbonnementRequest $request, Abonnement $abonnement)
    {
        if (! $this->isAdmin()) {
            abort(403);
        }

        $abonnement->update($request->validated());

        return back()->with('success', 'Abonnement mis à jour.');
    }

    /**
     * Suppression
     */
    public function destroy(Abonnement $abonnement)
    {
        if (! $this->isAdmin()) {
            abort(403);
        }

        $abonnement->planifications()->delete();
        $abonnement->delete();

        return back()->with('success', 'Abonnement supprimé.');
    }

    /**
     * Pré-remplissage des champs d'adresse
     */
    protected function prefillAddressFromClient(array $data, ?User $client = null): array
    {
        if ($client) {
            $data['rue']     = $data['rue']     ?? $client->rue     ?? '';
            $data['quartier'] = $data['quartier'] ?? $client->quartier ?? '';
            $data['porte']    = $data['porte']    ?? $client->porte    ?? '';
            $data['repere']   = $data['repere']   ?? $client->repere   ?? '';
        }

        return $data;
    }
}