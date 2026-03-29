<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Company;
use App\Models\User;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $company;
    public $user;
    public function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();
        $this->user = User::factory()
            ->for($this->company, 'company')
            ->create();
    }

    public function test_profile(): void
    {
        $token = $this->user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(route('company.profile'));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'company' => [
                    'name' => $this->company->name,
                    'email' => $this->company->email,
                    'phone' => $this->company->phone,
                    'description' => $this->company->description,
                    'address' => $this->company->address,
                ]
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'company' => [
                    'name',
                    'email',
                    'phone',
                    'description',
                    'address',
                ]
            ],
        ]);
    }


    public function test_update_profile(){
        $token = $this->user->createToken('test-token')->plainTextToken;
        $payload = [
            'email' => $this->faker->unique()->safeEmail(),
            'company_name' => $this->faker->name(),
            'company_email' => $this->faker->unique()->safeEmail(),
            'company_phone' => $this->faker->phoneNumber(),
            'company_description' => $this->faker->text(),
            'company_address' => $this->faker->text(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('company.profile-update'), $payload);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $this->user->id,
                'email' => $payload['email'],
                'company' => [
                    'name' => $payload['company_name'],
                    'email' => $payload['company_email'],
                    'phone' => $payload['company_phone'],
                    'description' => $payload['company_description'],
                    'address' => $payload['company_address'],
                ]
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'company' => [
                    'name',
                    'email',
                    'phone',
                    'description',
                    'address',
                ]
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => $payload['company_name'],
            'email' => $payload['company_email'],
            'phone' => $payload['company_phone'],
            'description' => $payload['company_description'],
            'address' => $payload['company_address'],
        ]);
    }
}
