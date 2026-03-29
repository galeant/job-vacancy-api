<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Applicant;
use App\Models\User;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Enums\JobVacancyStatus;

class JobVacancyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $applicant;
    public $company;
    public $user;

    public $draftVacancy;
    public $publishVacancy;
    public $inactiveVacancy;

    public function setUp(): void
    {
        parent::setUp();

        $this->applicant = Applicant::factory()->create();
        $this->company = Company::factory()->create();
        $this->user = User::factory()
            ->for($this->company, 'company')
            ->create();

        $this->draftVacancy = JobVacancy::factory()
            ->count(2)
            ->create([
                'status' => JobVacancyStatus::DRAFT->value,
            ]);

        $this->publishVacancy = JobVacancy::factory()
            ->count(2)
            ->create([
                'status' => JobVacancyStatus::PUBLISHED->value,
            ]);

        $this->inactiveVacancy = JobVacancy::factory()
            ->count(2)
            ->create([
                'status' => JobVacancyStatus::INACTIVE->value,
                'deadline' => now()->subDays()->format('Y-m-d')
            ]);
    }

    public function test_company_get_list(){
        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.list'));

        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');

        $response->assertJsonFragment([
            'id' => $this->draftVacancy[0]->id,
            'title' => $this->draftVacancy[0]->title,
            'description' => $this->draftVacancy[0]->description,
            'location' => $this->draftVacancy[0]->location,
            'salary' => $this->draftVacancy[0]->salary,
            'status' => $this->draftVacancy[0]->status,
            'deadline' => $this->draftVacancy[0]->deadline,
        ]);

        $response->assertJsonFragment([
            'id' => $this->publishVacancy[0]->id,
            'title' => $this->publishVacancy[0]->title,
            'description' => $this->publishVacancy[0]->description,
            'location' => $this->publishVacancy[0]->location,
            'salary' => $this->publishVacancy[0]->salary,
            'status' => $this->publishVacancy[0]->status,
            'deadline' => $this->publishVacancy[0]->deadline,
        ]);

        $response->assertJsonFragment([
            'id' => $this->inactiveVacancy[0]->id,
            'title' => $this->inactiveVacancy[0]->title,
            'description' => $this->inactiveVacancy[0]->description,
            'location' => $this->inactiveVacancy[0]->location,
            'salary' => $this->inactiveVacancy[0]->salary,
            'status' => $this->inactiveVacancy[0]->status,
            'deadline' => $this->inactiveVacancy[0]->deadline,
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'location',
                    'salary',
                    'status',
                    'status_text',
                    'deadline',
                    'is_applied',
                ]
            ]
        ]);
    }

    public function test_applicant_get_list(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.list'));

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        $response->assertJsonFragment([
            'id' => $this->publishVacancy[0]->id,
            'title' => $this->publishVacancy[0]->title,
            'description' => $this->publishVacancy[0]->description,
            'location' => $this->publishVacancy[0]->location,
            'salary' => $this->publishVacancy[0]->salary,
            'status' => $this->publishVacancy[0]->status,
            'deadline' => $this->publishVacancy[0]->deadline,
        ]);

        $response->assertJsonMissing([
            'id' => $this->draftVacancy[0]->id
        ]);

        $response->assertJsonMissing([
            'id' => $this->inactiveVacancy[0]->id
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'location',
                    'salary',
                    'status',
                    'status_text',
                    'deadline',
                    'is_applied',
                ]
            ]
        ]);
    }

    public function test_company_vacancy_create(){

        $deadline = \Carbon\Carbon::now()->addDays(rand(1,20));
        $payload = [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'deadline' => \Carbon\Carbon::parse($deadline)->format('Y-m-d'),
        ];

        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.create'),$payload);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'title' => $payload['title'],
                'description' => $payload['description'],
                'location' => $payload['location'],
                'salary' => $payload['salary'],
                'deadline' => $deadline->startOfDay()->toJson(),
            ],
        ]);

        $this->assertDatabaseHas('job_vacancies', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'location' => $payload['location'],
            'salary' => $payload['salary'],
        ]);
    }

    public function test_applicant_vacancy_create(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.create'),[]);
        $response->assertStatus(401);
    }

    public function test_company_vacancy_update(){
        $deadline = \Carbon\Carbon::now()->addDays(rand(1,20));
        $payload = [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'deadline' => \Carbon\Carbon::parse($deadline)->format('Y-m-d'),
        ];

        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.update',[
            'vacancy' => $this->draftVacancy[0]->id
        ]),$payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_vacancies', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'location' => $payload['location'],
            'salary' => $payload['salary'],
        ]);
    }

    public function test_applicant_vacancy_update(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.update',[
            'vacancy' => $this->draftVacancy[0]->id
        ]),[]);
        $response->assertStatus(401);
    }

    public function test_company_vacancy_publish(){
        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.publish',[
            'vacancy' => $this->draftVacancy[0]->id
        ]),[]);
        $response->assertStatus(200);
    }

    public function test_applicant_vacancy_publish(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.update',[
            'vacancy' => $this->draftVacancy[0]->id
        ]),[]);
        $response->assertStatus(401);
    }

    public function test_company_vacancy_inactive(){
        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.inactivate',[
            'vacancy' => $this->draftVacancy[0]->id
        ]),[]);
        $response->assertStatus(200);
    }

    public function test_applicant_vacancy_inactive(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.inactivate',[
            'vacancy' => $this->draftVacancy[0]->id
        ]),[]);
        $response->assertStatus(401);
    }

    public function test_company_vacancy_apply(){
        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.apply',[
            'vacancy_id' => $this->publishVacancy[0]->id
        ]),[]);
        $response->assertStatus(401);
    }

    public function test_applicant_vacancy_apply(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('vacancy.apply',[
            'vacancy_id' => $this->publishVacancy[0]->id
        ]),[]);

        $response->assertStatus(200);
        $list = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.list'));

        $list->assertJsonFragment([
            'is_applied' => true,
        ]);
    }

    public function test_company_vacancy_applied(){
        $token = $this->user->createToken('test-token')->plainTextToken;
        $this->publishVacancy[0]->applicants()->sync([
            $this->applicant->id => [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.applied',[
            'vacancy' => $this->publishVacancy[0],
        ]));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->applicant->id,
            'name' => $this->applicant->name,
            'email' => $this->applicant->email,
        ]);
    }

    public function test_applicant_vacancy_applied(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.applied',[
            'vacancy' => $this->publishVacancy[0]
        ]));
        $response->assertStatus(401);
    }

    public function test_company_vacancy_job_apply(){
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.job-apply'));

        $response->assertStatus(401);
    }

    public function test_applicant_vacancy_job_apply(){
        $token = $this->applicant->createToken('test-token')->plainTextToken;
        $this->applicant->vacancies()->sync([
            $this->publishVacancy[1]->id => [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('vacancy.job-apply'));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->publishVacancy[1]->id,
            'title' => $this->publishVacancy[1]->title,
        ]);
    }
}
