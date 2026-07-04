<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trie extends Model
{
    use HasFactory;

    protected $table = 'tries';

    protected $fillable = [
        'pesage_id',
        'type_dechet',
        'quantite_trier',
        'unite',
        'qualite',
        'destination',
        'valeur_estimee',
        'notes',
    ];

    protected $casts = [
        'quantite_trier' => 'decimal:2',
        'valeur_estimee' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function pesage(): BelongsTo
    {
        return $this->belongsTo(Pesage::class);
    }

    /**
     * Relation vers l'agent (User avec rôle 'agent')
     * Via la pesée
     */
    public function agent()
    {
        return $this->pesage?->agent;   // agent() défini dans Pesage
    }

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByTypeDechet($query, string $type)
    {
        return $query->where('type_dechet', $type);
    }

    public function scopeQualite($query, string $qualite)
    {
        return $query->where('qualite', $qualite);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getQuantiteFormateeAttribute(): string
    {
        return number_format($this->quantite_trier ?? 0, 2) . ' ' . ($this->unite ?? 'kg');
    }

    public function getValeurEstimeeFormateeAttribute(): string
    {
        return $this->valeur_estimee 
            ? number_format($this->valeur_estimee, 2) . ' FCFA' 
            : 'Non estimée';
    }
}