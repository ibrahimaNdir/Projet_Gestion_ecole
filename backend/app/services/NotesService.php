<?php

namespace App\services;

use App\Models\Notes;
use Laravel\Prompts\Note;

class NotesService
{

    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    /**
     * Récupère toutes les notes.
     * Charge les relations avec l'élève, le cours et la période d'évaluation.
     *
     * @return Collection<int, Note>
     */
    public function index(): Collection
    {
        return Note::with(['eleve', 'cours.matiere', 'cours.enseignant', 'cours.classe', 'periodeEvaluation'])->get();
    }


    /**
     * Crée une nouvelle note.
     *
     * @param array $data Les données validées pour la création.
     * @return Note L'instance de la note créée.
     * @throws Exception Si la création échoue.
     */
    public function store(array $data): Note
    {
        try {
            // S'assurer que le cours de la note est bien lié à l'année active si non spécifié
            // Le cours lui-même devrait déjà être lié à une année académique.
            // La validation de StoreNoteRequest assure que cours_id et periode_evaluation_id existent.
            return Note::create($data);
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la création de la note : " . $e->getMessage());
        }
    }

    /**
     * Récupère une note spécifique par son ID.
     * Charge les relations avec l'élève, le cours et la période d'évaluation.
     *
     * @param int $id L'ID de la note.
     * @return Note|null
     */
    public function show(int $id): ?Note
    {
        return Note::with(['eleve', 'cours.matiere', 'cours.enseignant', 'cours.classe', 'periodeEvaluation'])->find($id);
    }

    /**
     * Met à jour une note existante.
     *
     * @param int $id L'ID de la note à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return Note|null La note mise à jour ou null si non trouvée.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?Note
    {
        try {
            $note = Note::find($id);

            if ($note) {
                $note->update($data);
                $note->load(['eleve', 'cours.matiere', 'cours.enseignant', 'cours.classe', 'periodeEvaluation']); // Recharger les relations
            }

            return $note;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la mise à jour de la note : " . $e->getMessage());
        }
    }

    /**
     * Supprime une note.
     *
     * @param int $id L'ID de la note à supprimer.
     * @return bool Vrai si la note a été supprimée, faux sinon.
     */
    public function destroy(int $id): bool
    {
        return (bool) Note::destroy($id);
    }

    /**
     * Récupère toutes les notes d'un élève pour un cours spécifique, pour l'année active.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $coursId L'ID du cours.
     * @return Collection<int, Note>
     */
    public function getNotesByEleveAndCours(int $eleveId, int $coursId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Note::where('eleve_id', $eleveId)
            ->where('cours_id', $coursId)
            ->whereHas('cours', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->with(['periodeEvaluation']) // Charger la période d'évaluation pour le contexte
            ->get();
    }

    /**
     * Récupère toutes les notes d'un élève pour une période d'évaluation donnée, pour l'année active.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Collection<int, Note>
     */
    public function getNotesByEleveAndPeriode(int $eleveId, int $periodeEvaluationId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Note::where('eleve_id', $eleveId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->whereHas('periodeEvaluation', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->with(['cours.matiere', 'cours.enseignant']) // Charger les détails du cours et de la matière/enseignant
            ->get();
    }

    /**
     * Récupère toutes les notes pour un cours spécifique et une période d'évaluation donnée, pour l'année active.
     *
     * @param int $coursId L'ID du cours.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Collection<int, Note>
     */
    public function getNotesByCoursAndPeriode(int $coursId, int $periodeEvaluationId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Note::where('cours_id', $coursId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->whereHas('cours', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->whereHas('periodeEvaluation', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->with(['eleve']) // Charger les détails de l'élève
            ->get();
    }

    /**
     * Récupère toutes les notes pour un élève, un cours et une période d'évaluation spécifiques, pour l'année active.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $coursId L'ID du cours.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Collection<int, Note>
     */
    public function getNotesByEleveCoursPeriode(int $eleveId, int $coursId, int $periodeEvaluationId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Note::where('eleve_id', $eleveId)
            ->where('cours_id', $coursId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->whereHas('cours', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->whereHas('periodeEvaluation', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->get();
    }

    /**
     * Calcule la moyenne des notes d'un élève pour un cours spécifique, pour l'année active.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $coursId L'ID du cours.
     * @return float La moyenne calculée, ou 0.0 si aucune note.
     */
    public function getAverageNoteByEleveAndCours(int $eleveId, int $coursId): float
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return (float) Note::where('eleve_id', $eleveId)
            ->where('cours_id', $coursId)
            ->whereHas('cours', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->avg('valeur_note');
    }

    /**
     * Calcule la moyenne des notes d'un élève pour une période d'évaluation donnée, pour l'année active.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return float La moyenne calculée, ou 0.0 si aucune note.
     */
    public function getAverageNoteByEleveAndPeriode(int $eleveId, int $periodeEvaluationId): float
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return (float) Note::where('eleve_id', $eleveId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->whereHas('periodeEvaluation', function ($query) use ($anneeAcademiqueId) {
                $query->where('annee_academique_id', $anneeAcademiqueId);
            })
            ->avg('valeur_note');
    }

}
