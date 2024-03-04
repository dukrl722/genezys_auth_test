<?php

namespace App\Http\Controllers;

use App\Http\Repositories\contracts\AddressRepositoryInterface;
use App\Http\Repositories\contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        protected AddressRepositoryInterface $addressRepository,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function authentication(Request $request): JsonResponse|RedirectResponse
    {

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Email or Password does not match with our record.',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        try {

            if (!$this->userRepository->getByEmail($request->email)) {
                return response()->json([
                    'message' => 'User not found'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');

        } catch (Throwable $exception) {

            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request): JsonResponse|RedirectResponse
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

            if ($this->userRepository->getByEmail($request->email)) {
                return response()->json([
                    'message' => 'E-mail already registered'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::beginTransaction();

            $address = $this->addressRepository->create($request->only([
                'cep',
                'street',
                'number',
                'district',
                'city',
                'state'
            ]));

            $this->userRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'address_id' => $address->id
            ]);

            DB::commit();

            return redirect()->route('dashboard');

        } catch (Exception $exception) {

            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendResetPasswordEmail(Request $request): JsonResponse|RedirectResponse
    {

        try {

            $request->validate([
                'email' => 'required|email'
            ]);

            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? redirect()->route('login')
                : redirect()->route('forgot.password');

        } catch (Exception $exception) {

            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword(Request $request): JsonResponse|RedirectResponse
    {

        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]);

            DB::beginTransaction();

            $this->userRepository->updatePassword($request->only(['email', 'password']));

            DB::commit();

            return redirect()->route('login');

        } catch (Exception $exception) {

            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function logout(Request $request): RedirectResponse
    {
        auth('sanctum')->user()->tokens()->delete();
        $request->session()->flush();

        return redirect()->intended();
    }
}
