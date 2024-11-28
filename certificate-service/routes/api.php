<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

Route::post('/certificates/generate', [CertificateController::class, 'generate']);
Route::get('/certificates/validate/{certificate_id}', [CertificateController::class, 'validateCertificate']);
Route::post('/certificates/revoke/{certificate_id}', [CertificateController::class, 'revokeCertificate']);
Route::get('/certificates/generate-pdf', [CertificateController::class, 'generateSimple']);
Route::post('/certificates/generate-simple', [CertificateController::class, 'generateSimple']);
