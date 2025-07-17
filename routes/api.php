<?php

use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\EleveController;
use App\Http\Controllers\API\ParentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
//Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
//Route::apiResource("/offres",\App\Http\Controllers\OffreController::class);

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // ÉLÈVES
    Route::apiResource('eleves', EleveController::class);

    // PARENTS
    Route::apiResource('parents', ParentController::class);

    // Lier un élève à un parent
    Route::post('parents/{parent}/attach-eleve/{eleve}', [ParentController::class, 'attachEleve']);

    // DOCUMENTS
    Route::post('eleves/{eleve}/documents', [DocumentController::class, 'store']);
    Route::delete('documents/{document}', [DocumentController::class, 'destroy']);
});
