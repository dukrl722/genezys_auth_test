<?php

namespace App\Http\Services;

use App\Http\Repositories\contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function create(array $data) {
        $data['password'] = bcrypt(data_get($data, 'password'));

        return $this->userRepository->create($data);
    }

    public function updatePassword(array $data)
    {
        $data['password'] = bcrypt(data_get($data, 'password'));

        return $this->userRepository->updatePassword($data);
    }

    public function getByEmail(string $email) {
        return $this->userRepository->getByEmail($email);
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }
}
