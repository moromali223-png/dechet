<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Mouvement extends Model
{
    protected $table = 'mouvements';

    protected $fillable = [
        'stock_id',
        'type_mouvement',
        'quantite',
        'source',
        'description',
        'commande_id',
        'date_mouvement',
        'heure_mouvement',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'date_mouvement' => 'date',
        'heure_mouvement' => 'datetime:H:i:s',
    ];

    /**
     * Normalise le type de mouvement pour stocker sous forme standardisée.
     */
    public function setTypeMouvementAttribute(string $value): void
    {
        $normalized = Str::lower(Str::ascii($value));

        if ($normalized === 'sortie') {
            $this->attributes['type_mouvement'] = 'sortie';

            return;
        }

        $this->attributes['type_mouvement'] = 'entrée';
    }

    public function getTypeMouvementLabelAttribute(): string
    {
        $normalized = Str::upper(Str::ascii($this->type_mouvement));

        return $normalized === 'SORTIE' ? 'Sortie' : 'Entrée';
    }

    /**
     * Relation avec Stock
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Relation avec Commande
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Scopes utiles
     */
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entrée');
    }

    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }

    public function scopeParCommande($query, $commandeId)
    {
        return $query->where('commande_id', $commandeId);
    }
}
