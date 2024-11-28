<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Adiciona a dependência para logs

class EventController extends Controller
{
    // Rota para listar todos os eventos
    public function index()
    {
        // Log de acesso à rota
        Log::info('Acesso à rota: GET /events');

        // Recupera todos os eventos
        $events = Event::all();

        // Log de sucesso na recuperação dos eventos
        Log::info('Eventos recuperados com sucesso', ['count' => $events->count()]);

        // Retorna a lista de eventos com o código
        return response()->json($events);
    }

    public function show($id)
    {
        // Log de acesso à rota
        Log::info('Acesso à rota: GET /events/{id}', ['id' => $id]);

        // Recupera o evento específico pelo ID
        $event = Event::find($id);

        // Se o evento não for encontrado
        if (!$event) {
            Log::warning('Evento não encontrado', ['id' => $id]);
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        // Log de sucesso na recuperação do evento
        Log::info('Evento recuperado com sucesso', ['id' => $id, 'event_name' => $event->nome]);

        // Retorna os detalhes do evento, incluindo o código
        return response()->json($event);
    }

    // Rota para adicionar um novo evento
    public function store(Request $request)
    {
        // Log de acesso à rota
        Log::info('Acesso à rota: POST /events');

        // Validação dos dados
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'data' => 'required|date',
            'local' => 'required|string|max:255',
        ]);

        // Log de sucesso na validação dos dados
        Log::info('Dados validados com sucesso', ['data' => $validatedData]);

        // Geração do código único de 4 letras aleatórias
        $codigo = Str::upper(Str::random(4)); // Gera um código de 4 letras maiúsculas aleatórias

        // Criação do evento
        $event = Event::create([
            'nome' => $validatedData['nome'],
            'data' => $validatedData['data'],
            'local' => $validatedData['local'],
            'codigo' => $codigo, // Atribui o código gerado
        ]);

        // Log de sucesso na criação do evento
        Log::info('Evento criado com sucesso', ['event_id' => $event->id, 'event_name' => $event->nome]);

        // Retorna uma resposta de sucesso com os dados do evento
        return response()->json([
            'message' => 'Evento criado com sucesso',
            'event' => $event
        ], 201);
    }
}
