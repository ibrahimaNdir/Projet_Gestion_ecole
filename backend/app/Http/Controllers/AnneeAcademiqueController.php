<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\services\AnneeAcademiqueService;
use Illuminate\Http\Request;
use App\Http\Requests\AnneeAcademiqueRequest;




class AnneeAcademiqueController extends Controller
{
    protected  $anneeacademiqueService;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->anneeacademiqueService = new AnneeAcademiqueService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anneeacademique =  $this->anneeacademiqueService ->index();
        return response()->json($anneeacademique ,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnneeAcademiqueRequest $request)
    {
        $anneeacademique =  $this->anneeacademiqueService->store($request->validated());
        return response()->json($anneeacademique ,201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $anneeacademique = AnneeAcademique::find($id);
        return response()->json($anneeacademique,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnneeAcademiqueRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $anneeacademique = AnneeAcademique::find($id);
            $anneeacademique->update($validated);

        }
        return  response()->json($anneeacademique,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AnneeAcademique::destroy($id);
        return response()->json("",204);
        //
    }
    /**
     * Définit une année académique comme active et désactive toutes les autres.
     *
     * @param Request $request La requête contenant l'ID de l'année à activer.
     * @return \Illuminate\Http\JsonResponse
     */
    public function setActiveAnnee(AnneeAcademiqueRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $annee = $this->anneeAcademiqueService->setActiveAnnee($validated['annee_id']);

            return response()->json([
                'success' => true,
                'data' => $annee,
                'message' => 'Année académique activée avec succès.'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données de validation invalides.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation de l\'année académique : ' . $e->getMessage()
            ], 400);
        }
    }
}
