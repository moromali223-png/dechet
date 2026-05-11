<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use Illuminate\Http\Request;

class MouvementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mouvements = Mouvement::with(['stock.produit', 'commande', 'user'])
            ->orderByDesc('date_mouvement')
            ->orderByDesc('heure_mouvement')
            ->paginate(20);

        return view('mouvements.index', compact('mouvements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Mouvement $mouvement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mouvement $mouvement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mouvement $mouvement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mouvement $mouvement)
    {
        //
    }
}
