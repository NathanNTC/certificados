<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(function () {
    
    Route::post('/events', [EventController::class, 'store']);

    
    Route::get('/events', [EventController::class, 'index']);

    
    Route::get('/events/{id}', [EventController::class, 'show']);
});
