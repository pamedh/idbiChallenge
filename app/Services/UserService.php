<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAll() {
        $users = $this->userRepository->getAll();
        
        return $users;
    }

    public function getById($id) {
        $user = $this->userRepository->getById($id);
        
        return $user;
    }

    public function create(array $data) {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        return $user;
    }

    public function update($id, array $data) {
        $user = $this->userRepository->update($id, $data);

        return $user;
    }

    public function delete($id) {
        $user = $this->userRepository->delete($id);
    }
}