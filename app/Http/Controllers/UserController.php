<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use App\Http\Transformers\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function dashboardHomeList() {

        try {

            $users = UserResource::collection($this->userService->getAll());

            return view('dashboard', compact(['users']));

        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
