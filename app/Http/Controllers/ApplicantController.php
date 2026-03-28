<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApplicantService;
use App\Http\Resources\ApplicantResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Applicant\UpdateProfileRequest;

class ApplicantController extends Controller
{
    public function __construct(
        protected ApplicantService $applicantService
    ){}

    public function profile(){
        $auth = Auth::guard('applicant-api')->user();
        return new ApplicantResource($auth);
    }

    public function updateProfile(UpdateProfileRequest $request){
        $auth = Auth::guard('applicant-api')->user();
        $applicant = $this->applicantService->updateProfile(
            applicant:$auth,
            payload:$request->validated()
        );
        return new ApplicantResource($applicant);
    }
}
