<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\ApplicantRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\AuthenticateUser;
use App\Services\ApplicantService;
use App\Http\Resources\ApplicantResource;

class AuthApplicantController extends Controller
{
    use AuthenticateUser;

    public function __construct(
        protected ApplicantService $applicantService
    ){}

    public function register(ApplicantRegisterRequest $request){
        $applicant = $this->applicantService->register($request->validated());
        return response()->json([
            'message' => 'Registration success',
            'data' => [
                'applicant' => new ApplicantResource($applicant),
                'token' => $applicant->createToken('token')->plainTextToken,
            ],
        ], 201);
    }

    public function login(LoginRequest $request){
        $applicant = self::processLogin($request->only('email', 'password'), 'applicant');

        return response()->json([
            'message' => 'Login success',
            'data' => [
                'applicant' => new ApplicantResource($applicant),
                'token' => $applicant->createToken('token')->plainTextToken,
            ],
        ], 201);
    }

    public function logout(Request $request){
        self::processLogout($request, 'applicant-api');
        return response()->json([
            'message' => 'Logout success',
            'data' => null,
        ], 201);
    }
}
