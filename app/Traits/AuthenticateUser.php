<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Applicant;

trait AuthenticateUser
{
    public function processLogin(array $credentials, string $guard):User|Applicant
    {
        if (Auth::guard($guard)->attempt($credentials)) {
            return Auth::guard($guard)->user();
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }

    public function processLogout(Request $request, string $guard):true
    {
        $request->user()->tokens()->delete();
        return true;
    }
}
