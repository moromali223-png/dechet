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
    /**
     * Liste des abonnements
     */
    public function index()
    {
        $query = Abonnement::with([
            'user',
            'client.user',
            'client.zone',
            'planifications',
        ]);

        // Un client ne voit que ses abonnements
        if (auth()->user()->role === 'client') {
            $query->where('user_id', auth()->id());
        }

        $abonnements = $query->latest()->paginate(20);

        return view('admin.abonnements.index', compact('abonnements'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $clients = auth()->user()->role === 'admin'
            ? Client::with(['user', 'zone'])->orderBy('id')->get()
            : null;

        return view('admin.abonnements.create', compact('clients'));
    }

    /**
     * Enregistrer un nouvel abonnement
     */
    public function store(StoreAbonnementRequest $request)
    {
        $data = $request->validated();

        // Détermination du client
        if (auth()->user()->role === 'admin') {
            $client = Client::findOrFail($request->client_id);
            $data['user_id'] = $client->user_id;

            // Création par l'admin = activation immédiate
            $data['statut'] = 'actif';
            $data['date_activation'] = now();
        } else {
            $client = Client::where('user_id', auth()->id())->firstOrFail();
            $data['user_id'] = auth()->id();

            // Création par le client = validation admin requise
            $data['statut'] = 'en_attente';
        }

        // Pré-remplir l'adresse depuis le client si nécessaire
        $data = $this->prefillAddressFromClient($data, $client);

        // Création de l'abonnement
        $abonnement = Abonnement::create($data);

        $message = auth()->user()->role === 'admin'
            ? 'Abonnement créé avec succès et planifications générées automatiquement.'
            : 'Votre demande d’abonnement a été envoyée avec succès. Elle sera examinée par un administrateur.';

        return redirect()
            ->route('admin.abonnements.index')
            ->with('success', $message);
    }

    /**
     * Afficher un abonnement
     */
    public function show(Abonnement $abonnement)
    {
        $this->authorize('view', $abonnement);

        $abonnement->load([
            'user',
            'client.user',
            'client.zone',
            'planifications.zone',
            'planifications.collecteur.user',
            'planifications.agent',
        ]);

        return view('admin.abonnements.show', compact('abonnement'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Abonnement $abonnement)
    {
        $this->authorize('update', $abonnement);

        return view('admin.abonnements.edit', compact('abonnement'));
    }

    /**
     * Pré-remplir l'adresse depuis le client
     */
    protected function prefillAddressFromClient(array $data, ?Client $client = null): array
    {
        if (! $client && isset($data['client_id'])) {
            $client = Client::find($data['client_id']);
        }

        if ($client) {
            // Pré-remplir seulement si les champs sont vides
            if (empty($data['rue']) && $client->rue) {
                $data['rue'] = $client->rue;
            }
            if (empty($data['quartier']) && $client->quartier) {
                $data['quartier'] = $client->quartier;
            }
            if (empty($data['ville']) && $client->ville) {
                $data['ville'] = $client->ville;
            }
            if (empty($data['repere']) && $client->repere) {
                $data['repere'] = $client->repere;
            }
        }

        return $data;
    }

    /**
     * Mise à jour
     */
    public function update(
        UpdateAbonnementRequest $request,
        Abonnement $abonnement
    ) {
        $this->authorize('update', $abonnement);

        $abonnement->update($request->validated());

        return redirect()
            ->route('admin.abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Abonnement $abonnement)
    {
        $this->authorize('delete', $abonnement);

        // Suppression des planifications associées
        $abonnement->planifications()->delete();

        $abonnement->delete();

        return redirect()
            ->route('admin.abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }

    /**
     * Activation par l'administrateur
     */
    public function activer(Abonnement $abonnement)
    {
        $this->authorize('activer', $abonnement);

        $abonnement->update([
            'statut' => 'actif',
            'date_activation' => now(),
        ]);

        return redirect()
            ->route('admin.abonnements.index')
            ->with(
                'success',
                'Abonnement activé avec succès. Les planifications ont été générées automatiquement.'
            );
    }

    /**
     * Formulaire de rejet
     */
    public function rejeterForm(Abonnement $abonnement)
    {
        $this->authorize('rejeter', $abonnement);

        return view('admin.abonnements.reject', compact('abonnement'));
    }

    /**
     * Rejet par l'administrateur
     */
    public function rejeter(RejectAbonnementRequest $request, Abonnement $abonnement)
    {
        if ($abonnement->statut !== 'en_attente') {
            return back()->with(
                'error',
                'Seuls les abonnements en attente peuvent être rejetés.'
            );
        }

        $validated = $request->validated();

        $abonnement->update([
            'statut' => 'rejete',
            'motif_rejet' => $validated['motif_rejet'],
            'date_rejet' => now(),
        ]);

        // Notification du client
        if ($abonnement->user) {
            $abonnement->user->notify(
                new AbonnementRejectedNotification(
                    $abonnement,
                    $validated['motif_rejet']
                )
            );
        }

        return redirect()
            ->route('admin.abonnements.index')
            ->with(
                'success',
                'Abonnement rejeté avec succès. Le client a été notifié.'
            );
    }
}
