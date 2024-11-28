<?php

use App\Http\Controllers\InscriptionController;
use Illuminate\Support\Facades\Route;

// Rotas para inscrições
Route::get('/inscriptions', [InscriptionController::class, 'index']); // Lista inscrições do usuário
Route::post('/inscriptions', [InscriptionController::class, 'store']); // Realiza inscrição
Route::delete('/inscriptions/{id}', [InscriptionController::class, 'destroy']); // Cancela inscrição
