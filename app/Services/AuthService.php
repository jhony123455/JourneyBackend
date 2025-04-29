<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTGuard;

class AuthService
{
    protected $userRepository;
    protected $guard;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        /** @var JWTGuard $guard */
        $this->guard = Auth::guard('api');
    }

    public function register(array $data)
    {
        $existingUser = $this->userRepository->findByName($data['name']);

        if ($existingUser) {
            throw new \Exception('El nombre de usuario ya está registrado');
        }
        $user = $this->userRepository->create($data);

        return $this->generateTokenResponse($user);
    }

    public function login(array $credentials)
    {
        $rememberMe = $credentials['remember_me'] ?? false;
    
        $user = $this->userRepository->findByName($credentials['name']);
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }
    
        return $this->generateTokenResponse($user, $rememberMe);
    }
    

    public function logout()
    {
        $this->guard->logout();

        return ['message' => 'Sesión cerrada correctamente'];
    }

    public function refresh()
    {
        $newToken = $this->guard->refresh();
        $user = $this->guard->user();

        return $this->buildTokenData($user, $newToken);
    }

    public function me()
    {
        return $this->guard->user();
    }


    private function generateTokenResponse($user, $rememberMe = false)
    {
        if ($rememberMe) {
            $this->guard->factory()->setTTL(43200); // 30 días (30*24*60)
        }

        $token = $this->guard->login($user);

        return $this->buildTokenData($user, $token);
    }


    private function buildTokenData($user, $token)
    {
        return [
            'user' => $user,
            'token' => $token,
            'type' => 'bearer',
            'expires_in' => $this->guard->factory()->getTTL() * 60,
        ];
    }
}
