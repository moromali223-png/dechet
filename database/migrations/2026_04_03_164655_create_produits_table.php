<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();

            $table->string('nom')->unique();                    // Nom du produit (ex: Plastique PET, Compost, etc.)
            $table->string('type');
            $table->decimal('quantite');                           // Type : 'recycle', 'consommable', 'matiere_premiere', 'equipement'
            $table->string('unite_mesure')->default('kg');      // kg, pièce, litre, tonne...
            $table->decimal('prix_unitaire', 12, 2)->nullable(); // Prix de vente ou de référence
            $table->text('description')->nullable();
            // Date de fabrication / production
            $table->string('statut')->default('actif');         // actif, inactif, obsolete

            $table->foreignId('trie_id')
                ->constrained('tries')   // nom correct de la table
                ->onDelete('cascade');
            $table->timestamps();

            // Index pour recherches rapides
            $table->index(['type', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
