<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeclarationRequest;
use App\Http\Requests\UpdateDeclarationRequest;
use App\Models\Declaration;
use App\Models\Planification;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Support\Facades\Storage;

class DeclarationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Declaration::class, 'declaration');
    }

    public function index()
    {
        // Retrieve the authenticated user.
        // The 'auth' middleware on the route should ensure a user is logged in,
        // but an explicit check adds robustness against unexpected scenarios.
        $user = Auth::user();

        if (! $user) {
            // If no authenticated user is found, deny access.
            // This should ideally be handled by middleware or policy, but acts as a safeguard.
            abort(403, 'Vous devez être connecté pour accéder à vos déclarations.');
        }

        $declarations = $user->declarations()->latest()->paginate(15);

        return view('client.declarations.index', compact('declarations'));
    }

    public function create()
    {
        return view('declarations.create');
    }

    public function store(StoreDeclarationRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();

        $data['user_id'] = $user->id;
        $data['statut'] = 'en_attente';

        // 🔥 récupérer abonnement actif
        $abonnement = $user->abonnements()
            ->where('statut', 'actif')
            ->latest()
            ->first();

        if ($abonnement) {
            $data['abonnement_id'] = $abonnement->id;
        }

        Declaration::create($data);

        return redirect()
            ->route('client.declarations.index')
            ->with('success', 'Déclaration créée avec succès.');
    }

    public function valider(Declaration $declaration)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($declaration->statut !== 'en_attente') {
            return back()->with('error', 'Déjà traité');
        }

        $declaration->update(['statut' => 'valide']);

        $planification = Planification::createFromDeclaration($declaration);

        return redirect()
            ->route('planifications.edit', $planification)
            ->with('success', 'Planification proposée, veuillez ajuster.');
    }

    public function show(Declaration $declaration)
    {
        return view('client.declarations.show', compact('declaration'));
    }

    public function edit(Declaration $declaration)
    {
        abort_if($declaration->statut !== 'en_attente', 403, 'Cette déclaration ne peut plus être modifiée.');

        return view('client.declarations.edit', compact('declaration'));
    }

    public function update(UpdateDeclarationRequest $request, Declaration $declaration)
    {
        abort_if($declaration->statut !== 'en_attente', 403, 'Cette déclaration ne peut plus être modifiée.');

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($declaration->photo) {
                Storage::disk('public')->delete($declaration->photo);
            }

            $data['photo'] = $request->file('photo')->store('declarations', 'public');
        }

        $declaration->update($data);

        return redirect()->route('declarations.show', $declaration)
            ->with('success', 'Déclaration mise à jour avec succès.');
    }

    public function destroy(Declaration $declaration)
    {
        abort_if($declaration->statut !== 'en_attente', 403, 'Cette déclaration ne peut pas être supprimée.');

        if ($declaration->photo) {
            Storage::disk('public')->delete($declaration->photo);
        }

        $declaration->delete();

        return redirect()->route('client.declarations.index')
            ->with('success', 'Déclaration supprimée avec succès.');
    }

    public function adminIndex()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $declarations = Declaration::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.declarations.index', compact('declarations'));
    }

    public function rejeter(Request $request, Declaration $declaration)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($declaration->statut !== 'en_attente') {
            return back()->with('error', 'Déjà traité');
        }

        $declaration->update([
            'statut' => 'rejete',
        ]);

        return back()->with('success', 'Déclaration rejetée');
    }
}
