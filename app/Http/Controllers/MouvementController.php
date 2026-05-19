<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use Illuminate\Http\Request;

class MouvementController extends Controller
{
    public function index()
    {
        $mouvements = Mouvement::with([
                'stock.produit',
                'commande',
                'user'
            ])
            ->orderByDesc('date_mouvement')
            ->orderByDesc('heure_mouvement')
            ->paginate(20);

        return view('mouvements.index', compact('mouvements'));
    }
}