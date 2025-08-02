<?php

namespace App\services;

use App\Models\Enseignant;
use App\Models\EnseignantMatiere;
use App\Models\Matiere;
use App\Models\AnneeAcademique;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class EnseignantMatiereService
{
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }
    /**
     * Récupère toutes les affectations Enseignant-Matière.
     * Charge les relations avec l'enseignant, la matière et l'année académique.
     *
     * @return Collection<int, EnseignantMatiere>
     */
    public function index(): Collection
    {
        return EnseignantMatiere::with(['enseignant', 'matiere', 'anneeAcademique'])->get();
    }

    /**
     * Crée une nouvelle affectation Enseignant-Matière.
     * Utilise firstOrCreate pour gérer l'unicité des combinaisons.
     *
     * @param array $data Les données validées pour la création (enseignant_id, matiere_id, annee_academique_id).
     * @return EnseignantMatiere L'instance de la liaison créée ou existante.
     * @throws Exception Si la création échoue pour une raison inattendue.
     */
    public function store(array $data): EnseignantMatiere
    {
        try {
            // Utilise firstOrCreate pour éviter les doublons basés sur la contrainte d'unicité
            // (enseignant_id, matiere_id, annee_academique_id)
            return EnseignantMatiere::firstOrCreate(
                [
                    'enseignant_id' => $data['enseignant_id'],
                    'matiere_id' => $data['matiere_id'],
                    'annee_academique_id' => $data['annee_academique_id'],
                ]
            );
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la création de l'affectation Enseignant-Matière : " . $e->getMessage());
        }
    }

    /**
     * Récupère une affectation Enseignant-Matière spécifique par son ID.
     * Charge les relations avec l'enseignant, la matière et l'année académique.
     *
     * @param int $id L'ID de l'affectation.
     * @return EnseignantMatiere|null
     */
    public function show(int $id): ?EnseignantMatiere
    {
        return EnseignantMatiere::with(['enseignant', 'matiere', 'anneeAcademique'])->find($id);
    }

    /**
     * Met à jour une affectation Enseignant-Matière existante.
     *
     * @param int $id L'ID de l'affectation à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return EnseignantMatiere|null L'affectation mise à jour ou null si non trouvée.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?EnseignantMatiere
    {
        try {
            $affectation = EnseignantMatiere::find($id);

            if ($affectation) {
                $affectation->update($data);
                $affectation->load(['enseignant', 'matiere', 'anneeAcademique']); // Recharger les relations après mise à jour
            }

            return $affectation;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la mise à jour de l'affectation Enseignant-Matière : " . $e->getMessage());
        }
    }

    /**
     * Supprime une affectation Enseignant-Matière.
     *
     * @param int $id L'ID de l'affectation à supprimer.
     * @return bool Vrai si l'affectation a été supprimée, faux sinon.
     */
    public function destroy(int $id): bool
    {
        return (bool) EnseignantMatiere::destroy($id);
    }


    /**
     * Affecte une matière à un enseignant pour l'année académique active.
     * Crée une nouvelle entrée dans la table enseignant_matiere.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @param int $matiereId L'ID de la matière.
     * @return EnseignantMatiere L'instance de la liaison créée ou existante.
     * @throws Exception Si la création échoue pour une raison inattendue.
     */
    public function affecterMatiere(int $enseignantId, int $matiereId): EnseignantMatiere
    {
        try {
            $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

            return EnseignantMatiere::firstOrCreate(
                [
                    'enseignant_id' => $enseignantId,
                    'matiere_id' => $matiereId,
                    'annee_academique_id' => $anneeAcademiqueId,
                ]
            );
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de l'affectation de la matière : " . $e->getMessage());
        }
    }

    /**
     * Retire une matière d'un enseignant pour l'année académique active.
     * Supprime une entrée spécifique de la table enseignant_matiere.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @param int $matiereId L'ID de la matière.
     * @return bool Vrai si la liaison a été supprimée, faux sinon.
     */
    public function retirerMatiere(int $enseignantId, int $matiereId): bool
    {
        try {
            $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

            $deleted = EnseignantMatiere::where('enseignant_id', $enseignantId)
                ->where('matiere_id', $matiereId)
                ->where('annee_academique_id', $anneeAcademiqueId)
                ->delete();

            if ($deleted === 0) {
                throw new Exception('Affectation non trouvée pour le retrait.');
            }

            return true;
        } catch (Exception $e) {
            throw new Exception('Erreur lors du retrait de l\'affectation: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir toutes les matières d'un enseignant pour l'année académique active.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @return Collection<int, Matiere>
     */
    public function getMatieresByEnseignant(int $enseignantId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        $affectations = EnseignantMatiere::where('enseignant_id', $enseignantId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with('matiere')
            ->get();

        return $affectations->map(fn($affectation) => $affectation->matiere)->filter()->values();
    }

    /**
     * Obtenir tous les enseignants d'une matière pour l'année académique active.
     *
     * @param int $matiereId L'ID de la matière.
     * @return Collection<int, Enseignant>
     */
    public function getEnseignantsByMatiere(int $matiereId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        $affectations = EnseignantMatiere::where('matiere_id', $matiereId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with('enseignant')
            ->get();

        return $affectations->map(fn($affectation) => $affectation->enseignant)->filter()->values();
    }

    /**
     * Obtenir toutes les affectations pour une année académique spécifique (pour l'admin).
     * Cette méthode conserve le paramètre anneeAcademiqueId car elle est destinée à la consultation historique.
     *
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return Collection<int, EnseignantMatiere>
     */
    public function getAffectationsByAnnee(int $anneeAcademiqueId): Collection
    {
        return EnseignantMatiere::where('annee_academique_id', $anneeAcademiqueId)
            ->with(['enseignant', 'matiere', 'anneeAcademique'])
            ->get();
    }


    /**
     * Vérifie si une affectation spécifique existe pour l'année académique active.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @param int $matiereId L'ID de la matière.
     * @return bool
     */
    public function affectationExists(int $enseignantId, int $matiereId): bool
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return EnseignantMatiere::where('enseignant_id', $enseignantId)
            ->where('matiere_id', $matiereId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->exists();
    }

    /**
     * Obtenir le nombre total d'affectations pour une année académique spécifique (pour l'admin).
     *
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return int
     */
    public function getNombreAffectations(int $anneeAcademiqueId): int
    {
        return EnseignantMatiere::where('annee_academique_id', $anneeAcademiqueId)->count();
    }

}
