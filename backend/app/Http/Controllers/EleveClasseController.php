<?php

namespace App\Http\Controllers;

use App\Http\Requests\EleveClasseRequest;
use App\Models\EleveClasse;
use App\services\EleveClasseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EleveClasseController extends Controller
{
    /**
     * @var EleveClasseService
     */
    protected EleveClasseService $eleveClasseService;

    /**
     * Constructeur pour injecter le service EleveClasseService.
     *
     * @param EleveClasseService $eleveClasseService
     */
    public function __construct(EleveClasseService $eleveClasseService)
    {
        $this->eleveClasseService = $eleveClasseService;
    }

    /**
     * Affiche une liste de toutes les affectations Élève-Classe.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $affectations = $this->eleveClasseService->index();
            return response()->json($affectations, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des affectations Élève-Classe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée une nouvelle affectation Élève-Classe.
     *
     * @param EleveClasseRequest $request
     * @return JsonResponse
     */
    public function store(EleveClasseRequest $request): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreEleveClasseRequest
            $affectation = $this->eleveClasseService->store($request->validated());
            return response()->json($affectation, 201); // 201 Created
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'affectation Élève-Classe : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Affiche une affectation Élève-Classe spécifique.
     *
     * @param int $id L'ID de l'affectation.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $affectation = $this->eleveClasseService->show($id);

            if (!$affectation) {
                return response()->json(['message' => 'Affectation Élève-Classe non trouvée'], 404); // 404 Not Found
            }

            return response()->json($affectation, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'affectation Élève-Classe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour une affectation Élève-Classe existante.
     *
     * @param EleveClasseRequest $request
     * @param int $id L'ID de l'affectation à mettre à jour.
     * @return JsonResponse
     */
    public function update(EleveClasseRequest $request, int $id): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreEleveClasseRequest
            $affectation = $this->eleveClasseService->update($id, $request->validated());

            if (!$affectation) {
                return response()->json(['message' => 'Affectation Élève-Classe non trouvée'], 404); // 404 Not Found
            }

            return response()->json($affectation, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'affectation Élève-Classe : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprime une affectation Élève-Classe.
     *
     * @param int $id L'ID de l'affectation à supprimer.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->eleveClasseService->destroy($id);

            if (!$deleted) {
                return response()->json(['message' => 'Affectation Élève-Classe non trouvée ou suppression échouée'], 404); // 404 Not Found
            }

            return response()->json(null, 204); // 204 No Content (pour une suppression réussie)
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'affectation Élève-Classe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les classes auxquelles un élève est affecté pour une année académique donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getClassesByEleveAndAnnee(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $classes = $this->eleveClasseService->getClassesByEleveAndAnnee(
                $validated['eleve_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $classes,
                'message' => 'Classes récupérées avec succès pour l\'élève et l\'année académique.'
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
                'message' => 'Erreur lors de la récupération des classes par élève et année : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère tous les élèves affectés à une classe pour une année académique donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getElevesByClasseAndAnnee(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'classe_id' => 'required|integer|exists:classes,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $eleves = $this->eleveClasseService->getElevesByClasseAndAnnee(
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $eleves,
                'message' => 'Élèves récupérés avec succès pour la classe et l\'année académique.'
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
                'message' => 'Erreur lors de la récupération des élèves par classe et année : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les affectations Élève-Classe pour une année académique donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAffectationsByAnnee(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $affectations = $this->eleveClasseService->getAffectationsByAnnee(
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $affectations,
                'message' => 'Affectations récupérées avec succès pour l\'année académique.'
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
                'message' => 'Erreur lors de la récupération des affectations par année : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Synchronise les affectations de classes pour un élève et une année académique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function synchroniserClassesEleve(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'classe_ids' => 'required|array',
                'classe_ids.*' => 'integer|exists:classes,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $this->eleveClasseService->synchroniserClassesEleve(
                $validated['eleve_id'],
                $validated['classe_ids'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Classes de l\'élève synchronisées avec succès.'
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
                'message' => 'Erreur lors de la synchronisation des classes de l\'élève : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Synchronise les affectations d'élèves pour une classe et une année académique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function synchroniserElevesClasse(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'classe_id' => 'required|integer|exists:classes,id',
                'eleve_ids' => 'required|array',
                'eleve_ids.*' => 'integer|exists:eleves,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $this->eleveClasseService->synchroniserElevesClasse(
                $validated['classe_id'],
                $validated['eleve_ids'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Élèves de la classe synchronisés avec succès.'
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
                'message' => 'Erreur lors de la synchronisation des élèves de la classe : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifie si une affectation Élève-Classe spécifique existe.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function affectationExists(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'classe_id' => 'required|integer|exists:classes,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $exists = $this->eleveClasseService->affectationExists(
                $validated['eleve_id'],
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'exists' => $exists,
                'message' => $exists ? 'Affectation trouvée.' : 'Affectation non trouvée.'
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
                'message' => 'Erreur lors de la vérification de l\'affectation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir le nombre total d'affectations Élève-Classe pour une année académique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNombreAffectations(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $count = $this->eleveClasseService->getNombreAffectations(
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Nombre total d\'affectations récupéré avec succès.'
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
                'message' => 'Erreur lors de la récupération du nombre total d\'affectations : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir le nombre d'élèves affectés à une classe spécifique pour une année académique donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNombreElevesByClasseAndAnnee(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'classe_id' => 'required|integer|exists:classes,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $count = $this->eleveClasseService->getNombreElevesByClasseAndAnnee(
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Nombre d\'élèves par classe et année récupéré avec succès.'
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
                'message' => 'Erreur lors de la récupération du nombre d\'élèves par classe et année : ' . $e->getMessage()
            ], 500);
        }
    }
}
