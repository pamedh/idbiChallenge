<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $token = $this->authService->login($credentials);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token
        ], 200);
    }

    public function logout() {
        $this->authService->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ], 200);
    }
}
