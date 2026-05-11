<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tries', function (Blueprint $table) {
            $table->enum('qualite', ['Excellent', 'Bon', 'Moyen', 'Mauvais'])
                ->default('Bon')
                ->after('quantite_trier');

            $table->text('notes')->nullable()->after('qualite');

            $table->string('destination')->nullable()->after('notes');

            $table->decimal('valeur_estimee', 12, 2)->nullable()
                ->after('destination')
                ->comment('Valeur estimée en FCFA');
        });
    }

    public function down()
    {
        Schema::table('tries', function (Blueprint $table) {
            $table->dropColumn(['qualite', 'notes', 'destination', 'valeur_estimee']);
        });
    }
};
