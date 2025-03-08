<?php

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login($credentials) {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return $token;
    }

    public function logout() {
        $user = Auth::user();
        $user->tokens()->delete();
    }
}

