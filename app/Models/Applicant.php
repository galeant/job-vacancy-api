<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name',
    'profile_picture',
    'email',
    'password',
    'phone',
    'resume',
    'cover_letter',
    'skills',
    'experience',
    'education',
    'certifications',
    'projects',
    'languages',
    'cv_file',
])]
#[Hidden(['password'])]
class Applicant extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\ApplicantFactory> */
    use HasFactory,HasApiTokens;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'skills' => 'json',
            'experience' => 'json',
            'education' => 'json',
            'certifications' => 'json',
            'projects' => 'json',
            'languages' => 'json',
        ];
    }

    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(JobVacancy::class, 'applied_tables')
            ->withTimestamps();
    }
}
