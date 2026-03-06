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
        Schema::create('seances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('enfant_id', 36);
            $table->date('date_seance');
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->enum('statut', ['reservee', 'effectuee', 'reporter', 'manquee'])->default('reservee');
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('enfant_id')->references('id')->on('enfants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
