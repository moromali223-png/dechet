<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('commandes', 'user_id')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('client_id')->constrained('users')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('commandes', 'client_id')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->index(['client_id', 'statut']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropIndex(['client_id', 'statut']);
        });
    }
};
