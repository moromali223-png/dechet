<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectAbonnementRequest;
use App\Http\Requests\StoreAbonnementRequest;
use App\Http\Requests\UpdateAbonnementRequest;
use App\Models\Abonnement;
use App\Models\Client;
use App\Notifications\AbonnementRejectedNotification;

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
     * LISTE
     */
    public function index()
    {
        $query = Abonnement::with([
            'user',
            'client.user',
            'client.zone',
            'planifications',
        ]);

        // CLIENT = uniquement ses abonnements
        if ($this->isClient()) {
            $query->where('user_id', auth()->id());
        }

        $abonnements = $query->latest()->paginate(20);

        return view('admin.abonnements.index', compact('abonnements'));
    }

    /**
     * CREATE (ADMIN ONLY)
     */
    public function create()
    {
        if (! $this->isAdmin()) {
            abort(403, "Accès refusé");
        }

        $clients = Client::with(['user', 'zone'])->orderBy('id')->get();

        return view('admin.abonnements.create', compact('clients'));
    }

    /**
     * STORE
     */
    public function store(StoreAbonnementRequest $request)
    {
        $data = $request->validated();

        if ($this->isAdmin()) {

            $client = Client::findOrFail($request->client_id);
            $data['user_id'] = $client->user_id;

            $data['statut'] = 'actif';
            $data['date_activation'] = now();

        } else {

            $client = Client::where('user_id', auth()->id())->firstOrFail();
            $data['user_id'] = auth()->id();

            $data['statut'] = 'en_attente';
        }

        $data = $this->prefillAddressFromClient($data, $client);

        Abonnement::create($data);

        return redirect()
            ->route('abonnements.index')
            ->with('success', 'Abonnement enregistré avec succès.');
    }

    /**
     * SHOW (ADMIN + CLIENT OWNER)
     */
    public function show(Abonnement $abonnement)
    {
        $this->authorizeAccess($abonnement);

        return view('admin.abonnements.show', compact('abonnement'));
    }

    /**
     * EDIT (ADMIN ONLY)
     */
    public function edit(Abonnement $abonnement)
    {
        if (! $this->isAdmin()) {
            abort(403);
        }

        return view('admin.abonnements.edit', compact('abonnement'));
    }

    /**
     * UPDATE (ADMIN ONLY)
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
     * DELETE (ADMIN ONLY)
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
     * ACCESS CONTROL SHOW
     */
    private function authorizeAccess(Abonnement $abonnement)
    {
        if ($this->isClient() && $abonnement->user_id !== auth()->id()) {
            abort(403);
        }
    }

    /**
     * PREFILL ADDRESS
     */
   protected function prefillAddressFromClient(array $data, ?Client $client = null): array
{
    if ($client) {
        $data['rue'] = $data['rue'] ?? $client->rue;
        $data['quartier'] = $data['quartier'] ?? $client->quartier;
        $data['ville'] = $data['ville'] ?? $client->ville;
        $data['repere'] = $data['repere'] ?? $client->repere;
    }

    return $data;
}
}