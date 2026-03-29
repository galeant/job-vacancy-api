<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Applicant;

class AuthApplicantTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_applicant_registration(): void
    {
        $response = $this->post(route('applicant.register'), [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',

            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'skills' => json_encode(['PHP', 'Laravel']),
            'experience' => json_encode([
                [
                    'company' => $this->faker->company(),
                    'position' => $this->faker->jobTitle(),
                    'start_date' => $this->faker->date(),
                    'end_date' => $this->faker->date(),
                ],
            ]),
            'education' => json_encode([
                [
                    'institution' => $this->faker->company(),
                    'degree' => $this->faker->word(),
                    'field_of_study' => $this->faker->word(),
                    'start_date' => $this->faker->date(),
                    'end_date' => $this->faker->date(),
                ],
            ]),
            'certifications' => json_encode([
                [
                    'name' => $this->faker->word(),
                    'issuer' => $this->faker->company(),
                    'date' => $this->faker->date(),
                ],
            ]),
            'projects' => json_encode([
                [
                    'name' => $this->faker->word(),
                    'description' => $this->faker->sentence(),
                    'link' => $this->faker->url(),
                ],
            ]),
            'languages' => json_encode([
                [
                    'name' => $this->faker->word(),
                    'proficiency' => $this->faker->randomElement(['basic', 'intermediate', 'advanced', 'native']),
                ],
            ]),
            'cv_file' => null,
            'profile_picture' => null,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('applicants', [
            'email' => $response->json('data.applicant.email'),
        ]);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'applicant' => [
                    'id',
                    'name',
                    'profile_picture',
                    'email',
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
                ],
                'token',
            ],
        ]);
    }


    public function test_applicant_login(): void
    {
        $applicant = Applicant::factory()
            ->create([
                'password' => bcrypt('password'),
            ]);

        $response = $this->post(route('applicant.login'), [
            'email' => $applicant->email,
            'password' => 'password',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'applicant' => [
                    'id',
                    'name',
                    'profile_picture',
                    'email',
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
                ],
                'token',
            ],
        ]);
    }

    public function test_applicant_logout(): void
    {
        $applicant = Applicant::factory()
            ->create([
                'password' => bcrypt('password'),
            ]);

        $token = $applicant->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('applicant.logout'));

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Logout success',
            'data' => null,
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $applicant->id,
            'tokenable_type' => get_class($applicant),
        ]);
    }
}
