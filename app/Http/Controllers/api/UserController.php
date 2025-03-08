<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index() {
        $users = $this->userService->getAll();

        return response()->json([
            'status' => 'success',
            'users' => $users
        ], 200);
    }

    public function show($id) {
        $user = $this->userService->getById($id);

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'required|boolean',
        ]);

        $user = $this->userService->create($data);;

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'is_admin' => 'required|boolean',
        ]);

        $user = $this->userService->update($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'user' => $user
        ], 200);
    }

    public function delete($id) {
        $this->userService->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ], 200);
    }
}
