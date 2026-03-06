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
        // Modifier l'enum pour ajouter le statut 'manquee'
        \DB::statement("ALTER TABLE seances MODIFY COLUMN statut ENUM('reservee', 'effectuee', 'reporter', 'manquee') DEFAULT 'reservee'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ancien enum
        \DB::statement("ALTER TABLE seances MODIFY COLUMN statut ENUM('reservee', 'effectuee', 'reporter') DEFAULT 'reservee'");
    }
};
