<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // Importa Http para chamadas HTTP
use Illuminate\Support\Facades\Log; // Importa o Log para registrar logs

class AuthController extends Controller
{
    // Registro de novo usuário
    public function register(Request $request)
    {
        Log::info('Register method called', ['email' => $request->email]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Registration failed validation', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        Log::info('User registered successfully', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'token' => $token, 
            'user' => [
                'name' => $user->name,
                'email' => $user->email, 
            ]
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function registerNEW(Request $request)
    {
        Log::info('RegisterNEW method called', ['email' => $request->email]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Registration failed validation', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        Log::info('User registered successfully', ['user_id' => $user->id, 'email' => $user->email]);

        try {
            $response = Http::post('http://127.0.0.1:8001/api/new', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                Log::info('Email notification sent successfully for new registration', ['email' => $request->email]);
                return response()->json([
                    'token' => $token,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'message' => 'E-mail de novo registro enviado com sucesso.',
                ], 200, [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            } else {
                Log::error('Failed to send email notification', ['email' => $request->email, 'response' => $response->body()]);
                return response()->json(['message' => 'Erro ao enviar e-mail: ' . $response->body()], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception encountered while sending email', ['email' => $request->email, 'exception' => $e->getMessage()]);
            return response()->json(['message' => 'Erro ao enviar e-mail: ' . $e->getMessage()], 500);
        }
    }

    // Login do usuário e geração de token JWT
    public function login(Request $request)
    {
        Log::info('Login method called', ['email' => $request->email]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                Log::warning('Login failed: Unauthorized', ['email' => $request->email]);
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            Log::error('Login failed: Could not create token', ['email' => $request->email, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = Auth::user();
        Log::info('User logged in successfully', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    // Recuperar informações do usuário autenticado
    public function getUser(Request $request)
    {
        $user = Auth::user();
        Log::info('User details retrieved', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json(['user' => $user]);
    }

    // Logout (revogar o token)
    public function logout(Request $request)
    {
        Log::info('Logout method called', ['token' => $request->token]);

        if (!$request->token) {
            Log::warning('Logout failed: Token not provided');
            return response()->json(['error' => 'Token not provided'], 400);
        }

        try {
            JWTAuth::invalidate($request->token);
            Log::info('User logged out successfully', ['token' => $request->token]);
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            Log::error('Logout failed: Could not log out', ['token' => $request->token, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Could not log out'], 500);
        }
    }

    public function checkUser($email)
    {
        Log::info('CheckUser method called', ['email' => $email]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('User not found', ['email' => $email]);
            return response()->json(['message' => 'User not found'], 404);
        }

        Log::info('User exists', ['user_id' => $user->id, 'email' => $email]);
        return response()->json(['message' => 'User exists', 'user' => $user], 200);
    }
}
