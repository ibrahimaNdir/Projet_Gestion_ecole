<?php

namespace App\services;

use App\Models\MatiereCoefClasse;

class MatiereCoefClasseService
{
    public function index()
    {
        return MatiereCoefClasse::with(['matiere', 'classe'])->get();
    }

    public function store( array $request)
    {
        $matcoef =  MatiereCoefClasse::create($request);
        return $matcoef;
    }

    public function destroy($id)
    {
        MatiereCoefClasse::destroy($id);
        return true;
    }
    /**
     * Récupère le coefficient d'une matière pour une classe donnée.
     * anneeAcademiqueId n'est plus requis.
     *
     * @param int $matiereId L'ID de la matière.
     * @param int $classeId L'ID de la classe.
     * @return int|null Le coefficient trouvé, ou null si non trouvé.
     */
    public function getCoefficient(int $matiereId, int $classeId): ?int
    {
        $coefficient = MatiereClasseCoefficient::where('matiere_id', $matiereId)
            ->where('classe_id', $classeId)
            // annee_academique_id est retiré du filtre
            ->value('coefficient');

        return $coefficient !== null ? (int) $coefficient : null;
    }



    /**
     * Calcule la somme des coefficients pour une classe donnée.
     * anneeAcademiqueId n'est plus requis.
     *
     * @param int $classeId L'ID de la classe.
     * @return float La somme totale des coefficients, ou 0.0 si aucun coefficient n'est trouvé.
     */
    public function getTotalCoefficientsByClasse(int $classeId): float
    {
        return (float) MatiereClasseCoefficient::where('classe_id', $classeId)
            // annee_academique_id est retiré du filtre
            ->sum('coefficient');
    }


}
