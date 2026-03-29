<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\UpdateProfileRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ){}

    public function profile(){
        $auth = Auth::guard('company-api')->user();
        $auth->load('company');
        return new UserResource($auth);
    }

    public function updateProfile(UpdateProfileRequest $request){
        $auth = Auth::guard('company-api')->user();
        $user = $this->companyService->updateProfile(
            user:$auth,
            company:$auth->company,
            payload:$request->validated()
        );

        return new UserResource($user);
    }
}
