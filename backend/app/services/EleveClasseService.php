<?php

namespace App\services;

use App\Models\EleveClasse;

class EleveClasseService
{

    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    /**
     * Récupère toutes les affectations Élève-Classe.
     * Charge les relations avec l'élève, la classe et l'année académique.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder[]
     */
    public function index(): array|\Illuminate\Database\Eloquent\Collection
    {
        return EleveClasse::with(['eleve', 'classe', 'anneeAcademique'])->get();
    }

    /**
     * Crée une nouvelle affectation Élève-Classe.
     * Assigne automatiquement l'année académique active si non fournie.
     *
     * @param array $data Les données validées pour la création (eleve_id, classe_id, annee_academique_id, date_affectation).
     * @return EleveClasse L'instance de l'affectation créée ou existante.
     * @throws Exception Si la création échoue.
     */
    public function store(array $data): EleveClasse
    {
        try {
            if (!isset($data['annee_academique_id'])) {
                $data['annee_academique_id'] = $this->anneeAcademiqueService->getCurrentActiveAnneeId();
            }
            return EleveClasse::firstOrCreate(
                [
                    'eleve_id' => $data['eleve_id'],
                    'classe_id' => $data['classe_id'],
                    'annee_academique_id' => $data['annee_academique_id'],
                ],
                [
                    'date_affectation' => $data['date_affectation'] ?? now(),
                ]
            );
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la création de l'affectation Élève-Classe : " . $e->getMessage());
        }
    }

    /**
     * Récupère une affectation Élève-Classe spécifique par son ID.
     * Charge les relations avec l'élève, la classe et l'année académique.
     *
     * @param int $id L'ID de l'affectation.
     * @return EleveClasse|null
     */
    public function show(int $id): ?EleveClasse
    {
        return EleveClasse::with(['eleve', 'classe', 'anneeAcademique'])->find($id);
    }

    /**
     * Met à jour une affectation Élève-Classe existante.
     *
     * @param int $id L'ID de l'affectation à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return EleveClasse|null L'affectation mise à jour ou null si non trouvée.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?EleveClasse
    {
        try {
            $affectation = EleveClasse::find($id);

            if ($affectation) {
                $affectation->update($data);
                $affectation->load(['eleve', 'classe', 'anneeAcademique']); // Recharger les relations après mise à jour
            }

            return $affectation;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la mise à jour de l'affectation Élève-Classe : " . $e->getMessage());
        }
    }

    /**
     * Supprime une affectation Élève-Classe.
     *
     * @param int $id L'ID de l'affectation à supprimer.
     * @return bool Vrai si l'affectation a été supprimée, faux sinon.
     */
    public function destroy(int $id): bool
    {
        return (bool) EleveClasse::destroy($id);
    }



    /**
     * Récupère tous les élèves affectés à une classe pour l'année académique active.
     *
     * @param int $classeId L'ID de la classe.
     * @return Collection<int, Eleve>
     */
    public function getElevesByClasse(int $classeId): Collection
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        $affectations = EleveClasse::where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with('eleve')
            ->get();

        return $affectations->map(fn($affectation) => $affectation->eleve)->filter()->values();
    }

    /**
     * Récupère toutes les affectations Élève-Classe pour une année académique spécifique (pour l'admin).
     *
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return Collection<int, EleveClasse>
     */
    public function getAffectationsByAnnee(int $anneeAcademiqueId): Collection
    {
        return EleveClasse::where('annee_academique_id', $anneeAcademiqueId)
            ->with(['eleve', 'classe', 'anneeAcademique'])
            ->get();
    }



    /**
     * Vérifie si une affectation Élève-Classe spécifique existe pour l'année académique active.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $classeId L'ID de la classe.
     * @return bool
     */
    public function affectationExists(int $eleveId, int $classeId): bool
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return EleveClasse::where('eleve_id', $eleveId)
            ->where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->exists();
    }

    /**
     * Obtenir le nombre total d'affectations Élève-Classe pour une année académique spécifique (pour l'admin).
     *
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return int
     */
    public function getNombreAffectations(int $anneeAcademiqueId): int
    {
        return EleveClasse::where('annee_academique_id', $anneeAcademiqueId)->count();
    }

    /**
     * Obtenir le nombre d'élèves affectés à une classe spécifique pour l'année académique active.
     *
     * @param int $classeId L'ID de la classe.
     * @return int Le nombre d'élèves affectés.
     */
    public function getNombreElevesByClasse(int $classeId): int
    {
        $anneeAcademiqueId = $this->anneeAcademiqueService->getCurrentActiveAnneeId(); // Utilise l'année active

        return EleveClasse::where('classe_id', $classeId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->count();
    }

}
