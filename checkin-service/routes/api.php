<?php

use App\Http\Controllers\CheckinController;
use Illuminate\Support\Facades\Route;

Route::post('/checkin', [CheckinController::class, 'checkin']);

Route::post('/events-by-email', [CheckinController::class, 'getEventIdsByEmail']);