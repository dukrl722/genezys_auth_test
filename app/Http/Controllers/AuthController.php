<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\AddressService;
use App\Http\Services\AuthService;
use App\Http\Services\UserService;
use App\Http\Transformers\UserResource;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct(
        protected User           $user,
        protected Address        $address,
        protected UserService    $userService,
        protected AddressService $addressService,
        protected AuthService    $authService
    )
    {

    }

    public function authentication(Request $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'message' => 'Email or Password does not match with our record.',
            ], JsonResponse::HTTP_NOT_ACCEPTABLE);
        }

        try {

            if (!$user = $this->userService->getByEmail($request->email)) {
                return response()->json([
                    'message' => 'User not found'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');

        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request)
    {

        try {

            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed',
                'cep' => 'required|max:8|min:8',
                'street' => 'required',
                'number' => 'required',
                'district' => 'required',
                'city' => 'required',
                'state' => 'required'
            ]);

            if ($user = $this->userService->getByEmail($request->email)) {
                return response()->json([
                    'message' => 'E-mail already registered'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::beginTransaction();

            $address = $this->addressService->create($request->only([
                'cep',
                'street',
                'number',
                'district',
                'city',
                'state'
            ]));

            $user = $this->userService->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'address_id' => $address->id
            ]);

            DB::commit();

            return redirect()->route('dashboard');

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function passwordReset(Request $request) {

        try {

            $request->validate([
                'email' => 'required|email'
            ]);

            $status = $this->authService->sendResetPasswordEmail($request);

            return $status === Password::RESET_LINK_SENT
                ? redirect()->route('login')
                : redirect()->route('forgot.password');

        } catch (\Exception $exception) {

            return response()->json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        auth('sanctum')->user()->tokens()->delete();
        $request->session()->flush();

        return redirect()->intended('/');
    }
}
