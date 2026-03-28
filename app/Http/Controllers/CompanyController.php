<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\UpdateProfileRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ){}

    public function profile(){
        $auth = Auth::guard('company-api')->user();
        return new CompanyResource($auth->company);
    }

    public function updateProfile(UpdateProfileRequest $request){
        $auth = Auth::guard('company-api')->user();
        $company = $this->companyService->updateProfile(
            company:$auth->company,
            payload:$request->validated()
        );
        return new CompanyResource($company);
    }
}
