<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthService
{
    public function __construct() {}

    public function sendResetPasswordEmail(Request $request) {
        return Password::sendResetLink($request->only('email'));
    }
}
