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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            // Colonne `enseignant_id` (FK) : Lien vers Enseignants.
            $table->foreignId('enseignant_id')
                ->constrained('enseignants')
                ->onDelete('cascade');

            // Colonne `matiere_id` (FK) : Lien vers Matières.
            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade');


            $table->foreignId('classe_id')
                ->constrained('classes')
                ->onDelete('cascade');


            $table->foreignId('annee_academique_id')
                ->constrained('annees_academiques')
                ->onDelete('cascade');


            $table->unique(['enseignant_id', 'matiere_id', 'classe_id', 'annee_academique_id'], 'unique_cours_instance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
