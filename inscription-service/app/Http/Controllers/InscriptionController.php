<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Adiciona a dependência para logs

class InscriptionController extends Controller
{
    // Listar as inscrições do usuário
    public function index(Request $request)
    {
        $email = $request->header('User-Email');

        // Log de acesso à rota
        Log::info('Acesso à rota: GET /inscriptions');

        if (!$email) {
            Log::warning('Email não fornecido no header');
            return response()->json(['message' => 'Email não fornecido'], 400);
        }

        $inscriptions = Inscription::where('user_email', $email)->get();

        if ($inscriptions->isEmpty()) {
            Log::info('Nenhuma inscrição encontrada para o email', ['email' => $email]);
            return response()->json(['message' => 'Nenhuma inscrição encontrada para este email'], 404);
        }

        Log::info('Inscrições recuperadas com sucesso', ['email' => $email, 'count' => $inscriptions->count()]);

        return response()->json($inscriptions);
    }

    // Criar uma inscrição
    public function store(Request $request)
    {
        $email = $request->header('User-Email');

        // Log de acesso à rota
        Log::info('Acesso à rota: POST /inscriptions');

        if (!$email) {
            Log::warning('Email não fornecido no header');
            return response()->json(['message' => 'Email não fornecido'], 400);
        }

        $eventId = $request->input('event_id');

        // Log de criação de inscrição
        Log::info('Tentativa de criar uma nova inscrição', ['email' => $email, 'event_id' => $eventId]);

        // Criar a inscrição diretamente usando o email
        $inscription = Inscription::create([
            'user_email' => $email,
            'event_id' => $eventId,
        ]);

        Log::info('Inscrição criada com sucesso', ['email' => $email, 'event_id' => $eventId]);

        return response()->json([
            'message' => 'Inscrição realizada com sucesso!',
            'inscription' => $inscription,
        ], 201);
    }

    // Cancelar uma inscrição
    public function destroy(Request $request, $id)
    {
        // Log de acesso à rota
        Log::info('Acesso à rota: DELETE /inscriptions/{id}', ['id' => $id]);

        // Obter o email do usuário logado
        $email = $request->header('User-Email');

        if (!$email) {
            Log::warning('Email não fornecido no header');
            return response()->json(['message' => 'Email não fornecido'], 400);
        }

        // Buscar a inscrição do usuário pelo id e email
        $inscription = Inscription::where('user_email', $email)
            ->where('id', $id)
            ->first();

        if (!$inscription) {
            Log::info('Inscrição não encontrada', ['email' => $email, 'id' => $id]);
            return response()->json(['message' => 'Inscrição não encontrada'], 404);
        }

        // Log de cancelamento de inscrição
        Log::info('Inscrição encontrada e cancelada', ['email' => $email, 'id' => $id]);

        // Cancelar a inscrição
        $inscription->delete();

        return response()->json(['message' => 'Inscrição cancelada com sucesso!']);
    }
}
