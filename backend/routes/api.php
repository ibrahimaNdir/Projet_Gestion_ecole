<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importez tous vos contrôleurs

use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\BulletinsController; // Utilisé tel quel depuis q.txt
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\EleveClasseController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EnseignantMatiereController;
use App\Http\Controllers\MatiereCoefClasseController; // Utilisé tel quel depuis q.txt
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NotesController; // Utilisé tel quel depuis q.txt
use App\Http\Controllers\PeriodeEvaluationController;
// Si vous avez un EleveController séparé pour la gestion des profils des élèves, importez-le ici
// use App\Http\Controllers\EleveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// --- Routes publiques (pas d'authentification requise) ---
//Route::post('/login', [LoginController::class, 'login']);
// Ajoutez ici d'autres routes publiques si nécessaire (ex: inscription)

// --- Routes protégées par l'authentification (Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {

    // Route pour obtenir les informations de l'utilisateur connecté
   /** Route::get('/user', function (Request $request) {
        // Charge la relation de rôle si elle existe sur le modèle User
        return $request->user()->load('role');
    });**/

    // --- Routes d'Administration (rôle 'admin' requis) ---
    Route::middleware('role:admin')->group(function () {

        // Gestion des Années Académiques
        Route::apiResource('anneesacademiques', AnneeAcademiqueController::class);
        Route::post('anneesacademiques/set-active', [AnneeAcademiqueController::class, 'setActiveAnnee']);

        // Gestion des Enseignants (profils)
        Route::apiResource('enseignants', EnseignantController::class);
        Route::get('enseignants/count', [EnseignantController::class, 'countEnseignant']);

        // Gestion des Classes
        Route::apiResource('classes', ClasseController::class);
        Route::get('classes/count', [ClasseController::class, 'countClasses']);

        // Gestion des Matières
        Route::apiResource('matieres', MatiereController::class);
        Route::get('matieres/count', [MatiereController::class, 'countMatiere']);

        // Gestion des Périodes d'Évaluation
        Route::apiResource('periodes', PeriodeEvaluationController::class);
        // Les méthodes getPeriodesByAnnee et getPeriodeByNomAndAnnee ne sont pas dans le contrôleur fourni
        // Si elles existent dans le service et devraient être exposées, ajoutez-les ici.
        // Route::get('periodes/by-annee', [PeriodeEvaluationController::class, 'getPeriodesByAnnee']);
        // Route::get('periodes/by-nom-annee', [PeriodeEvaluationController::class, 'getPeriodeByNomAndAnnee']);


        // Gestion des Affectations Enseignant-Matière
        Route::apiResource('enseignants_matieres', EnseignantMatiereController::class);
        Route::post('enseignants_matieres/affecter', [EnseignantMatiereController::class, 'affecter']);
        Route::post('enseignants_matieres/retirer', [EnseignantMatiereController::class, 'retirer']);
        Route::get('enseignants_matieres/by-enseignant', [EnseignantMatiereController::class, 'getMatieresByEnseignant']);
        Route::get('enseignants_matieres/by-matiere', [EnseignantMatiereController::class, 'getEnseignantsByMatiere']);
        Route::get('enseignants_matieres/by-annee', [EnseignantMatiereController::class, 'getAffectationsByAnnee']);
        Route::get('enseignants_matieres/check-exists', [EnseignantMatiereController::class, 'verifierAffectation']);
        // Route::post('enseignants_matieres/synchroniser', [EnseignantMatiereController::class, 'synchroniser']); // Non présent dans le contrôleur fourni
        // Route::get('enseignants_matieres/statistiques', [EnseignantMatiereController::class, 'getStatistiques']); // Non présent dans le contrôleur fourni


        // Gestion des Cours
        Route::apiResource('cours', CoursController::class);
        Route::post('cours/attribuer', [CoursController::class, 'attribuer']);
        Route::post('cours/retirer', [CoursController::class, 'retirer']);
        Route::get('cours/by-enseignant', [CoursController::class, 'getCoursByEnseignant']);
        Route::get('cours/by-matiere', [CoursController::class, 'getCoursByMatiere']);
        Route::get('cours/by-classe', [CoursController::class, 'getCoursByClasse']);
        Route::get('cours/by-annee', [CoursController::class, 'getCoursByAnnee']);
        Route::get('cours/check-exists', [CoursController::class, 'verifierCours']);
        //Route::get('cours/statistiques', [CoursController::class, 'getStatistiques']);
        //Route::get('cours/emploi-du-temps/enseignant', [CoursController::class, 'getEmploiDuTempsEnseignant']);
        //Route::get('cours/emploi-du-temps/classe', [CoursController::class, 'getEmploiDuTempsClasse']);
        // Route::post('cours/synchroniser-enseignant', [CoursController::class, 'synchroniserEnseignant']); // Non présent dans le contrôleur fourni
        // Route::post('cours/synchroniser-classe', [CoursController::class, 'synchroniserClasse']); // Non présent dans le contrôleur fourni


        // Gestion des Affectations Élève-Classe
        Route::apiResource('eleves_classes', EleveClasseController::class);
        Route::get('eleves_classes/by-eleve-annee', [EleveClasseController::class, 'getClassesByEleveAndAnnee']);
        Route::get('eleves_classes/by-classe-annee', [EleveClasseController::class, 'getElevesByClasseAndAnnee']);
        Route::get('eleves_classes/by-annee', [EleveClasseController::class, 'getAffectationsByAnnee']);
        Route::get('eleves_classes/check-exists', [EleveClasseController::class, 'affectationExists']);
        Route::get('eleves_classes/nombre-affectations', [EleveClasseController::class, 'getNombreAffectations']);
        Route::get('eleves_classes/nombre-eleves-by-classe', [EleveClasseController::class, 'getNombreElevesByClasseAndAnnee']);


        // Gestion des Coefficients Matière-Classe
        Route::apiResource('matiere_classe_coefficients', MatiereCoefClasseController::class);
        // Note: Le contrôleur MatiereCoefClasseController a une méthode 'storeOrUpdate'
        // qui peut remplacer 'store' et 'update' de apiResource si vous le souhaitez.
        // Si vous utilisez 'storeOrUpdate' comme point d'entrée unique pour la création/mise à jour,
        // vous pouvez exclure 'store' et 'update' de apiResource:
        // Route::apiResource('matiere_classe_coefficients', MatiereCoefClasseController::class)->except(['store', 'update']);
        Route::post('matiere_classe_coefficients/store-or-update', [MatiereCoefClasseController::class, 'storeOrUpdate']);
        Route::get('matiere_classe_coefficients/get-coefficient', [MatiereCoefClasseController::class, 'getCoefficient']);
        Route::get('matiere_classe_coefficients/sum-by-classe', [MatiereCoefClasseController::class, 'getSumOfCoefficientsByClasse']);
        // La méthode getCoefficientsByClasseAndAnnee n'est pas dans le contrôleur fourni
        // Route::get('matiere_classe_coefficients/by-classe-annee', [MatiereCoefClasseController::class, 'getCoefficientsByClasseAndAnnee']);


        // Gestion des Notes
        Route::apiResource('notes', NotesController::class);
        Route::get('notes/by-eleve-cours', [NotesController::class, 'getNotesByEleveAndCours']);
        Route::get('notes/by-eleve-periode', [NotesController::class, 'getNotesByEleveAndPeriode']);
        Route::get('notes/by-cours-periode', [NotesController::class, 'getNotesByCoursAndPeriode']);
        Route::get('notes/by-eleve-cours-periode', [NotesController::class, 'getNotesByEleveCoursPeriode']);
        Route::get('notes/average-by-eleve-cours', [NotesController::class, 'getAverageNoteByEleveAndCours']);
        Route::get('notes/average-by-eleve-periode', [NotesController::class, 'getAverageNoteByEleveAndPeriode']);

        // Gestion des Bulletins
        Route::apiResource('bulletins', BulletinsController::class);
        Route::get('bulletins/by-eleve-annee', [BulletinsController::class, 'getBulletinsByEleveAndAnnee']);
        Route::get('bulletins/by-eleve-periode', [BulletinsController::class, 'getBulletinsByEleveAndPeriode']);
        Route::get('bulletins/by-eleve-annee-periode', [BulletinsController::class, 'getBulletinByEleveAnneePeriode']);
        Route::post('bulletins/generate', [BulletinsController::class, 'generateBulletin']);
    });


    // --- Routes Enseignant (rôle 'enseignant' requis) ---
    Route::middleware('role:enseignant')->group(function () {
        // L'enseignant peut gérer ses propres notes et consulter ses cours
        Route::post('notes', [NotesController::class, 'store']); // Saisie de notes
        Route::put('notes/{id}', [NotesController::class, 'update']); // Mise à jour de ses notes
        Route::delete('notes/{id}', [NotesController::class, 'destroy']); // Suppression de ses notes

        // Consultation de ses cours pour l'année active
        Route::get('enseignant/mes-cours', [CoursController::class, 'getCoursByEnseignant']);
        Route::get('enseignant/mon-emploi-du-temps', [CoursController::class, 'getEmploiDuTempsEnseignant']);
        Route::get('enseignant/mes-matieres', [EnseignantMatiereController::class, 'getMatieresByEnseignant']);

        // Consultation des notes de ses élèves pour ses cours
        Route::get('notes/mes-cours/{cours_id}/periode/{periode_id}', [NotesController::class, 'getNotesByCoursAndPeriode']);
        Route::get('notes/mes-eleves/{eleve_id}/cours/{cours_id}', [NotesController::class, 'getNotesByEleveAndCours']);
        Route::get('notes/mes-eleves/{eleve_id}/periode/{periode_id}', [NotesController::class, 'getNotesByEleveAndPeriode']);
        Route::get('notes/moyenne-eleve-cours', [NotesController::class, 'getAverageNoteByEleveAndCours']);
        Route::get('notes/moyenne-eleve-periode', [NotesController::class, 'getAverageNoteByEleveAndPeriode']);

        // Consultation des bulletins de ses élèves (si professeur principal)
        // Nécessiterait une logique pour identifier le professeur principal d'une classe
        // Route::get('bulletins/mes-classes/{classe_id}/eleves/{eleve_id}', [BulletinsController::class, 'getBulletinByEleveAnneePeriode']);
    });

    // --- Routes Élève (rôle 'eleve' requis) ---
    Route::middleware('role:eleve')->group(function () {
        // L'élève peut consulter ses propres informations
        Route::get('eleve/mes-classes', [EleveClasseController::class, 'getClassesByEleveAndAnnee']);
        Route::get('eleve/mes-notes', [NotesController::class, 'getNotesByEleveAndPeriode']);
        Route::get('eleve/mes-bulletins', [BulletinsController::class, 'getBulletinsByEleveAndAnnee']);
        Route::get('eleve/mon-emploi-du-temps', [CoursController::class, 'getEmploiDuTempsClasse']);
    });

    // --- Routes Parent (rôle 'parent' requis) ---
    Route::middleware('role:parent')->group(function () {
        // Le parent peut consulter les informations de ses enfants (élèves)
        // Cela nécessiterait une relation entre 'parents' et 'eleves' et une logique d'autorisation
        // Pour chaque enfant:
        // Route::get('parent/mes-enfants/{eleve_id}/notes', [NotesController::class, 'getNotesByEleveAndPeriode']);
        // Route::get('parent/mes-enfants/{eleve_id}/bulletins', [BulletinsController::class, 'getBulletinsByEleveAndAnnee']);
        // Route::get('parent/mes-enfants/{eleve_id}/emploi-du-temps', [CoursController::class, 'getEmploiDuTempsClasse']);
    });

});
