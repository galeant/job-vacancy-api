<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Applicant;
use App\Models\JobVacancy;
use App\Enums\JobVacancyStatus;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company = Company::factory()->create();
        User::factory()->for($company)->create([
            'email' => 'cp1@mail.com',
        ]);

        $applicant = Applicant::factory()->create([
            'email' => 'ap1@mail.com'
        ]);

        JobVacancy::factory()->count(3)->create([
            'company_id' => $company->id,
            'status' => JobVacancyStatus::PUBLISHED->value,
        ])->each(function ($vacancy) use ($applicant) {
            $vacancy->applicants()->attach([$applicant->id => [
                'created_at' => now(),
                'updated_at' => now(),
            ]]);
        });

        JobVacancy::factory()->count(3)->create([
            'company_id' => $company->id,
            'status' => JobVacancyStatus::DRAFT->value,
        ]);

        JobVacancy::factory()->count(3)->create([
            'company_id' => $company->id,
            'status' => JobVacancyStatus::INACTIVE->value,
        ]);
    }
}
