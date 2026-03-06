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
        Schema::create('enfants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('parent_id', 36);
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('classe');
            $table->string('programme')->nullable();
            $table->integer('nombre_seances_total')->default(0);
            $table->integer('nombre_seances_utilisees')->default(0);
            $table->enum('statut_paiement', ['paye', 'non_paye'])->default('non_paye');
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('parents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfants');
    }
};
