<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {

            // Supprimer colonne nom
            $table->dropColumn('nom');

        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {

            // Restaurer colonne nom
            $table->string('nom')->nullable();

        });
    }
};