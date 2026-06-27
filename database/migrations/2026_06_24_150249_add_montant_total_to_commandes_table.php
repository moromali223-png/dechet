<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMontantTotalToCommandesTable extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {

            $table->decimal('prix_unitaire', 10, 2)
                  ->after('quantite');

            $table->decimal('montant_total', 10, 2)
                  ->after('prix_unitaire');

        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {

            $table->dropColumn([
                'prix_unitaire',
                'montant_total'
            ]);

        });
    }
}

