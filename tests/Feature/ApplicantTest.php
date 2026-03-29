<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Applicant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApplicantTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $applicant;

    public function setUp(): void
    {
        parent::setUp();

        $this->applicant = Applicant::factory()->create();
    }

    public function test_profile(): void
    {
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('applicant.profile'));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'email' => $this->applicant->email,
                'name' => $this->applicant->name,
                'profile_picture' => asset($this->applicant->profile_picture),
                'phone' => $this->applicant->phone,
                'resume' => $this->applicant->resume,
                'cover_letter' => $this->applicant->cover_letter,
                'skills' => $this->applicant->skills,
                'experience' => $this->applicant->experience,
                'education' => $this->applicant->education,
                'certifications' => $this->applicant->certifications,
                'projects' => $this->applicant->projects,
                'languages' => $this->applicant->languages,
                'cv_file' => asset($this->applicant->cv_file),
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'email',
                'name',
                'profile_picture',
                'phone',
                'resume',
                'cover_letter',
                'skills',
                'experience',
                'education',
                'certifications',
                'projects',
                'languages',
                'cv_file'
            ]
        ]);
    }

    public function test_update_profile(): void
    {
        $token = $this->applicant->createToken('test-token')->plainTextToken;

        Storage::fake('public');

        $imgName = 'profile.jpg';
        $fileName = 'cv.pdf';
        $img = UploadedFile::fake()->create($imgName, 500);
        $file = UploadedFile::fake()->create($fileName, 500);

        $skills = ['PHP', 'Laravel', 'JavaScript'];
        $experience = [
            [
                'company' => 'Company A',
                'position' => 'Developer',
                'duration' => '2 years',
            ],
        ];
        $education = [
            [
                'institution' => 'University A',
                'degree' => 'Bachelor of Science',
                'field_of_study' => 'Computer Science',
                'graduation_year' => '2020',
            ],
        ];
        $certifications = [
            [
                'name' => 'Certification A',
                'issuer' => 'Issuer A',
                'date' => '2021-01-01',
            ],
        ];
        $projects = [
            [
                'name' => 'Project A',
                'description' => 'Description of Project A',
                'link' => '',
            ],
        ];
        $languages = ['English', 'Spanish'];

        $payload = [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'profile_picture' => $img,
            'phone' => $this->faker->phoneNumber(),
            'resume' => $this->faker->text(),
            'cover_letter' => $this->faker->text(),
            'skills' => json_encode($skills),
            'experience' => json_encode($experience),
            'education' => json_encode($education),
            'certifications' => json_encode($certifications),
            'projects' => json_encode($projects),
            'languages' => json_encode($languages),
            'cv_file' => $file,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('applicant.profile-update'), $payload);
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'email' => $payload['email'],
                'name' => $payload['name'],
                'profile_picture' => asset('storage/profile_pictures/' . time().'_'.$imgName),
                'phone' => $payload['phone'],
                'resume' => $payload['resume'],
                'cover_letter' => $payload['cover_letter'],
                'skills' => $payload['skills'],
                'experience' => $payload['experience'],
                'education' => $payload['education'],
                'certifications' => $payload['certifications'],
                'projects' => $payload['projects'],
                'languages' => $payload['languages'],
                'cv_file' => asset('storage/cv_files/' . time().'_'.$fileName),
            ]
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'name',
                'profile_picture',
                'phone',
                'resume',
                'cover_letter',
                'skills',
                'experience',
                'education',
                'certifications',
                'projects',
                'languages',
                'cv_file'
            ]
        ]);

        $this->assertDatabaseHas('applicants', [
            'email' => $payload['email'],
            'name' => $payload['name'],
            'profile_picture' => 'storage/profile_pictures/' . time().'_'.$imgName,
            'phone' => $payload['phone'],
            'resume' => $payload['resume'],
            'cover_letter' => $payload['cover_letter'],
            'skills' => json_encode($payload['skills']),
            'experience' => json_encode($payload['experience']),
            'education' => json_encode($payload['education']),
            'certifications' => json_encode($payload['certifications']),
            'projects' => json_encode($payload['projects']),
            'languages' => json_encode($payload['languages']),
            'cv_file' => 'storage/cv_files/' . time().'_'.$fileName,
        ]);
    }
}
