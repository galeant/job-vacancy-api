<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Company;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AuthCompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_company_registration(): void
    {
        $response = $this->post(route('company.register'), [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',

            'company_name' => 'Test Company',
            'company_email' => $this->faker->unique()->safeEmail(),
            'company_phone' => $this->faker->phoneNumber(),
            'company_description' => $this->faker->paragraph(),
            'company_address' => $this->faker->address(),
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
        ]);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'user' => [
                    'id',
                    'email',
                ],
                'token',
            ],
        ]);
    }

    public function test_company_login(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()
            ->for($company, 'company')
            ->create([
                'password' => bcrypt('password'),
            ]);

        $response = $this->post(route('company.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'user' => [
                    'id',
                    'email',
                ],
                'token',
            ],
        ]);
    }

    public function test_company_logout(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()
            ->for($company, 'company')
            ->create([
                'password' => bcrypt('password'),
            ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('company.logout'));

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Logout success',
            'data' => null,
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => get_class($user),
        ]);
    }
}
