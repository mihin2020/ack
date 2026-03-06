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
        Schema::table('seances', function (Blueprint $table) {
            $table->enum('paiement_statut', ['en_attente', 'valide', 'annule'])->default('en_attente')->after('statut');
            $table->integer('montant')->nullable()->after('paiement_statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->dropColumn(['paiement_statut', 'montant']);
        });
    }
};


