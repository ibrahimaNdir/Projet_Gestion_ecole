<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\EleveController;
use App\Http\Controllers\API\ParentController;
use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\BulletinsController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\EleveClasseController;
use App\Http\Controllers\EnseignantController as NewEnseignantController; // Utilisez un alias pour éviter les conflits
use App\Http\Controllers\EnseignantMatiereController;
use App\Http\Controllers\MatiereCoefClasseController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PeriodeEvaluationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ici, vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider et toutes seront
| assignées au groupe de middleware "api".
|
*/

// --- Routes publiques (pas d'authentification requise) ---
Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Vous pouvez également ajouter ici d'autres routes publiques
    Route::get('/nombre-eleves', [EleveController::class, 'compter']);
});

// --- Routes protégées par l'authentification (Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {

    // --- Routes d'Administration (rôle 'admin' requis) ---
    Route::middleware('role:admin')->group(function () {
        // Routes de votre branche 'feature/academic-structure' pour l'administration
        Route::apiResource('anneesacademiques', AnneeAcademiqueController::class);
        Route::post('anneesacademiques/set-active', [AnneeAcademiqueController::class, 'setActiveAnnee']);

        // Gestion des Enseignants (profils)
        Route::apiResource('enseignants', NewEnseignantController::class);
        Route::get('enseignants/count', [NewEnseignantController::class, 'countEnseignant']);

        // Gestion des Classes
        Route::apiResource('classes', ClasseController::class);
        Route::get('classes/count', [ClasseController::class, 'countClasses']);

        // Gestion des Matières
        Route::apiResource('matieres', MatiereController::class);
        Route::get('matieres/count', [MatiereController::class, 'countMatiere']);

        // Gestion des Périodes d'Évaluation
        Route::apiResource('periodes', PeriodeEvaluationController::class);

        // Gestion des Affectations Enseignant-Matière
        // Gère les opérations CRUD pour la ressource d'affectation enseignant-matière
        Route::apiResource('enseignants_matieres', EnseignantMatiereController::class);

// Affecte une matière à un enseignant
        Route::post('enseignants_matieres/affecter', [EnseignantMatiereController::class, 'affecter']);

// Retire une matière d'un enseignant
        Route::post('enseignants_matieres/retirer', [EnseignantMatiereController::class, 'retirer']);
// Récupère toutes les matières affectées à un enseignant donné
        Route::get('enseignants_matieres/by-enseignant', [EnseignantMatiereController::class, 'getMatieresByEnseignant']);
// Récupère tous les enseignants affectés à une matière donnée
        Route::get('enseignants_matieres/by-matiere', [EnseignantMatiereController::class, 'getEnseignantsByMatiere']);
// Récupère toutes les affectations d'enseignants pour une année scolaire spécifique
        Route::get('enseignants_matieres/by-annee', [EnseignantMatiereController::class, 'getAffectationsByAnnee']);
// Vérifie si une affectation spécifique enseignant-matière existe déjà
        Route::get('enseignants_matieres/check-exists', [EnseignantMatiereController::class, 'verifierAffectation']);


        // Gestion des Cours
        Route::apiResource('cours', CoursController::class);
        Route::post('cours/attribuer', [CoursController::class, 'attribuer']);
        Route::post('cours/retirer', [CoursController::class, 'retirer']);
        // Route pour obtenir tous les cours d'un enseignant pour l'année académique en cours .
        Route::get('cours/by-enseignant', [CoursController::class, 'getCoursByEnseignant']);
        Route::get('cours/by-matiere', [CoursController::class, 'getCoursByMatiere']);
        // Route pour Obtenir tous les cours d'une classe pour l'année académique en Cours
        Route::get('cours/by-classe', [CoursController::class, 'getCoursByClasse']);
        // Route pour obtenir les Cours de L'annee en Cours
        Route::get('cours/by-annee', [CoursController::class, 'getCoursByAnnee']);
        //Route qui Vérifie si un cours spécifique existe pour l'année académique active.
        Route::get('cours/check-exists', [CoursController::class, 'verifierCours']);

        // Gestion des Affectations Élève-Classe
        // Gère les ressources CRUD (Create, Read, Update, Delete) pour les affectations élève-classe
        Route::apiResource('eleves_classes', EleveClasseController::class);
// Récupère les classes d'un élève pour une année scolaire donnée
        Route::get('eleves_classes/by-eleve-annee', [EleveClasseController::class, 'getClassesByEleveAndAnnee']);
// Récupère tous les élèves d'une classe pour une année scolaire donnée
        Route::get('eleves_classes/by-classe-annee', [EleveClasseController::class, 'getElevesByClasseAndAnnee']);
// Récupère toutes les affectations d'élèves pour une année scolaire spécifique
        Route::get('eleves_classes/by-annee', [EleveClasseController::class, 'getAffectationsByAnnee']);
// Vérifie si une affectation spécifique élève-classe existe déjà
        Route::get('eleves_classes/check-exists', [EleveClasseController::class, 'affectationExists']);
// Récupère le nombre total d'affectations pour une année donnée
        Route::get('eleves_classes/nombre-affectations', [EleveClasseController::class, 'getNombreAffectations']);

        // Gestion des Coefficients Matière-Classe
        Route::apiResource('matiere_classe_coefficients', MatiereCoefClasseController::class);
        Route::post('matiere_classe_coefficients/store-or-update', [MatiereCoefClasseController::class, 'storeOrUpdate']);
        Route::get('matiere_classe_coefficients/get-coefficient', [MatiereCoefClasseController::class, 'getCoefficient']);
        Route::get('matiere_classe_coefficients/sum-by-classe', [MatiereCoefClasseController::class, 'getSumOfCoefficientsByClasse']);

        // Gestion des Notes


// Récupère toutes les notes d'un élève pour un cours spécifique
        Route::get('notes/by-eleve-cours', [NotesController::class, 'getNotesByEleveAndCours']);

        // Récupère toutes les notes d'un élève pour une période donnée
        Route::get('notes/by-eleve-periode', [NotesController::class, 'getNotesByEleveAndPeriode']);
        // Récupère les notes de tous les élèves pour un cours et une période spécifiques
        Route::get('notes/by-cours-periode', [NotesController::class, 'getNotesByCoursAndPeriode']);
        // Récupère les notes d'un élève pour un cours et une période donnés
        Route::get('notes/by-eleve-cours-periode', [NotesController::class, 'getNotesByEleveCoursPeriode']);
        // Calcule et récupère la moyenne des notes d'un élève pour un cours spécifique
        Route::get('notes/average-by-eleve-cours', [NotesController::class, 'getAverageNoteByEleveAndCours']);
        // Calcule et récupère la moyenne des notes d'un élève pour une période donnée
        Route::get('notes/average-by-eleve-periode', [NotesController::class, 'getAverageNoteByEleveAndPeriode']);


        // Gestion des Bulletins
        // Gère les ressources CRUD pour les bulletins de notes
        Route::apiResource('bulletins', BulletinsController::class);
        // Récupère les bulletins de notes d'un élève pour une année scolaire spécifique
        Route::get('bulletins/by-eleve-annee', [BulletinsController::class, 'getBulletinsByEleveAndAnnee']);
        // Récupère les bulletins de notes d'un élève pour une période donnée
        Route::get('bulletins/by-eleve-periode', [BulletinsController::class, 'getBulletinsByEleveAndPeriode']);
        // Récupère un bulletin de notes précis pour un élève, une année et une période
        Route::get('bulletins/by-eleve-annee-periode', [BulletinsController::class, 'getBulletinByEleveAnneePeriode']);
        // Génère un nouveau bulletin de notes
        Route::post('bulletins/generate', [BulletinsController::class, 'generateBulletin']);

        // Routes de votre branche 'mor_ndiaye' que vous souhaitez conserver (gestion des documents et parents)
        // Gère les opérations CRUD (Create, Read, Update, Delete) pour la ressource Eleve
        Route::apiResource('eleves', EleveController::class);

// Gère les opérations CRUD pour la ressource Parent
        Route::apiResource('parents', ParentController::class);
// Associe un élève existant à un parent existant
        Route::post('parents/{parent}/attach-eleve/{eleve}', [ParentController::class, 'attachEleve']);
// Envoie et stocke un nouveau document pour un élève spécifique
        Route::post('documents/{eleve}', [DocumentController::class, 'store']);
// Supprime un document spécifique de la base de données
        Route::delete('documents/{document}', [DocumentController::class, 'destroy']);
    });

    // --- Routes Enseignant (rôle 'enseignant' requis) ---
    Route::middleware('role:enseignant')->group(function () {
        // L'enseignant peut gérer ses propres notes et consulter ses cours
        Route::post('notes', [NotesController::class, 'store']); // Saisie de notes
        Route::put('notes/{id}', [NotesController::class, 'update']); // Mise à jour de ses notes
        Route::delete('notes/{id}', [NotesController::class, 'destroy']); // Suppression de ses notes

        // Consultation de ses cours pour l'année active
        Route::get('enseignant/mes-cours', [CoursController::class, 'getCoursByEnseignant']);
        // Route::get('enseignant/mon-emploi-du-temps', [CoursController::class, 'getEmploiDuTempsEnseignant']);
        // La liste de ses Matiere qu'il dispense
        Route::get('enseignant/mes-matieres', [EnseignantMatiereController::class, 'getMatieresByEnseignant']);

        // Consultation des notes de ses élèves pour ses cours
        Route::apiResource('notes', NotesController::class);
        Route::get('notes/mes-cours/{cours_id}/periode/{periode_id}', [NotesController::class, 'getNotesByCoursAndPeriode']);
        Route::get('notes/mes-eleves/{eleve_id}/cours/{cours_id}', [NotesController::class, 'getNotesByEleveAndCours']);
        Route::get('notes/mes-eleves/{eleve_id}/periode/{periode_id}', [NotesController::class, 'getNotesByEleveAndPeriode']);
        Route::get('notes/moyenne-eleve-cours', [NotesController::class, 'getAverageNoteByEleveAndCours']);
        Route::get('notes/moyenne-eleve-periode', [NotesController::class, 'getAverageNoteByEleveAndPeriode']);
    });

    // --- Routes Élève (rôle 'eleve' requis) ---
    Route::middleware('role:eleve')->group(function () {
        // L'élève peut consulter ses propres informations
        Route::get('eleve/ma-classes', [EleveClasseController::class, 'getClassesByEleveAndAnnee']);
        Route::get('eleve/mes-notes', [NotesController::class, 'getNotesByEleveAndPeriode']);
        Route::get('eleve/mes-bulletins', [BulletinsController::class, 'getBulletinsByEleveAndAnnee']);
        // Route::get('eleve/mon-emploi-du-temps', [CoursController::class, 'getEmploiDuTempsClasse']);
    });

    // --- Routes Parent (rôle 'parent' requis) ---
    Route::middleware('role:parent')->group(function () {
        // Le parent peut consulter les informations de ses enfants (élèves)
        // Les routes ci-dessous nécessitent une logique d'autorisation spécifique pour vérifier
        // que l'élève demandé est bien un enfant de l'utilisateur connecté.
        Route::get('parent/mes-enfants/{eleve_id}/notes', [NotesController::class, 'getNotesByEleveAndPeriode']);
        Route::get('parent/mes-enfants/{eleve_id}/bulletins', [BulletinsController::class, 'getBulletinsByEleveAndAnnee']);
        // Route::get('parent/mes-enfants/{eleve_id}/emploi-du-temps', [CoursController::class, 'getEmploiDuTempsClasse']);
    });
});
