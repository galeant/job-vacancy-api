<?php

namespace App\Services;
use App\Repositories\JobVacancyRepository;
use App\Repositories\ApplicantRepository;
use App\Models\JobVacancy;
use App\Enums\JobVacancyStatus;
use Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class JobVacancyService
{
    public $jobVacancyRepository;
    public $applicantRepository;

    public function __construct()
    {
        $this->jobVacancyRepository = new JobVacancyRepository();
        $this->applicantRepository = new ApplicantRepository();
    }

    public function getList(array $params = []):Collection|LengthAwarePaginator{
        if(Auth::guard('company-api')->user()){
            $params['company_id'] = Auth::guard('company-api')->user()->company_id;
        }else{
            $params['status'] = [JobVacancyStatus::PUBLISHED->value];
            $params['deadline'] = now()->toDateString();
        }

        if(Auth::guard('applicant-api')->user()){
            $params['applicant_id'] = Auth::guard('applicant-api')->user()->id;
        }

        return $this->jobVacancyRepository->search(filters:$params);
    }


    public function create(array $payload):JobVacancy
    {
        $user = Auth::guard('company-api')->user();
        return $this->jobVacancyRepository->create([
            'company_id' => $user->company_id,
            'title' => $payload['title'],
            'description' => $payload['description'],
            'location' => $payload['location'],
            'salary' => $payload['salary'],
            'deadline' => $payload['deadline'],
            'status' => JobVacancyStatus::DRAFT->value,
        ]);
    }

    public function update($id, array $payload):JobVacancy{
        return $this->jobVacancyRepository->update($id, [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'location' => $payload['location'],
            'salary' => $payload['salary'],
            'deadline' => $payload['deadline'],

            'status' => JobVacancyStatus::DRAFT->value,
        ]);
    }

    public function publish(JobVacancy $jobVacancy):JobVacancy{
        if($jobVacancy->status != JobVacancyStatus::DRAFT){
            throw new \Exception('Data not in draft state');
        }

        return $this->jobVacancyRepository->update($jobVacancy->id, [
            'status' => JobVacancyStatus::PUBLISHED->value,
        ]);
    }

    public function inactivate(JobVacancy $jobVacancy):JobVacancy{
        if($jobVacancy->status == JobVacancyStatus::INACTIVE){
            throw new \Exception('Data is already inactive');
        }
        return $this->jobVacancyRepository->update($jobVacancy->id, [
            'status' => JobVacancyStatus::INACTIVE->value,
        ]);
    }

    public function apply(array $payload):void{
        $applicant = Auth::guard('applicant-api')->user();
        $vacancy = $this->jobVacancyRepository->findById($payload['vacancy_id'],$applicant->id);

        if(!$vacancy || $vacancy->status != JobVacancyStatus::PUBLISHED){
            throw ValidationException::withMessages([
                'vacancy_id' => ['job vacancy not eligible'],
            ]);
        }

        if($vacancy->applicants->count() > 0){
            throw ValidationException::withMessages([
                'vacancy_id' => ['already applied'],
            ]);
        }

        $this->jobVacancyRepository->apply($applicant, $payload['vacancy_id']);
    }

    public function applied(JobVacancy $jobVacancy, array $params = []){
        $params['job_vacancy_id'] = $jobVacancy->id;
        return $this->applicantRepository->search(filters:$params);
    }
}
