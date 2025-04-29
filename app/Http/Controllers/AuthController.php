<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        if (!$result) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        return response()->json([
            'message' => 'bienvenido',
            'token' => $result['token'],
            'user' => $result['user'] ?? null
        ]);
        
    }

    public function logout()
    {
        $result = $this->authService->logout();

        return response()->json($result);
    }

    public function refresh()
    {
        $result = $this->authService->refresh();

        return response()->json($result);
    }

    public function me()
    {
        $user = $this->authService->me();

        return response()->json($user);
    }
}
