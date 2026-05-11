<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('planifications', function (Blueprint $table) {
            $table->timestamp('heure_depart')->nullable();
            $table->timestamp('heure_arrivee')->nullable();
            $table->timestamp('heure_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planifications', function (Blueprint $table) {
            //
        });
    }
};
