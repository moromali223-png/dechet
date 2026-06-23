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
            if (Schema::hasColumn('produits', 'trie_id')) {
                try {
                    $table->dropForeign(['trie_id']);
                } catch (\Throwable $e) {
                    // Ne pas échouer si la contrainte est déjà supprimée
                }
            }

            $columnsToDrop = [];

            foreach (['categorie', 'quantite', 'stock_disponible', 'trie_id'] as $column) {
                if (Schema::hasColumn('produits', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {

            $table->string('categorie')->nullable();

            $table->integer('quantite')->default(0);

            $table->integer('stock_disponible')->default(0);

            $table->foreignId('trie_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

        });
    }
};