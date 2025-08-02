<?php

namespace App\Http\Controllers;

use App\Models\EnseignantMatiere;
use App\services\EnseignantMatiereService;
use Illuminate\Http\Request;
use App\Http\Requests\EnseignantMatiereRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;

class EnseignantMatiereController extends Controller
{
    protected EnseignantMatiereService $enseignantMatiereService;

    public function __construct(EnseignantMatiereService $enseignantMatiereService)
    {
        $this->enseignantMatiereService = $enseignantMatiereService;
    }

    /**
     * Affiche une liste de toutes les affectations Enseignant-Matière.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Le service index() charge déjà les relations et l'année académique si nécessaire
            $affectations = $this->enseignantMatiereService->index();
            return response()->json($affectations, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des affectations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée une nouvelle affectation Enseignant-Matière.
     *
     * @param StoreEnseignantMatiereRequest $request
     * @return JsonResponse
     */
    public function store(StoreEnseignantMatiereRequest $request): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreEnseignantMatiereRequest
            // Le service store() gère l'annee_academique_id si non fourni
            $affectation = $this->enseignantMatiereService->store($request->validated());
            return response()->json($affectation, 201); // 201 Created
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'affectation: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Affiche une affectation Enseignant-Matière spécifique.
     *
     * @param int $id L'ID de l'affectation.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $affectation = $this->enseignantMatiereService->show($id);

            if (!$affectation) {
                return response()->json(['message' => 'Affectation non trouvée'], 404); // 404 Not Found
            }

            return response()->json($affectation, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'affectation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour une affectation Enseignant-Matière existante.
     *
     * @param StoreEnseignantMatiereRequest $request
     * @param int $id L'ID de l'affectation à mettre à jour.
     * @return JsonResponse
     */
    public function update(StoreEnseignantMatiereRequest $request, int $id): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreEnseignantMatiereRequest
            $affectation = $this->enseignantMatiereService->update($id, $request->validated());

            if (!$affectation) {
                return response()->json(['message' => 'Affectation non trouvée'], 404); // 404 Not Found
            }

            return response()->json($affectation, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'affectation: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprime une affectation Enseignant-Matière.
     *
     * @param int $id L'ID de l'affectation à supprimer.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->enseignantMatiereService->destroy($id);

            if (!$deleted) {
                return response()->json(['message' => 'Affectation non trouvée ou suppression échouée'], 404); // 404 Not Found
            }

            return response()->json(null, 204); // 204 No Content (pour une suppression réussie)
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'affectation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affecter une matière à un enseignant pour une année académique
     */
    public function affecter(EnseignantMatiereRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->enseignantMatiereService->affecterMatiere(
                $validated['enseignant_id'],
                $validated['matiere_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Matière affectée avec succès à l\'enseignant'
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Retirer une matière d'un enseignant pour une année académique
     */
    public function retirer(EnseignantMatiereRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->enseignantMatiereService->retirerMatiere(
                $validated['enseignant_id'],
                $validated['matiere_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Matière retirée avec succès de l\'enseignant'
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
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Obtenir toutes les matières d'un enseignant pour une année académique
     */
    public function getMatieresByEnseignant(EnseignantMatiereRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $matieres = $this->enseignantMatiereService->getMatieresByEnseignant(
                $validated['enseignant_id'],
                $validated['annee_academique_id']
            );

            return response()->json($matieres,200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des matières'
            ], 500);
        }
    }

    /**
     * Obtenir tous les enseignants d'une matière pour une année académique
     */
    public function getEnseignantsByMatiere(EnseignantMatiereRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $enseignants = $this->enseignantMatiereService->getEnseignantsByMatiere(
                $validated['matiere_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $enseignants,
                'message' => 'Enseignants récupérés avec succès'
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
                'message' => 'Erreur lors de la récupération des enseignants'
            ], 500);
        }
    }









    /**
     * Obtenir toutes les affectations pour une année académique
     */
    public function getAffectationsByAnnee(Request $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $affectations = $this->enseignantMatiereService->getAffectationsByAnnee(
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $affectations,
                'message' => 'Affectations récupérées avec succès'
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
                'message' => 'Erreur lors de la récupération des affectations'
            ], 500);
        }
    }




    /**
     * Vérifier si une affectation existe
     */
    public function verifierAffectation(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'enseignant_id' => 'required|integer|exists:enseignants,id',
                'matiere_id' => 'required|integer|exists:matieres,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $exists = $this->enseignantMatiereService->affectationExists(
                $validated['enseignant_id'],
                $validated['matiere_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'exists' => $exists,
                'message' => $exists ? 'Affectation trouvée' : 'Affectation non trouvée'
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
                'message' => 'Erreur lors de la vérification'
            ], 500);
        }
    }



}
