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
            $table->string('rue')->nullable();
            $table->string('quartier')->nullable();
            $table->string('ville')->nullable();
            $table->string('repere')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropColumn(['rue', 'quartier', 'ville', 'repere']);
        });
    }
};
