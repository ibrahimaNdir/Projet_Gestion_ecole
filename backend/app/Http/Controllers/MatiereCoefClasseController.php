<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatiereCoefClasseRequest;
use App\services\MatiereCoefClasseService;
use Illuminate\Http\JsonResponse;

class MatiereCoefClasseController
{


    protected MatiereCoefClasseService $matiereClasseCoefficientService;

    public function __construct(MatiereCoefClasseService $matiereClasseCoefficientService)
    {
        $this->matiereClasseCoefficientService = $matiereClasseCoefficientService;
    }

    /**
     * Affiche une liste de tous les coefficients matière-classe.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $coefficients = $this->matiereClasseCoefficientService->index();
            return response()->json($coefficients, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des coefficients : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée ou met à jour un coefficient matière-classe.
     *
     * @param StoreMatiereClasseCoefficientRequest $request
     * @return JsonResponse
     */
    public function storeOrUpdate(StoreMatiereClasseCoefficientRequest $request): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreMatiereClasseCoefficientRequest
            $coefficient = $this->matiereClasseCoefficientService->storeOrUpdate($request->validated());

            return response()->json([
                'success' => true,
                'data' => $coefficient,
                'message' => 'Coefficient créé ou mis à jour avec succès.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'opération sur le coefficient : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Affiche un coefficient spécifique.
     *
     * @param int $id L'ID du coefficient.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $coefficient = $this->matiereClasseCoefficientService->show($id);

            if (!$coefficient) {
                return response()->json(['message' => 'Coefficient non trouvé'], 404);
            }

            return response()->json($coefficient, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du coefficient : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour un coefficient existant.
     * Note: La validation est la même que pour 'storeOrUpdate'.
     *
     * @param StoreMatiereClasseCoefficientRequest $request
     * @param int $id L'ID du coefficient à mettre à jour.
     * @return JsonResponse
     */
    public function update(StoreMatiereClasseCoefficientRequest $request, int $id): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreMatiereClasseCoefficientRequest
            $coefficient = $this->matiereClasseCoefficientService->update($id, $request->validated());

            if (!$coefficient) {
                return response()->json(['message' => 'Coefficient non trouvé'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $coefficient,
                'message' => 'Coefficient mis à jour avec succès.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du coefficient : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprime un coefficient.
     *
     * @param int $id L'ID du coefficient à supprimer.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->matiereClasseCoefficientService->destroy($id);

            if (!$deleted) {
                return response()->json(['message' => 'Coefficient non trouvé ou suppression échouée'], 404);
            }

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du coefficient : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère le coefficient d'une matière pour une classe donnée.
     * annee_academique_id n'est plus requis dans la requête.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCoefficient(MatiereCoefClasseRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $coefficient = $this->matiereClasseCoefficientService->getCoefficient(
                $validated['matiere_id'],
                $validated['classe_id']
            );

            if ($coefficient === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coefficient non trouvé pour les critères spécifiés.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'coefficient' => $coefficient,
                'message' => 'Coefficient récupéré avec succès.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du coefficient : ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Récupère la somme totale des coefficients pour une classe donnée.
     * annee_academique_id n'est plus requis dans la requête.
     *
     * @param MatiereCoefClasseRequest $request
     * @return JsonResponse
     */
    public function getSumOfCoefficientsByClasse(MatiereCoefClasseRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $totalCoefficients = $this->matiereClasseCoefficientService->getTotalCoefficientsByClasse(
                $validated['classe_id']
            );

            return response()->json([
                'success' => true,
                'total_coefficients' => $totalCoefficients,
                'message' => 'Somme des coefficients récupérée avec succès.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la somme des coefficients : ' . $e->getMessage()
            ], 500);
        }
    }
}
