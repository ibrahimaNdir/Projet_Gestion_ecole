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
        Schema::create('matiere_classe_coefficients', function (Blueprint $table) {
            $table->id();
            // matiere_id (FK) : Lien vers la table 'matieres'
            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade'); // Supprime le coefficient si la matière est supprimée

            // classe_id (FK) : Lien vers la table 'classes'
            // Représente le niveau ou la classe spécifique pour ce coefficient
            $table->foreignId('classe_id')
                ->constrained('classes')
                ->onDelete('cascade'); // Supprime le coefficient si la classe est supprimée


            $table->integer('coefficient');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matiere_classe_coefficients');
    }
};
