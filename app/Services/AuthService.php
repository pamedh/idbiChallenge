<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register($data) {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->createUser($data);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login($credentials) {
        if (!Auth::attempt($credentials)) {
            
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout($request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }
}

