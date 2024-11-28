<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Rota para adicionar um novo evento
Route::post('/events', [EventController::class, 'store']);

// Rota para listar todos os eventos
Route::get('/events', [EventController::class, 'index']);

Route::get('/events/{id}', [EventController::class, 'show']);
