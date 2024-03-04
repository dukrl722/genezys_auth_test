<?php

namespace App\Http\Repositories;

use App\Http\Repositories\contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $user
    ) {}

    public function create(array $data) {
        return $this->user->create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => data_get($data, 'password'),
            'address_id' => data_get($data, 'address_id')
        ]);
    }

    public function getByEmail(string $email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function getAll()
    {
        return $this->user->get();
    }

    public function updatePassword(array $data)
    {
        return $this->user->where('email', data_get($data, 'email'))->update([
            'password' => data_get($data, 'password')
        ]);
    }
}
