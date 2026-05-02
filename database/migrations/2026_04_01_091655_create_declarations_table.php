<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->id();

            $table->string('type_dechet');                    // Type de déchet (Plastique, Métal, etc.)
            $table->decimal('poids_estime', 10, 2)->nullable(); // Poids en kg avec 2 décimales

            // Champ pour la photo (on le met nullable car tu vas l'ajouter plus tard)
            $table->string('photo')->nullable();

            // Champs recommandés (très utiles)
            $table->text('description')->nullable();
            $table->string('statut')->default('en_attente');   // en_attente, validée, rejetée, collectée...

            $table->foreignId('user_id')
                ->constrained('users')      // assure-toi que la table users existe
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declarations');
    }
};
