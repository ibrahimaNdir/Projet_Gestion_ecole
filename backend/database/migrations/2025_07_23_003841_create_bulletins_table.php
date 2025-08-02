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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            // eleve_id (FK) : Lien vers Élèves.
            $table->foreignId('eleve_id')
                ->constrained('eleves') // Référence la table 'eleves'
                ->onDelete('cascade'); // Supprime les bulletins si l'élève est supprimé

            // annee_academique_id (FK) : Lien vers AnnéesAcadémiques.
            $table->foreignId('annee_academique_id')
                ->constrained('annees_academiques') // Référence la table 'annees_academiques'
                ->onDelete('cascade'); // Supprime les bulletins si l'année académique est supprimée

            // periode_evaluation_id (FK) : Lien vers PeriodesEvaluation.
            $table->foreignId('periode_evaluation_id')
                ->constrained('periodes_evaluation') // Référence la table 'periodes_evaluation'
                ->onDelete('cascade'); // Supprime les bulletins si la période d'évaluation est supprimée

            // Contrainte d'unicité composite sur eleve_id, annee_academique_id, et periode_evaluation_id.
            // Cela garantit qu'un élève ne peut avoir qu'un seul bulletin pour une période
            // donnée dans une année académique donnée.
            $table->unique(['eleve_id', 'annee_academique_id', 'periode_evaluation_id'], 'unique_eleve_annee_periode_bulletin');

            // date_generation (Date) : Date de génération du PDF.
            $table->date('date_generation'); // Cette date est généralement obligatoire

            // url_bulletin_pdf (Chaîne de caractères) : Chemin d'accès au fichier PDF du bulletin.
            $table->string('url_bulletin_pdf')->nullable(); // Peut être nul si le PDF n'est pas encore généré

            // moyenne_generale (Numérique, ex: DECIMAL(4,2), optionnel).
            $table->decimal('moyenne_generale', 4, 2)->nullable(); // 4 chiffres au total, 2 après la virgule (ex: 99.99)

            // mention (Chaîne de caractères, optionnel) : Ex: "Assez Bien".
            $table->string('mention')->nullable();

            // rang_classe (Entier, optionnel).
            $table->integer('rang_classe')->nullable();

            // appreciation_generale (Texte, optionnel).
            $table->text('appreciation_generale')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
