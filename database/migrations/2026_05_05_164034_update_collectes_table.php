<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('collectes', function (Blueprint $table) {

            // 🧠 amélioration statut
            $table->enum('statut', [
                'en_cours',
                'terminee',
                'annulee',
            ])->default('en_cours')->change();

            // 📝 commentaire terrain
            $table->text('commentaire')->nullable();

            // ⏱ tracking simple
            $table->timestamp('heure_depart')->nullable();
            $table->timestamp('heure_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
