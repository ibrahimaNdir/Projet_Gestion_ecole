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
        Schema::create('periodes_evaluation', function (Blueprint $table) {
            $table->id();
            $table->string('nom_periode');
            $table->foreignId('annee_academique_id')->constrained('annees_academiques')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamps();

            // Contrainte d'unicité : un nom de période unique par année académique
            $table->unique(['nom_periode', 'annee_academique_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes_evaluation');
    }
};
