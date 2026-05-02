<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trie extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesage_id',
        'type_dechet',
        'quantite_trier',
        'unite',
    ];

    /**
     * Un tri appartient à un pesage.
     */
    public function pesage(): BelongsTo
    {
        return $this->belongsTo(Pesage::class, 'pesage_id');
    }
}
