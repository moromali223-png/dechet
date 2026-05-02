<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->foreignId('abonnement_id')
                ->nullable()
                ->constrained('abonnements')
                ->nullOnDelete()
                ->after('user_id');

            $table->index(['abonnement_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropIndex(['abonnement_id', 'created_at']);
            $table->dropForeign(['abonnement_id']);
            $table->dropColumn('abonnement_id');
        });
    }
};
