<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('planifications', function (Blueprint $table) {
            $table->dropForeign(['collecteur_id']);
            $table->foreignId('abonnement_id')
                ->nullable()
                ->after('declaration_id')
                ->constrained('abonnements')
                ->nullOnDelete();
            $table->foreignId('agent_id')
                ->nullable()
                ->after('collecteur_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->unsignedSmallInteger('ordre_passage')
                ->nullable()
                ->after('agent_id');
            $table->unsignedSmallInteger('duree_estimee')
                ->nullable()
                ->after('ordre_passage');
            $table->unsignedTinyInteger('priorite')
                ->default(1)
                ->after('duree_estimee');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE planifications MODIFY collecteur_id BIGINT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planifications', function (Blueprint $table) {
            $table->dropForeign(['abonnement_id']);
            $table->dropForeign(['agent_id']);
            $table->dropColumn(['abonnement_id', 'agent_id', 'ordre_passage', 'duree_estimee', 'priorite']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE planifications MODIFY collecteur_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
