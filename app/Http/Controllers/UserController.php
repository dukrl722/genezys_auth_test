<?php

namespace App\Http\Controllers;

use App\Http\Repositories\contracts\UserRepositoryInterface;
use App\Http\Transformers\UserResource;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function dashboardHomeList(): View|Factory
    {
        $users = UserResource::collection($this->userRepository->getAll());

        return view('dashboard', compact(['users']));
    }
}
