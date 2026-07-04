<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'code_commande',
        'user_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'statut',
        'date_commande',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_commande' => 'datetime',
    ];

    public const STATUTS = [
        'en_attente' => 'En attente',
        'acceptee' => 'Acceptée',
        'refusee' => 'Refusée',
        'en_preparation' => 'En préparation',
        'livree' => 'Livrée',
        'annulee' => 'Annulée',
    ];

    // ================= RELATIONS =================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
   
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }


        // Dans class Commande extends Model
   
    // ================= SCOPES =================

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptee($query)
    {
        return $query->where('statut', 'acceptee');
    }

    public function scopeLivree($query)
    {
        return $query->where('statut', 'livree');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ================= ACCESSORS =================

    public function getStatutFormateAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? ucfirst($this->statut);
    }

    public function getMontantTotalFormateAttribute(): string
    {
        return number_format($this->montant_total ?? 0, 2) . ' FCFA';
    }
}