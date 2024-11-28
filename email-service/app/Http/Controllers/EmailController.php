<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CancelEmail;
use App\Mail\CheckinEmail;
use App\Mail\InscriptionEmail;
use App\Mail\RegistrationEmail;
use App\Mail\NewRegistrationEmail; // Importa o novo e-mail
use Illuminate\Support\Str;

class EmailController extends Controller
{
    /**
     * Envia um e-mail de cancelamento de evento.
     */
    public function sendCancelEmail(Request $request)
    {
        Log::info('Recebendo solicitação para enviar e-mail de cancelamento', ['email' => $request->input('email')]);

        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::to($validatedData['email'])->send(new CancelEmail);

            Log::info('E-mail de cancelamento enviado com sucesso', ['email' => $validatedData['email']]);
            return response()->json(['message' => 'E-mail de cancelamento enviado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de cancelamento', ['email' => $validatedData['email'], 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao enviar e-mail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Envia um e-mail de confirmação de check-in.
     */
    public function sendCheckinEmail(Request $request)
    {
        Log::info('Recebendo solicitação para enviar e-mail de confirmação de check-in', ['email' => $request->input('email')]);

        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::to($validatedData['email'])->send(new CheckinEmail($validatedData['email']));

            Log::info('E-mail de confirmação de check-in enviado com sucesso', ['email' => $validatedData['email']]);
            return response()->json(['message' => 'E-mail de check-in enviado com sucesso!'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de confirmação de check-in', ['email' => $validatedData['email'], 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao enviar e-mail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Envia um e-mail de confirmação de inscrição.
     */
    public function sendInscriptionEmail(Request $request)
    {
        Log::info('Recebendo solicitação para enviar e-mail de confirmação de inscrição', ['email' => $request->input('email')]);

        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::to($validatedData['email'])->send(new InscriptionEmail);

            Log::info('E-mail de confirmação de inscrição enviado com sucesso', ['email' => $validatedData['email']]);
            return response()->json(['message' => 'E-mail de inscrição enviado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de confirmação de inscrição', ['email' => $validatedData['email'], 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao enviar e-mail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Envia um e-mail de confirmação de registro.
     */
    public function sendConfirmationEmail(Request $request)
    {
        Log::info('Recebendo solicitação para enviar e-mail de confirmação de registro', ['email' => $request->input('email')]);

        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::to($validatedData['email'])->send(new RegistrationEmail);

            Log::info('E-mail de confirmação de registro enviado com sucesso', ['email' => $validatedData['email']]);
            return response()->json(['message' => 'E-mail de registro enviado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de confirmação de registro', ['email' => $validatedData['email'], 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao enviar e-mail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Envia um e-mail de novo registro com senha aleatória.
     */
    public function new(Request $request)
    {
        Log::info('Recebendo solicitação para enviar e-mail de novo registro', ['email' => $request->input('email')]);

        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        try {
            Mail::to($validatedData['email'])->send(new NewRegistrationEmail($validatedData['email'], $validatedData['password']));

            Log::info('E-mail de novo registro enviado com sucesso', ['email' => $validatedData['email']]);
            return response()->json(['message' => 'E-mail de novo registro enviado com sucesso.'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de novo registro', ['email' => $validatedData['email'], 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao enviar e-mail: ' . $e->getMessage()], 500);
        }
    }
}
