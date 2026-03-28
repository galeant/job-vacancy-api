<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\CompanyRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\AuthenticateUser;
use App\Services\CompanyService;
use App\Http\Resources\UserResource;

class AuthCompanyController extends Controller
{
    use AuthenticateUser;

    public function __construct(
        protected CompanyService $companyService
    ){}

    public function register(CompanyRegisterRequest $request){
        $user = $this->companyService->register($request->validated());
        return response()->json([
            'message' => 'Registration success',
            'data' => [
                'user' => new UserResource($user),
                'token' => $user->createToken('token')->plainTextToken,
            ],
        ], 201);
    }

    public function login(LoginRequest $request){
        $user = self::processLogin($request->only('email', 'password'), 'company');
        return response()->json([
            'message' => 'Login success',
            'data' => [
                'user' => new UserResource($user),
                'token' => $user->createToken('token')->plainTextToken,
            ],
        ], 201);
    }

    public function logout(Request $request){
        self::processLogout($request, 'company');
        return response()->json([
            'message' => 'Logout success',
            'data' => null,
        ], 201);
    }
}
