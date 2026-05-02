<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbonnementRequest;
use App\Http\Requests\UpdateAbonnementRequest;
use App\Models\Abonnement;
use App\Models\Client;
use App\Notifications\AbonnementRejectedNotification;
use Illuminate\Http\Request;

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

        return view('abonnements.index', compact('abonnements'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $clients = auth()->user()->role === 'admin'
            ? Client::with(['user', 'zone'])->orderBy('id')->get()
            : null;

        return view('abonnements.create', compact('clients'));
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

        // Création de l'abonnement
        $abonnement = Abonnement::create($data);

        $message = auth()->user()->role === 'admin'
            ? 'Abonnement créé avec succès et planifications générées automatiquement.'
            : 'Votre demande d’abonnement a été envoyée avec succès. Elle sera examinée par un administrateur.';

        return redirect()
            ->route('abonnements.index')
            ->with('success', $message);
    }

    /**
     * Afficher un abonnement
     */
    public function show(Abonnement $abonnement)
    {
        $this->authorizeAbonnement($abonnement);

        $abonnement->load([
            'user',
            'client.user',
            'client.zone',
            'planifications.zone',
            'planifications.collecteur.user',
            'planifications.agent',
        ]);

        return view('abonnements.show', compact('abonnement'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Abonnement $abonnement)
    {
        $this->authorizeAbonnement($abonnement);

        return view('abonnements.edit', compact('abonnement'));
    }

    /**
     * Mise à jour
     */
    public function update(
        UpdateAbonnementRequest $request,
        Abonnement $abonnement
    ) {
        $this->authorizeAbonnement($abonnement);

        $abonnement->update($request->validated());

        return redirect()
            ->route('abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Abonnement $abonnement)
    {
        $this->authorizeAbonnement($abonnement);

        // Suppression des planifications associées
        $abonnement->planifications()->delete();

        $abonnement->delete();

        return redirect()
            ->route('abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }

    /**
     * Activation par l'administrateur
     */
    public function activer(Abonnement $abonnement)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($abonnement->statut !== 'en_attente') {
            return back()->with(
                'error',
                'Seuls les abonnements en attente peuvent être activés.'
            );
        }

        $abonnement->update([
            'statut' => 'actif',
            'date_activation' => now(),
        ]);

        return redirect()
            ->route('abonnements.index')
            ->with(
                'success',
                'Abonnement activé avec succès. Les planifications ont été générées automatiquement.'
            );
    }

    /**
     * Rejet par l'administrateur
     */
    public function rejeter(Request $request, Abonnement $abonnement)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($abonnement->statut !== 'en_attente') {
            return back()->with(
                'error',
                'Seuls les abonnements en attente peuvent être rejetés.'
            );
        }

        $validated = $request->validate([
            'motif_rejet' => ['required', 'string', 'max:1000'],
        ]);

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
            ->route('abonnements.index')
            ->with(
                'success',
                'Abonnement rejeté avec succès. Le client a été notifié.'
            );
    }

    /**
     * Vérification des autorisations
     */
    protected function authorizeAbonnement(Abonnement $abonnement): void
    {
        abort_unless(
            auth()->user()->role === 'admin' ||
            auth()->id() === $abonnement->user_id,
            403,
            'Vous n’êtes pas autorisé à accéder à cet abonnement.'
        );
    }
}