<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('type_abonnement');
            $table->enum('frequence', ['hebdomadaire', 'mensuelle']);
            $table->string('jour_collecte');
            $table->decimal('poids_estime', 10, 2)->default(0);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['actif', 'expiré', 'annulé', 'en_attente', 'rejete'])->default('en_attente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
