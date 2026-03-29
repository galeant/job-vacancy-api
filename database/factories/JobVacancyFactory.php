<?php

namespace Database\Factories;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Enums\JobVacancyStatus;

/**
 * @extends Factory<JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = Company::inRandomOrder()->first() ?? Company::factory()->create();
        return [
            'company_id' => $company->id,
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'status' => JobVacancyStatus::getRandom()->value,
            'deadline' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
