<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();                    // Clé primaire auto-incrémentée (recommandé)
            $table->string('code_commande')->unique();   // Ton IdCommande métier (ex: CMD-20260403-001)
            $table->string('produit');                 // À améliorer plus tard (voir remarque ci-dessous)
            $table->decimal('quantite', 12, 2);
            $table->string('statut')->default('en_attente'); // en_attente, validée, livrée, annulée...

            // Clé étrangère vers Client (comme tu l'as demandé)
            $table->foreignId('client_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->date('date_commande')->nullable(); // Date de la commande

            $table->timestamps();

            // Index pour meilleures performances
            $table->index(['client_id', 'statut', 'date_commande']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
