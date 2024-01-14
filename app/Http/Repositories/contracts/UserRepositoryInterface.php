<?php

namespace App\Http\Repositories\contracts;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function updatePassword(array $data);
    public function getByEmail(string $email);
    public function getAll();
}
