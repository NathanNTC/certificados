<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Rotas de autenticação
Route::post('register', [AuthController::class, 'register']);
Route::post('registerNEW', [AuthController::class, 'registerNEW']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/users/{email}', [AuthController::class, 'checkUser']);


Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// Nova rota para enviar e-mail de confirmação após o registro
Route::post('send-confirmation-email', function () {
    $email = request()->input('email');

    // Verifica se o email foi fornecido na requisição
    if (!$email) {
        return response()->json(['error' => 'O e-mail é obrigatório.'], 400);
    }

    // Envia uma requisição para a API de envio de e-mail (porta 8001)
    $response = Http::post('http://127.0.0.1:8001/api/send-confirmation-email', [
        'email' => $email,
    ]);

    // Verifica se a requisição foi bem-sucedida
    if ($response->successful()) {
        return response()->json(['message' => 'E-mail de confirmação enviado com sucesso!'], 200);
    }

    return response()->json(['error' => 'Falha ao enviar o e-mail.'], 500);
});

// Rota para validar o token
Route::post('validate-token', function (Request $request) {
    $token = $request->bearerToken(); // Obtém o token do cabeçalho Authorization

    if (!$token) {
        return response()->json(['message' => 'Token não fornecido'], 401);
    }

    try {
        // Valida o token utilizando o guard configurado
        $user = Auth::guard('api')->user();

        if ($user) {
            return response()->json([
                'valid' => true,
                'user' => $user, // Retorna informações do usuário
            ], 200);
        }

        return response()->json(['valid' => false, 'message' => 'Token inválido'], 401);
    } catch (\Exception $e) {
        return response()->json(['valid' => false, 'message' => 'Erro ao validar token'], 401);
    }
});
