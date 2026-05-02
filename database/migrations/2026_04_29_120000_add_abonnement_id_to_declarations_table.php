<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('declarations', 'abonnement_id')) {
            Schema::table('declarations', function (Blueprint $table) {
                $table->foreignId('abonnement_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('abonnements')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('declarations', 'abonnement_id')) {
            Schema::table('declarations', function (Blueprint $table) {
                $table->dropForeign(['abonnement_id']);
                $table->dropColumn('abonnement_id');
            });
        }
    }
};
