<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\PeriodeEvaluationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('periodes-evaluation', PeriodeEvaluationController::class)->middleware('auth:sanctum');
Route::apiResource('matieres', MatiereController::class)->middleware('auth:sanctum');
Route::apiResource('classes', ClasseController::class)->middleware('auth:sanctum');
Route::apiResource('annees-academiques', AnneeAcademiqueController::class)->middleware('auth:sanctum');
