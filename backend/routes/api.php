<?php

use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\EleveController;
use App\Http\Controllers\API\ParentController;
use App\Http\Controllers\API\AuthController;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Route;


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


Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::apiResource('eleves', EleveController::class);
        Route::apiResource('parents', ParentController::class);
        Route::post('parents/{parent}/attach-eleve/{eleve}', [ParentController::class, 'attachEleve']);
        Route::post('eleves/{eleve}/documents', [DocumentController::class, 'store']);
        Route::delete('documents/{document}', [DocumentController::class, 'destroy']);
    });
});
