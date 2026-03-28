<?php

namespace App\Services;
use App\Repositories\JobVacancyRepository;
use App\Models\JobVacancy;
use App\Enums\JobVacancyStatus;
use Auth;

class JobVacancyService
{
    public $jobVacancyRepository;

    public function __construct()
    {
        $this->jobVacancyRepository = new JobVacancyRepository();
    }

    public function getList(array $params = []){
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

    public function publish($id):JobVacancy{
        return $this->jobVacancyRepository->update($id, [
            'status' => JobVacancyStatus::PUBLISHED->value,
        ]);
    }

    public function inactivate($id):JobVacancy{
        return $this->jobVacancyRepository->update($id, [
            'status' => JobVacancyStatus::INACTIVE->value,
        ]);
    }

    public function apply(array $payload):void{
        $applicant = Auth::guard('applicant-api')->user();
        $this->jobVacancyRepository->apply($applicant, $payload['vacancy_id']);
    }
}
