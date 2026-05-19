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
        Schema::table('produits', function (Blueprint $table) {
            $table->string('categorie')->nullable()->after('description');
            $table->decimal('stock_disponible', 10, 2)->default(0)->after('prix_unitaire');
            $table->enum('statut', ['actif', 'inactif'])->default('actif')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['categorie', 'stock_disponible']);
            $table->string('statut')->change(); // Revenir à string si nécessaire
        });
    }
};
