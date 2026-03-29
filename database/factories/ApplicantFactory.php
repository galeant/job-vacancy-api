<?php

namespace Database\Factories;

use App\Models\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Applicant>
 */
class ApplicantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'profile_picture' => $this->faker->imageUrl(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            'resume' => $this->faker->text(),
            'cover_letter' => $this->faker->text(),
            'skills' => $this->faker->words(5, true),
            'experience' => $this->faker->paragraph(),
            'education' => $this->faker->paragraph(),
            'certifications' => $this->faker->words(3, true),
            'projects' => $this->faker->words(3, true),
            'languages' => $this->faker->words(3, true),
            'cv_file' => $this->faker->word() . '.pdf',
        ];
    }
}
