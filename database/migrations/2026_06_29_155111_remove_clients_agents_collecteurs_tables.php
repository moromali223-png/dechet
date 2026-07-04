<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Désactiver toutes les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        /**
         * 2. Supprimer toutes les foreign keys connues proprement
         */
        $tables = [
            'planifications',
            'abonnements',
            'declarations',
        ];

        foreach ($tables as $table) {

            // récupérer toutes les FK du table
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$table]);

            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("
                        ALTER TABLE `$table`
                        DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`
                    ");
                } catch (\Throwable $e) {
                    // ignore
                }
            }
        }

        /**
         * 3. Supprimer les tables
         */
        Schema::dropIfExists('clients');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('collecteurs');

        // 4. Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        //
    }
};