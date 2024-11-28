<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    public function getEventIdsByEmail(Request $request)
    {
        // Validação do e-mail
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $validatedData['email'];

        // Buscar os `event_id` associados ao e-mail
        $eventIds = Checkin::where('email', $email)
            ->pluck('event_id');

        Log::info('Consultando event_ids por e-mail', ['email' => $email, 'event_ids' => $eventIds]);

        return response()->json([
            'event_ids' => $eventIds,
        ], 200);
    }

    public function checkin(Request $request)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'codigo' => 'required|string',
            'email' => 'required|email',
        ]);

        $email = $validatedData['email'];
        $eventId = $validatedData['event_id'];
        $codigoEvento = $validatedData['codigo'];

        // Recuperar o evento pelo ID
        $event = Event::find($eventId);

        if (!$event) {
            Log::warning('Evento não encontrado', ['event_id' => $eventId]);
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        // Verificar se o código informado é válido para o evento
        if (!str_starts_with($codigoEvento, $event->codigo) || !str_ends_with($codigoEvento, "-{$event->id}")) {
            Log::warning('Código inválido para evento', ['event_id' => $eventId, 'codigo_informado' => $codigoEvento]);
            return response()->json(['message' => 'Código inválido para este evento'], 400);
        }

        // Verificar se o usuário já fez check-in
        $existingCheckin = Checkin::where('event_id', $event->id)
            ->where('email', $email)
            ->first();

        if ($existingCheckin) {
            Log::info('Check-in já realizado', ['event_id' => $eventId, 'email' => $email]);
            return response()->json(['message' => 'Check-in já realizado para este usuário'], 409);
        }

        try {
            // Registrar o check-in
            $checkin = Checkin::create([
                'event_id' => $event->id,
                'codigo' => $codigoEvento, // O código é salvo no banco, mas não enviado para o e-mail
                'email' => $email,
            ]);

            Log::info('Check-in realizado com sucesso', ['event_id' => $eventId, 'email' => $email, 'checkin_id' => $checkin->id]);

        } catch (\Exception $e) {
            Log::error('Erro ao registrar o check-in', ['event_id' => $eventId, 'email' => $email, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao registrar o check-in: ' . $e->getMessage()], 500);
        }

        // Retornar sucesso
        return response()->json([
            'message' => 'Check-in realizado com sucesso!',
            'checkin' => $checkin,
        ], 201);
    }
}
