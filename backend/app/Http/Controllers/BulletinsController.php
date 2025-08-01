<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulletinsRequest;
use App\Models\Bulletins;
use App\services\BulletinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BulletinsController extends Controller
{
    /**
 * @var BulletinService
 */
    protected BulletinService $bulletinService;

    /**
     * Constructeur pour injecter le service BulletinService.
     *
     * @param BulletinService $bulletinService
     */
    public function __construct(BulletinService $bulletinService)
    {
        $this->bulletinService = $bulletinService;
    }

    /**
     * Affiche une liste de tous les bulletins.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $bulletins = $this->bulletinService->index();
            return response()->json($bulletins, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des bulletins : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée un nouveau bulletin.
     *
     * @param StoreBulletinRequest $request
     * @return JsonResponse
     */
    public function store(BulletinsRequest $request): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreBulletinRequest
            $bulletin = $this->bulletinService->store($request->validated());
            return response()->json($bulletin, 201); // 201 Created
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du bulletin : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Affiche un bulletin spécifique.
     *
     * @param int $id L'ID du bulletin.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $bulletin = $this->bulletinService->show($id);

            if (!$bulletin) {
                return response()->json(['message' => 'Bulletin non trouvé'], 404); // 404 Not Found
            }

            return response()->json($bulletin, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du bulletin : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour un bulletin existant.
     *
     * @param StoreBulletinRequest $request
     * @param int $id L'ID du bulletin à mettre à jour.
     * @return JsonResponse
     */
    public function update(BulletinsRequest $request, int $id): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreBulletinRequest
            $bulletin = $this->bulletinService->update($id, $request->validated());

            if (!$bulletin) {
                return response()->json(['message' => 'Bulletin non trouvé'], 404); // 404 Not Found
            }

            return response()->json($bulletin, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du bulletin : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprime un bulletin.
     *
     * @param int $id L'ID du bulletin à supprimer.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->bulletinService->destroy($id);

            if (!$deleted) {
                return response()->json(['message' => 'Bulletin non trouvé ou suppression échouée'], 404); // 404 Not Found
            }

            return response()->json(null, 204); // 204 No Content (pour une suppression réussie)
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du bulletin : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère tous les bulletins d'un élève pour une année académique donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBulletinsByEleveAndAnnee(BulletinsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $bulletins = $this->bulletinService->getBulletinsByEleveAndAnnee(
                $validated['eleve_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $bulletins,
                'message' => 'Bulletins récupérés avec succès pour l\'élève et l\'année académique.'
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
                'message' => 'Erreur lors de la récupération des bulletins par élève et année : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère tous les bulletins d'un élève pour une période d'évaluation donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBulletinsByEleveAndPeriode(BulletinsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $bulletins = $this->bulletinService->getBulletinsByEleveAndPeriode(
                $validated['eleve_id'],
                $validated['periode_evaluation_id']
            );

            return response()->json([
                'success' => true,
                'data' => $bulletins,
                'message' => 'Bulletins récupérés avec succès pour l\'élève et la période d\'évaluation.'
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
                'message' => 'Erreur lors de la récupération des bulletins par élève et période : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère un bulletin spécifique par élève, année académique et période d'évaluation.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBulletinByEleveAnneePeriode(BulletinsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $bulletin = $this->bulletinService->getBulletinByEleveAnneePeriode(
                $validated['eleve_id'],
                $validated['annee_academique_id'],
                $validated['periode_evaluation_id']
            );

            if (!$bulletin) {
                return response()->json(['message' => 'Bulletin non trouvé pour les critères spécifiés'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $bulletin,
                'message' => 'Bulletin récupéré avec succès.'
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
                'message' => 'Erreur lors de la récupération du bulletin : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un bulletin pour un élève, une année et une période spécifiques.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateBulletin(BulletinsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Le service va gérer la création/mise à jour et potentiellement le calcul
            $bulletin = $this->bulletinService->generateBulletin(
                $validated['eleve_id'],
                $validated['annee_academique_id'],
                $validated['periode_evaluation_id'],
                $validated // Passer toutes les données validées pour la génération/mise à jour
            );

            return response()->json([
                'success' => true,
                'data' => $bulletin,
                'message' => 'Bulletin généré/mis à jour avec succès.'
            ], 200); // 200 OK car cela peut être une création ou une mise à jour

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du bulletin : ' . $e->getMessage()
            ], 500);
        }
    }
}
