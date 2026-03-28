<?php

namespace App\Services;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Company;

class CompanyService
{
    public $companyRepository;
    public $userRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
        $this->userRepository = new UserRepository();
    }


    public function register(array $payload):User
    {
        $company = $this->companyRepository->create([
            'name' => $payload['company_name'],
            'email' => $payload['company_email'],
            'phone' => $payload['company_phone'],
            'description' => $payload['company_description'] ?? null,
            'address' => $payload['company_address'] ?? null,
        ]);
        $user = $this->userRepository->create([
            'email' => $payload['email'],
            'password' => $payload['password'],
            'company_id' => $company->id,
        ]);

        return $user->load('company');
    }

    public function updateProfile(Company $company, array $payload){
        return $this->companyRepository->update($company->id, [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'description' => $payload['description'],
            'address' => $payload['address'],
        ]);
    }
}
