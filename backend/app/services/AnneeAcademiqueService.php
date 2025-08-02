<?php

namespace App\services;
use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\DB;

class AnneeAcademiqueService
{
    /**
     * Récupère toutes les années académiques.
     *
     * @return Collection<int, AnneeAcademique>
     */
    public function index(): Collection
    {
        $anne = AnneeAcademique::all();
        return $anne;
    }

    /**
     * Crée une nouvelle année académique.
     *
     * @param array $data Les données validées pour la création (nom, date_debut, date_fin, actuell).
     * @return AnneeAcademique
     * @throws Exception Si la création échoue.
     */
    public function store(array $data): AnneeAcademique
    {
        try {
            // Si la nouvelle année est marquée comme 'actuell', désactiver toutes les autres.
            if (isset($data['actuell']) && $data['actuell'] === true) {
                AnneeAcademique::where('actuell', true)->update(['actuell' => false]);
            }
            return AnneeAcademique::create($data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la création de l\'année académique: ' . $e->getMessage());
        }
    }

    /**
     * Récupère une année académique spécifique par son ID.
     *
     * @param int $id L'ID de l'année académique.
     * @return AnneeAcademique|null
     */
    public function show(int $id): ?AnneeAcademique
    {
        return AnneeAcademique::find($id);
    }

    /**
     * Met à jour une année académique existante.
     *
     * @param int $id L'ID de l'année académique à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return AnneeAcademique|null L'année mise à jour ou null si non trouvée.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?AnneeAcademique
    {
        try {
            $annee = AnneeAcademique::find($id);

            if ($annee) {
                // Si l'année est marquée comme 'actuell', désactiver toutes les autres.
                // Assurez-vous que seule une année peut être active.
                if (isset($data['actuell']) && $data['actuell'] === true) {
                    AnneeAcademique::where('id', '!=', $id)->update(['actuell' => false]);
                }
                $annee->update($data);
            }

            return $annee;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour de l\'année académique: ' . $e->getMessage());
        }
    }

    /**
     * Supprime une année académique.
     *
     * @param int $id L'ID de l'année académique à supprimer.
     * @return bool Vrai si l'année a été supprimée, faux sinon.
     * @throws Exception Si la suppression échoue ou si l'année est active.
     */
    public function destroy(int $id): bool
    {
        $annee = AnneeAcademique::find($id);

        if (!$annee) {
            throw new Exception('Année académique non trouvée pour la suppression.');
        }

        if ($annee->actuell) {
            throw new Exception('Impossible de supprimer l\'année académique active.');
        }

        return (bool) $annee->delete();
    }

    /**
     * Récupère l'année académique actuellement active.
     *
     * @return AnneeAcademique|null L'instance de l'année académique active, ou null si aucune n'est active.
     */
    public function getCurrentActiveAnnee(): ?AnneeAcademique
    {
        return AnneeAcademique::where('actuell', true)->first();
    }

    /**
     * Récupère l'ID de l'année académique actuellement active.
     *
     * @return int|null L'ID de l'année académique active, ou null si aucune n'est active.
     * @throws Exception Si aucune année académique active n'est trouvée.
     */
    public function getCurrentActiveAnneeId(): ?int
    {
        $annee = $this->getCurrentActiveAnnee();
        if (!$annee) {
            throw new Exception("Aucune année académique active n'est définie dans le système. Veuillez contacter l'administrateur.");
        }
        return $annee->id;
    }

    /**
     * Définit une année académique comme active et désactive toutes les autres.
     *
     * @param int $anneeId L'ID de l'année académique à activer.
     * @return AnneeAcademique
     * @throws Exception Si l'année n'est pas trouvée ou si l'activation échoue.
     */
    public function setActiveAnnee(int $anneeId): AnneeAcademique
    {
        DB::beginTransaction();
        try {
            // Désactiver toutes les années actuellement actives
            AnneeAcademique::where('actuell', true)->update(['actuell' => false]);

            // Activer l'année spécifiée
            $annee = AnneeAcademique::find($anneeId);
            if (!$annee) {
                throw new Exception("L'année académique avec l'ID {$anneeId} n'a pas été trouvée.");
            }
            $annee->actuell = true;
            $annee->save();

            DB::commit();
            return $annee;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception('Erreur lors de l\'activation de l\'année académique: ' . $e->getMessage());
        }
    }

}
