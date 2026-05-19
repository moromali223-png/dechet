<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Abonnement;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'typeclient',
        'zone_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    // AJOUTER CECI
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class, 'user_id', 'user_id');
    }
}