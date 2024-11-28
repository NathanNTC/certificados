<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

// Rota para envio de e-mail de confirmação de registro
Route::post('/send-confirmation-email', [EmailController::class, 'sendConfirmationEmail']);

// Rota para envio de e-mail de cancelamento de evento
Route::post('/send-cancel-email', [EmailController::class, 'sendCancelEmail']);

// Rota para envio de e-mail de confirmação de comparecimento
Route::post('/send-checkin-email', [EmailController::class, 'sendCheckinEmail']);

// Rota para envio de e-mail de confirmação de inscrição
Route::post('/send-inscription-email', [EmailController::class, 'sendInscriptionEmail']);

// Rota para envio de e-mail de novo registro com senha aleatória
Route::post('/new', [EmailController::class, 'new']);



