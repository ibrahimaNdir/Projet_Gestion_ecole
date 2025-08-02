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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();

            // eleve_id (FK) : Lien vers Élèves.
            $table->foreignId('eleve_id')
                ->constrained('eleves') // Référence la table 'eleves'
                ->onDelete('cascade'); // Supprime les notes si l'élève est supprimé

            // cours_id (FK) : Lien vers Cours (qui inclut enseignant, matière, classe, année).
            $table->foreignId('cours_id')
                ->constrained('cours') // Référence la table 'cours'
                ->onDelete('cascade'); // Supprime les notes si le cours est supprimé

            // valeur_note (Numérique, ex: DECIMAL(4,2) pour 15.50).
            // Utilise 4 chiffres au total, dont 2 après la virgule (ex: 15.50, 9.75, 20.00).
            $table->integer('valeur_note');

            // date_saisie (Date) : Date à laquelle la note a été saisie.
            $table->date('date_saisie');

            // periode_evaluation_id (FK) : Lien vers PeriodesEvaluation.
            // Permet de situer la note dans un trimestre/semestre pour les calculs de bulletins.
            $table->foreignId('periode_evaluation_id')
                ->constrained('periodes_evaluation') // Référence la table 'periodes_evaluation'
                ->onDelete('cascade'); // Supprime les notes si la période est supprimée

            // type_evaluation (Chaîne de caractères, optionnel) : Ex: "Devoir Maison", "Examen".
            $table->string('type_evaluation')->nullable();

            // commentaire_enseignant (Texte, optionnel).
            $table->text('commentaire_enseignant')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
