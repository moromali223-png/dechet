<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('planifications')) {
            Schema::table('planifications', function (Blueprint $table) {
                if (! Schema::hasColumn('planifications', 'collecteur_id')) {
                    $table->unsignedBigInteger('collecteur_id')->nullable();
                }

                if (! Schema::hasColumn('planifications', 'agent_id')) {
                    $table->unsignedBigInteger('agent_id')->nullable();
                }
            });
        }

        if (Schema::hasTable('commandes')) {
            Schema::table('commandes', function (Blueprint $table) {
                if (! Schema::hasColumn('commandes', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('planifications')) {
            Schema::table('planifications', function (Blueprint $table) {
                $table->dropColumn(['collecteur_id', 'agent_id']);
            });
        }
    }
};
