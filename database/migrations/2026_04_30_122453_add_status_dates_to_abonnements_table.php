<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->timestamp('date_activation')
                  ->nullable()
                  ->after('statut');

            $table->timestamp('date_rejet')
                  ->nullable()
                  ->after('date_activation');
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropColumn([
                'date_activation',
                'date_rejet',
            ]);
        });
    }
};