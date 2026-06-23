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
        Schema::table('mouvements', function (Blueprint $table) {
            if (! Schema::hasColumn('mouvements', 'prix_unitaire')) {
                $table->decimal('prix_unitaire', 15, 2)->after('quantite')->default(0);
            }

            if (! Schema::hasColumn('mouvements', 'montant_total')) {
                $table->decimal('montant_total', 15, 2)->after('prix_unitaire')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            if (Schema::hasColumn('mouvements', 'montant_total')) {
                $table->dropColumn('montant_total');
            }

            if (Schema::hasColumn('mouvements', 'prix_unitaire')) {
                $table->dropColumn('prix_unitaire');
            }
        });
    }
};
