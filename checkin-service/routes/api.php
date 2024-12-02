<?php

use App\Http\Controllers\CheckinController;
use Illuminate\Support\Facades\Route;

// Rotas para check-in, protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    Route::post('/checkin', [CheckinController::class, 'checkin']);
    Route::post('/events-by-email', [CheckinController::class, 'getEventIdsByEmail']);
});
