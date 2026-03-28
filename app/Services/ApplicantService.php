<?php

namespace App\Services;
use App\Repositories\ApplicantRepository;
use App\Models\Applicant;

class ApplicantService
{
    public $applicantRepository;

    public function __construct()
    {
        $this->applicantRepository = new ApplicantRepository();
    }


    public function register(array $payload):Applicant
    {
        $profilePicturePath = null;
        if (isset($payload['profile_picture'])) {
            $profilePicturePath = $payload['profile_picture']->store('profile_pictures', 'public');
        }

        $cvFilePath = null;
        if (isset($payload['cv_file'])) {
            $cvFilePath = $payload['cv_file']->store('cv_files', 'public');
        }

        $applicant = $this->applicantRepository->create([
            'name' => $payload['name'],
            'profile_picture' => $profilePicturePath,
            'email' => $payload['email'],
            'password' => $payload['password'],
            'phone' => $payload['phone'],
            'resume' => $payload['resume'] ?? null,
            'cover_letter' => $payload['cover_letter'] ?? null,
            'skills' => $payload['skills'] ?? null,
            'experience' => $payload['experience'] ?? null,
            'education' => $payload['education'] ?? null,
            'certifications' => $payload['certifications'] ?? null,
            'projects' => $payload['projects'] ?? null,
            'languages' => $payload['languages'] ?? null,
            'cv_file' => $cvFilePath,
        ]);

        return $applicant;
    }

    public function updateProfile(Applicant $applicant, array $payload){
        $profilePicturePath = null;
        if (isset($payload['profile_picture'])) {
            $profilePicturePath = $payload['profile_picture']->store('profile_pictures', 'public');
        }

        $cvFilePath = null;
        if (isset($payload['cv_file'])) {
            $cvFilePath = $payload['cv_file']->store('cv_files', 'public');
        }
        return $this->applicantRepository->update($applicant->id, [
            'name' => $payload['name'],
            'profile_picture' => $profilePicturePath,
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'resume' => $payload['resume'] ?? null,
            'cover_letter' => $payload['cover_letter'] ?? null,
            'skills' => $payload['skills'] ?? null,
            'experience' => $payload['experience'] ?? null,
            'education' => $payload['education'] ?? null,
            'certifications' => $payload['certifications'] ?? null,
            'projects' => $payload['projects'] ?? null,
            'languages' => $payload['languages'] ?? null,
            'cv_file' => $cvFilePath,
        ]);
    }
}
