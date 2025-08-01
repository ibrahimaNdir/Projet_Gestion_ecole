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
        Schema::create('enseignantmatiere', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers la table 'enseignants'
            $table->foreignId('enseignant_id')
                ->nullable()
                ->constrained('enseignants')
                ->onDelete('cascade');

            // Clé étrangère vers la table 'matieres'
            $table->foreignId('matiere_id')
                ->nullable()
                ->constrained('matieres')
                ->onDelete('cascade');

            // Clé étrangère vers la table 'annees_academiques'
            $table->foreignId('annee_academique_id')
                ->nullable()
                ->constrained('annees_academiques')
                ->onDelete('cascade');


            $table->unique(['enseignant_id', 'matiere_id', 'annee_academique_id'], 'unique_enseignant_matiere_annee');

            // Champs pour suivre la création et la dernière modification de l'affectation.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignantmatiere');
    }
};
