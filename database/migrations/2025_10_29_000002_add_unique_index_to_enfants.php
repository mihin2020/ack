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
        Schema::table('enfants', function (Blueprint $table) {
            $table->unique(['parent_id', 'nom', 'prenom', 'date_naissance'], 'enfants_parent_nom_prenom_dna_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enfants', function (Blueprint $table) {
            $table->dropUnique('enfants_parent_nom_prenom_dna_unique');
        });
    }
};


