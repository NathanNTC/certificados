<?php

use App\Http\Controllers\InscriptionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(function () {
    // Lista inscrições do usuário
    Route::get('/inscriptions', [InscriptionController::class, 'index']);

    // Realiza inscrição
    Route::post('/inscriptions', [InscriptionController::class, 'store']);

    // Cancela inscrição
    Route::delete('/inscriptions/{id}', [InscriptionController::class, 'destroy']);
});
