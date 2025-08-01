<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotesRequest;
use App\Models\Notes;
use App\services\NotesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    /**
     * @var NoteService
     */
    protected NoteService $noteService;

    /**
     * Constructeur pour injecter le service NoteService.
     *
     * @param NoteService $noteService
     */
    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Affiche une liste de toutes les notes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $notes = $this->noteService->index();
            return response()->json($notes, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notes : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crée une nouvelle note.
     *
     * @param StoreNoteRequest $request
     * @return JsonResponse
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreNoteRequest
            $note = $this->noteService->store($request->validated());
            return response()->json($note, 201); // 201 Created
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la note : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Affiche une note spécifique.
     *
     * @param int $id L'ID de la note.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $note = $this->noteService->show($id);

            if (!$note) {
                return response()->json(['message' => 'Note non trouvée'], 404); // 404 Not Found
            }

            return response()->json($note, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la note : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour une note existante.
     *
     * @param StoreNoteRequest $request
     * @param int $id L'ID de la note à mettre à jour.
     * @return JsonResponse
     */
    public function update(StoreNoteRequest $request, int $id): JsonResponse
    {
        try {
            // Les données sont déjà validées par StoreNoteRequest
            $note = $this->noteService->update($id, $request->validated());

            if (!$note) {
                return response()->json(['message' => 'Note non trouvée'], 404); // 404 Not Found
            }

            return response()->json($note, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la note : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprime une note.
     *
     * @param int $id L'ID de la note à supprimer.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->noteService->destroy($id);

            if (!$deleted) {
                return response()->json(['message' => 'Note non trouvée ou suppression échouée'], 404); // 404 Not Found
            }

            return response()->json(null, 204); // 204 No Content (pour une suppression réussie)
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la note : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les notes d'un élève pour un cours spécifique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotesByEleveAndCours(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'cours_id' => 'required|integer|exists:cours,id',
            ]);

            $notes = $this->noteService->getNotesByEleveAndCours(
                $validated['eleve_id'],
                $validated['cours_id']
            );

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Notes récupérées avec succès pour l\'élève et le cours.'
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
                'message' => 'Erreur lors de la récupération des notes par élève et cours : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les notes d'un élève pour une période d'évaluation donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotesByEleveAndPeriode(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'periode_evaluation_id' => 'required|integer|exists:periodes_evaluation,id',
            ]);

            $notes = $this->noteService->getNotesByEleveAndPeriode(
                $validated['eleve_id'],
                $validated['periode_evaluation_id']
            );

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Notes récupérées avec succès pour l\'élève et la période d\'évaluation.'
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
                'message' => 'Erreur lors de la récupération des notes par élève et période : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les notes pour un cours spécifique et une période d'évaluation donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotesByCoursAndPeriode(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|integer|exists:cours,id',
                'periode_evaluation_id' => 'required|integer|exists:periodes_evaluation,id',
            ]);

            $notes = $this->noteService->getNotesByCoursAndPeriode(
                $validated['cours_id'],
                $validated['periode_evaluation_id']
            );

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Notes récupérées avec succès pour le cours et la période d\'évaluation.'
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
                'message' => 'Erreur lors de la récupération des notes par cours et période : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les notes pour un élève, un cours et une période d'évaluation spécifiques.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotesByEleveCoursPeriode(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'cours_id' => 'required|integer|exists:cours,id',
                'periode_evaluation_id' => 'required|integer|exists:periodes_evaluation,id',
            ]);

            $notes = $this->noteService->getNotesByEleveCoursPeriode(
                $validated['eleve_id'],
                $validated['cours_id'],
                $validated['periode_evaluation_id']
            );

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Notes récupérées avec succès pour l\'élève, le cours et la période.'
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
                'message' => 'Erreur lors de la récupération des notes par élève, cours et période : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcule la moyenne des notes d'un élève pour un cours spécifique.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAverageNoteByEleveAndCours(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'cours_id' => 'required|integer|exists:cours,id',
            ]);

            $average = $this->noteService->getAverageNoteByEleveAndCours(
                $validated['eleve_id'],
                $validated['cours_id']
            );

            return response()->json([
                'success' => true,
                'average' => $average,
                'message' => 'Moyenne des notes récupérée avec succès pour l\'élève et le cours.'
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
                'message' => 'Erreur lors de la récupération de la moyenne : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcule la moyenne des notes d'un élève pour une période d'évaluation donnée.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAverageNoteByEleveAndPeriode(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'eleve_id' => 'required|integer|exists:eleves,id',
                'periode_evaluation_id' => 'required|integer|exists:periodes_evaluation,id',
            ]);

            $average = $this->noteService->getAverageNoteByEleveAndPeriode(
                $validated['eleve_id'],
                $validated['periode_evaluation_id']
            );

            return response()->json([
                'success' => true,
                'average' => $average,
                'message' => 'Moyenne des notes récupérée avec succès pour l\'élève et la période.'
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
                'message' => 'Erreur lors de la récupération de la moyenne : ' . $e->getMessage()
            ], 500);
        }
    }
}
