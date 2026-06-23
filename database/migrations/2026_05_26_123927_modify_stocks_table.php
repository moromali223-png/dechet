<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Suppression sécurisée : évite l'erreur 1091 si la colonne est déjà partie
            if (Schema::hasColumn('stocks', 'commande_id')) {
                // On tente de supprimer la contrainte. Si elle n'existe pas/plus, on ignore l'exception
                try {
                    $table->dropForeign(['commande_id']);
                } catch (\Exception $e) {}
                
                $table->dropColumn('commande_id');
            }

            // Ajout sécurisé de trie_id
            if (!Schema::hasColumn('stocks', 'trie_id')) {
                // foreignId crée un BigInt Unsigned par défaut. 
                // Si l'erreur 150 persiste, essayez : $table->unsignedInteger('trie_id')->nullable();
                $table->foreignId('trie_id')
                    ->nullable()
                    ->constrained('tries')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'trie_id')) {
                $table->dropForeign(['trie_id']);
                $table->dropColumn('trie_id');
            }

            if (!Schema::hasColumn('stocks', 'commande_id')) {
                $table->foreignId('commande_id')
                    ->nullable()
                    ->constrained('commandes')
                    ->onDelete('set null');
            }
        });
    }
};