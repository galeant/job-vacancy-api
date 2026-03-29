<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancy\CreateRequest;
use App\Http\Requests\JobVacancy\UpdateRequest;
use App\Http\Requests\JobVacancy\ApplyRequest;
use App\Http\Requests\JobVacancy\AppliedRequest;
use App\Http\Requests\JobVacancy\GetListRequest;
use App\Http\Resources\JobVacancyResource;
use App\Http\Resources\ApplicantResource;
use App\Services\JobVacancyService;
use App\Models\JobVacancy;

class JobVacancyController extends Controller
{
    public function __construct(
        protected JobVacancyService $jobVacancyService
    ){}

    public function getList(GetListRequest $request){
        $vacancies =  $this->jobVacancyService->getList($request->validated());
        return JobVacancyResource::collection($vacancies);
    }

    public function create(CreateRequest $request){
        $jobVacancy = $this->jobVacancyService->create($request->validated());
        return new JobVacancyResource($jobVacancy);
    }

    public function update(UpdateRequest $request, JobVacancy $vacancy){
        $jobVacancy = $this->jobVacancyService->update($vacancy->id, $request->validated());
        return new JobVacancyResource($jobVacancy);
    }

    public function publish(JobVacancy $vacancy){
        auth('company-api')->user()->can('update', $vacancy->id);

        $jobVacancy = $this->jobVacancyService->publish($vacancy);
        return new JobVacancyResource($jobVacancy);
    }

    public function inactivate(JobVacancy $vacancy){
        auth('company-api')->user()->can('update', $vacancy->id);

        $jobVacancy = $this->jobVacancyService->inactivate($vacancy);
        return new JobVacancyResource($jobVacancy);
    }

    public function apply(ApplyRequest $request){
        $this->jobVacancyService->apply($request->validated());
        return response()->json(['message' => 'Application submitted successfully','data' => null],200);
    }

    public function applied(JobVacancy $vacancy,AppliedRequest $request){
        $applicants = $this->jobVacancyService->applied($vacancy, $request->validated());
        return ApplicantResource::collection($applicants);
    }
}
