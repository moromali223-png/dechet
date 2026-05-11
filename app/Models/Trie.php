<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trie extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesage_id',
        'type_dechet',
        'quantite_trier',
        'unite',
        'qualite',
        'destination',       // ← Ajouté
        'valeur_estimee',    // ← Ajouté
        'notes',             // ← Ajouté
    ];

    // Relation avec Pesage
    public function pesage()
    {
        return $this->belongsTo(Pesage::class);
    }
}
