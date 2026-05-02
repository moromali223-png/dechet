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
        Schema::table('abonnements', function (Blueprint $table) {
            if (! Schema::hasColumn('abonnements', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('abonnements', 'frequence')) {
                $table->enum('frequence', ['hebdomadaire', 'mensuelle'])
                    ->nullable()
                    ->after('type_dechet');
            }

            if (! Schema::hasColumn('abonnements', 'jour_collecte')) {
                $table->string('jour_collecte')
                    ->nullable()
                    ->after('frequence');
            }

            if (! Schema::hasColumn('abonnements', 'poids_estime')) {
                $table->decimal('poids_estime', 10, 2)
                    ->default(0)
                    ->after('jour_collecte');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            if (Schema::hasColumn('abonnements', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('abonnements', 'frequence')) {
                $table->dropColumn('frequence');
            }

            if (Schema::hasColumn('abonnements', 'jour_collecte')) {
                $table->dropColumn('jour_collecte');
            }

            if (Schema::hasColumn('abonnements', 'poids_estime')) {
                $table->dropColumn('poids_estime');
            }
        });
    }
};
