<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoursRequest;
use App\Models\Cours;
use App\services\CoursService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CoursController extends Controller
{
    protected CoursService $coursService;

    public function __construct(CoursService $coursService)
    {
        $this->coursService = $coursService;
    }

    /**
     * Afficher tous les cours
     */
    public function index(): JsonResponse
    {
        try {
            $cours = $this->coursService->index();

            return response()->json($cours,200);


        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des cours'
            ], 500);
        }
    }

    /**
     * Créer un nouveau cours
     */
    public function store(CoursRequest $request): JsonResponse
    {
        try {

           $cours =  $this->matiereService->store($request->validated());

            return response()->json($cours, 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Afficher un cours spécifique
     */
    public function show(int $id): JsonResponse
    {
        try {
            $cours = $this->coursService->show($id);

            if (!$cours) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cours non trouvé'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $cours,
                'message' => 'Cours récupéré avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du cours'
            ], 500);
        }
    }

    /**
     * Mettre à jour un cours
     */
    public function update(CoursRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        if($validated){
            $matiere = Cours::find($id);
            $matiere->update($validated);

        }
        return  response()->json($matiere,200);

    }

    /**
     * Supprimer un cours par ID
     */
    public function destroy( $id): JsonResponse
    {
        Cours::destroy($id);
        return response()->json("",204);

    }

    /**
     * Attribuer un cours
     */
    public function attribuer(CoursRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $cours = $this->coursService->attribuerCours(
                $validated['enseignant_id'],
                $validated['matiere_id'],
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $cours->load(['enseignant', 'matiere', 'classe', 'anneeAcademique']),
                'message' => 'Cours attribué avec succès'
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Retirer un cours spécifique
     */
    public function retirer(CoursRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->coursService->retirerCours(
                $validated['enseignant_id'],
                $validated['matiere_id'],
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Cours retiré avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Obtenir tous les cours d'un enseignant
     */
    public function getCoursByEnseignant(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'enseignant_id' => 'required|integer|exists:enseignants,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $cours = $this->coursService->getCoursByEnseignant(
                $validated['enseignant_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $cours,
                'message' => 'Cours de l\'enseignant récupérés avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des cours'
            ], 500);
        }
    }

    /**
     * Obtenir tous les cours d'une matière
     */
    public function getCoursByMatiere(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'matiere_id' => 'required|integer|exists:matieres,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $cours = $this->coursService->getCoursByMatiere(
                $validated['matiere_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $cours,
                'message' => 'Cours de la matière récupérés avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des cours'
            ], 500);
        }
    }

    /**
     * Obtenir tous les cours d'une classe
     */
    public function getCoursByClasse(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'classe_id' => 'required|integer|exists:classes,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $cours = $this->coursService->getCoursByClasse(
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $cours,
                'message' => 'Cours de la classe récupérés avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des cours'
            ], 500);
        }
    }

    /**
     * Obtenir tous les cours d'une année académique
     */
    public function getCoursByAnnee(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $cours = $this->coursService->getCoursByAnnee($validated['annee_academique_id']);

            return response()->json([
                'success' => true,
                'data' => $cours,
                'message' => 'Cours de l\'année académique récupérés avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des cours'
            ], 500);
        }
    }





    /**
     * Vérifier si un cours existe
     */
    public function verifierCours(CoursRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $exists = $this->coursService->coursExists(
                $validated['enseignant_id'],
                $validated['matiere_id'],
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'exists' => $exists,
                'message' => $exists ? 'Cours trouvé' : 'Cours non trouvé'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des cours
     */
    public function getStatistiques(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $statistiques = $this->coursService->getStatistiquesDetaillees(
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $statistiques,
                'message' => 'Statistiques récupérées avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Obtenir l'emploi du temps d'un enseignant
     */
    public function getEmploiDuTempsEnseignant(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'enseignant_id' => 'required|integer|exists:enseignants,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $emploiDuTemps = $this->coursService->getEmploiDuTempsEnseignant(
                $validated['enseignant_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $emploiDuTemps,
                'message' => 'Emploi du temps de l\'enseignant récupéré avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'emploi du temps'
            ], 500);
        }
    }

    /**
     * Obtenir l'emploi du temps d'une classe
     */
    public function getEmploiDuTempsClasse(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'classe_id' => 'required|integer|exists:classes,id',
                'annee_academique_id' => 'required|integer|exists:annees_academiques,id',
            ]);

            $emploiDuTemps = $this->coursService->getEmploiDuTempsClasse(
                $validated['classe_id'],
                $validated['annee_academique_id']
            );

            return response()->json([
                'success' => true,
                'data' => $emploiDuTemps,
                'message' => 'Emploi du temps de la classe récupéré avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'emploi du temps'
            ], 500);
        }
    }
}
