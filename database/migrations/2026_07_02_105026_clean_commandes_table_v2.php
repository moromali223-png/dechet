<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {

            // supprimer foreign key si existe
            try {
                $table->dropForeign(['client_id']);
            } catch (\Exception $e) {}

            // supprimer colonnes inutiles
            if (Schema::hasColumn('commandes', 'client_id')) {
                $table->dropColumn('client_id');
            }

            if (Schema::hasColumn('commandes', 'produit')) {
                $table->dropColumn('produit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('produit')->nullable();
        });
    }
};