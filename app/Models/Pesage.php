<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesage extends Model
{
    use HasFactory;

    protected $table = 'pesage';

    protected $fillable = [
        'id_collecte',
        'poids',
        'unite',
        'description',
        'statut',
    ];

    /**
     * Un pesage appartient à une collecte (table collectes)
     */
    public function collecte(): BelongsTo
    {
        return $this->belongsTo(Collectes::class, 'id_collecte');
        // 'collecte' = nom de la relation (utilisé dans with('collecte'))
        // Collectes::class = nom du modèle
        // 'id_collecte' = nom de la colonne clé étrangère dans la table pesage
    }

    /**
     * Relation avec les tries (conservée)
     */
    public function tries()
    {
        return $this->hasMany(Trie::class, 'pesage_id');
    }
}
