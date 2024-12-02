<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;


Route::middleware('auth:api')->prefix('certificates')->group(function () {
    Route::post('/generate', [CertificateController::class, 'generate']);
    Route::get('/validate/{certificate_id}', [CertificateController::class, 'validateCertificate']);
    Route::post('/revoke/{certificate_id}', [CertificateController::class, 'revokeCertificate']);
    Route::get('/generate-pdf', [CertificateController::class, 'generateSimple']);
    Route::post('/generate-simple', [CertificateController::class, 'generateSimple']);
});
