<?php

namespace App\services;

use App\Models\Cours;

class CoursService
{
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    /**
     * Récupère tous les cours.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder[]
     */
    public function index(): array|\Illuminate\Database\Eloquent\Collection
    {
        // Récupère toutes les instances de cours avec leurs relations pour un affichage complet.
        return Cours::with(['enseignant', 'matiere', 'classe', 'anneeAcademique'])->get();
    }

    /**
     * Crée un nouveau cours.
     *
     * @param array $data Les données validées pour la création (enseignant_id, matiere_id, classe_id, annee_academique_id).
     * @return Cours
     * @throws Exception Si la création échoue.
     */
    public function store(array $data): Cours
    {
        try {
            // Si annee_academique_id n'est pas fourni, utilise l'année active par défaut
            if (!isset($data['annee_academique_id'])) {
                $data['annee_academique_id'] = $this->anneeAcademiqueService->getCurrentActiveAnneeId();
            }
            return Cours::create($data);
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la création du cours : " . $e->getMessage());
        }
    }

    /**
     * Récupère un cours spécifique par son ID.
     *
     * @param int $id L'ID du cours.
     * @return Cours|null
     */
    public function show(int $id): ?Cours
    {
        // Trouve un cours par son ID et charge ses relations. Retourne null si non trouvé.
        return Cours::with(['enseignant', 'matiere', 'classe', 'anneeAcademique'])->find($id);
    }

    /**
     * Met à jour un cours existant.
     *
     * @param int $id L'ID du cours à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return Cours|null Le cours mis à jour ou null si non trouvé.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?Cours
    {
        try {
            $cours = Cours::find($id);

            if ($cours) {
                $cours->update($data);
                $cours->load(['enseignant', 'matiere', 'classe', 'anneeAcademique']); // Recharger les relations après mise à jour
            }

            return $cours;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la mise à jour du cours : " . $e->getMessage());
        }
    }

    /**
     * Supprime un cours.
     *
     * @param int $id L'ID du cours à supprimer.
     * @return bool Vrai si le cours a été supprimé, faux sinon.
     */
    public function destroy(int $id): bool
    {
        // Trouve le cours par ID et le supprime.
        // Retourne true si la suppression a réussi, false si l'élément n'a pas été trouvé.
        return (bool) Cours::destroy($id);
    }

    /**
     * Attribue un cours (crée une nouvelle instance de cours).
     * Utilise l'année académique active par défaut.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @param int $matiereId L'ID de la matière.
     * @param int $classeId L'ID de la classe.
     * @return Cours L'instance du cours créée ou existante.
     * @throws Exception Si la création échoue.
     */
    public function attribuerCours(int $enseignantId, int $matiereId, int $classeId): Cours
    {
        try {
            $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

            return Cours::firstOrCreate(
                [
                    'enseignant_id' => $enseignantId,
                    'matiere_id' => $matiereId,
                    'classe_id' => $classeId,
                    'annee_academique_id' => $anneeAcademiqueId,
                ]
            );
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de l'attribution du cours : " . $e->getMessage());
        }
    }

    /**
     * Retire un cours spécifique.
     * Utilise l'année académique active par défaut.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @param int $matiereId L'ID de la matière.
     * @param int $classeId L'ID de la classe.
     * @return bool Vrai si le cours a été retiré, faux sinon.
     */
    public function retirerCours(int $enseignantId, int $matiereId, int $classeId): bool
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return (bool) Cours::where('enseignant_id', $enseignantId)
            ->where('matiere_id', $matiereId)
            ->where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->delete();
    }

    /**
     * Obtenir tous les cours d'un enseignant pour l'année académique active.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @return Collection<int, Cours>
     */
    public function getCoursByEnseignant(int $enseignantId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Cours::where('enseignant_id', $enseignantId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['matiere', 'classe', 'anneeAcademique'])
            ->get();
    }

    /**
     * Obtenir tous les cours d'une matière pour l'année académique active.
     *
     * @param int $matiereId L'ID de la matière.
     * @return Collection<int, Cours>
     */
    public function getCoursByMatiere(int $matiereId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Cours::where('matiere_id', $matiereId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['enseignant', 'classe', 'anneeAcademique'])
            ->get();
    }

    /**
     * Obtenir tous les cours d'une classe pour l'année académique active.
     *
     * @param int $classeId L'ID de la classe.
     * @return Collection<int, Cours>
     */
    public function getCoursByClasse(int $classeId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Cours::where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['enseignant', 'matiere', 'anneeAcademique'])
            ->get();
    }

    /**
     * Obtenir tous les cours pour une année académique spécifique (pour l'admin ou historique).
     * Cette méthode conserve le paramètre anneeAcademiqueId car elle est destinée à la consultation historique.
     *
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return Collection<int, Cours>
     */
    public function getCoursByAnnee(int $anneeAcademiqueId): Collection
    {
        return Cours::where('annee_academique_id', $anneeAcademiqueId)
            ->with(['enseignant', 'matiere', 'classe'])
            ->get();
    }



    /**
     * Vérifie si un cours spécifique existe pour l'année académique active.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @param int $matiereId L'ID de la matière.
     * @param int $classeId L'ID de la classe.
     * @return bool
     */
    public function coursExists(int $enseignantId, int $matiereId, int $classeId): bool
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Cours::where('enseignant_id', $enseignantId)
            ->where('matiere_id', $matiereId)
            ->where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->exists();
    }


    /**
     * Obtenir l'emploi du temps d'un enseignant pour l'année académique active.
     *
     * @param int $enseignantId L'ID de l'enseignant.
     * @return Collection<int, Cours>
     */
    public function getEmploiDuTempsEnseignant(int $enseignantId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Cours::where('enseignant_id', $enseignantId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['matiere', 'classe']) // Charger les détails de la matière et de la classe
            ->get();
    }

    /**
     * Obtenir l'emploi du temps d'une classe pour l'année académique active.
     *
     * @param int $classeId L'ID de la classe.
     * @return Collection<int, Cours>
     */
    public function getEmploiDuTempsClasse(int $classeId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return Cours::where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['enseignant', 'matiere']) // Charger les détails de l'enseignant et de la matière
            ->get();
    }

}
