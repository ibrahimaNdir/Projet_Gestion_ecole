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
        Schema::create('eleveclasse', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers la table 'eleves'
            $table->foreignId('eleve_id')
                ->nullable()
                ->constrained('eleves')
                ->onDelete('cascade');

            // Clé étrangère vers la table 'classes'
            $table->foreignId('classe_id')
                ->nullable()
                ->constrained('classes')
                ->onDelete('cascade');

            // Clé étrangère vers la table 'annees_academiques'
            $table->foreignId('annee_academique_id')
                ->nullable()
                ->constrained('annees_academiques')
                ->onDelete('cascade');

            $table->date('date_affectation')->nullable();


            $table->unique(['eleve_id', 'classe_id', 'annee_academique_id'], 'unique_eleve_classe_annee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleveclasse');
    }
};
