<?php

namespace Tests\App\Http\Controllers;

use App\Models\Role;
use App\Models\Scale;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=AuthControllerTest
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=AuthControllerTest::testInvalidCredentials
     *
     * Test the behavior of the login route when invalid credentials are provided.
     * Verifies when invalid email and password credentials are provided.
     * It also checks that the user is not authenticated after the invalid login attempt.
     *
     * @return void
     */
    public function testInvalidCredentials()
    {
        $this->artisan('passport:install');

        // Send a POST request to the login route with invalid credentials
        $response = $this->post('api/auth/login', [
            'email' => 'invalid_email@noemail.com',
            'password' => 'invalid_password',
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(401);

        // Verify that the user is not authenticated after the invalid login attempt
        $this->assertGuest();
    }

    /**
     * docker-compose exec app php artisan test --filter=AuthControllerTest::testValidCredentials
     *
     * Test the behavior of the login route when valid credentials are provided.
     * Verifies when valid email and password credentials are provided.
     * It also checks that the user authenticated after the valid login attempt.
     *
     * @return void
     */
    public function testValidCredentials()
    {
        $this->artisan('passport:install');

        // Create a Role and a Scale
        Role::factory()->create();
        Scale::factory()->create();

        // Create a User
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        // Send a POST request to the login route with invalid credentials
        $response = $this->post('api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(200);

        // Verify that the user authenticated after the valid login attempt
        $this->assertAuthenticated();
    }
}
