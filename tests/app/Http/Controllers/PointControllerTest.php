<?php

namespace Tests\App\Http\Controllers;

use App\Models\Permission;
use App\Models\Point;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Scale;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=PointControllerTest
class PointControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testGetItemsNoPermissions
     *
     * Test the behavior of the get points route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testGetItemsNoPermissions()
    {
        $this->artisan('passport:install');

        // Create Point
        $this->createPoint();

        // Get Headers no permissions Administrator
        $headers = $this->getHeadersNoPermissions();

        // Send a GET request to try to get the items without permission
        $response = $this->withHeaders($headers)->get('api/points');

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testGetItemsWithPermissions
     *
     * Test the behavior of the get points route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function testGetItemsWithPermissions()
    {
        $this->artisan('passport:install');

        // Create Point
        $this->createPoint();

        // Get Headers with permissions Administrator
        $headers = $this->getHeadersWithPermissions('View');

        // Send a GET request to try to get the items without permission
        $response = $this->withHeaders($headers)->get('api/points');

        // Check the HTTP status of the response
        $response->assertStatus(200);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testCreateNoPermissions
     *
     * Test the behavior of the create points route when credentials are provided.
     * Verifies when credentials are provided.
     *
     * @return void
     */
    public function testCreateNoPermissions()
    {
        $this->artisan('passport:install');

        // Create a Role and a Scale
        $role = Role::factory()->create();
        $scale = Scale::factory()->create();

        // Create User
        $user = User::factory()->create([
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        // Get Headers no permissions
        $headers = $this->getHeadersNoPermissions();

        // Send a POST request to try to create the item without permission
        $response = $this->withHeaders($headers)->post('api/points', [
            'register' => now(),
            'latitude' => rand(-90, 90),
            'longitude' => rand(-180, 180),
            'user_id' => $user->id
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testCreateWithPermissions
     *
     * Test the behavior of the create points route when credentials are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function testCreateWithPermissions()
    {
        $this->artisan('passport:install');

        // Create a Role and a Scale
        $role = Role::factory()->create();
        $scale = Scale::factory()->create();

        // Create User
        $user = User::factory()->create([
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        // Get Headers with permissions
        $headers = $this->getHeadersWithPermissions('Create');

        // Send a POST request to try to create the item with permission
        $response = $this->withHeaders($headers)->post('api/points', [
            'register' => now(),
            'latitude' => rand(-90, 90),
            'longitude' => rand(-180, 180),
            'user_id' => $user->id
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(201);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testUpdateNoPermissions
     *
     * Test the behavior of the update points route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testUpdateNoPermissions()
    {
        $this->artisan('passport:install');

        // Create Point
        $point = $this->createPoint();

        // Get Headers no permissions
        $headers = $this->getHeadersNoPermissions();

        // Send a PUT request to try to update the item without permission
        $response = $this->withHeaders($headers)->put('api/points/' . $point->id, [
            'register' => now(),
            'latitude' => rand(-90, 90),
            'longitude' => rand(-180, 180),
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testUpdateWithPermissions
     *
     * Test the behavior of the update points route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function  testUpdateWithPermissions()
    {
        $this->artisan('passport:install');

        // Create Point
        $point = $this->createPoint();

        // Get Headers with permissions
        $headers = $this->getHeadersWithPermissions('Update');

        // Send a PUT request to try to update the item with permission
        $response = $this->withHeaders($headers)->put('api/points/' . $point->id, [
            'register' => now(),
            'latitude' => rand(-90, 90),
            'longitude' => rand(-180, 180),
        ]);

        // Check the HTTP status of the response
        $response->assertStatus(200);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testDeleteNoPermissions
     *
     * Test the behavior of the delete points route when credentials no permissions are provided.
     * Verifies when credentials no permissions are provided.
     *
     * @return void
     */
    public function testDeleteNoPermissions()
    {
        $this->artisan('passport:install');

        // Create Point
        $point = $this->createPoint();

        // Get Headers no permissions
        $headers = $this->getHeadersNoPermissions();

        // Send a DELETE request to try to delete the item without permission
        $response = $this->withHeaders($headers)->delete('api/points/' . $point->id);

        // Check the HTTP status of the response
        $response->assertStatus(403);
    }

    /**
     * docker-compose exec app php artisan test --filter=PointControllerTest::testDeleteWithPermissions
     *
     * Test the behavior of the delete points route when credentials with permissions are provided.
     * Verifies when credentials with permissions are provided.
     *
     * @return void
     */
    public function  testDeleteWithPermissions()
    {
        $this->artisan('passport:install');

        // Create Point
        $point = $this->createPoint();

        // Get Headers with permissions
        $headers = $this->getHeadersWithPermissions('Delete');

        // Send a DELETE request to try to delete the item with permission
        $response = $this->withHeaders($headers)->delete('api/points/' . $point->id);

        // Check the HTTP status of the response
        $response->assertStatus(200);
    }

    /**
     * Generate a token for the user no permissions
     *
     * @return array
     */
    private function getHeadersNoPermissions()
    {
        // Create a User
        Role::factory()->create();
        Scale::factory()->create();
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        // Generate a Token token for the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = User::find($user->id)->createToken('accessToken')->accessToken;

            // Set the Token token in the request
            return ['Authorization' => 'Bearer ' . $token];
        }
    }

    /**
     * Generate a token for the user with permissions
     *
     * @return array
     */
    private function getHeadersWithPermissions(string $permission)
    {
        // Create a RolePermission
        $role = Role::factory()->create();
        $scale = Scale::factory()->create();
        RolePermission::create([
            'role_id' => $role->id,
            'permission_id' => Permission::create([
                'name' => $permission
            ])->id,
        ]);

        // Create a User
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        // Generate a Token token for the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = User::find($user->id)->createToken('accessToken')->accessToken;

            // Set the Token token in the request
            return ['Authorization' => 'Bearer ' . $token];
        }
    }

    /**
     * Create point
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model|\App\Models\User>|\Illuminate\Database\Eloquent\Model
     */
    private function createPoint()
    {
        // Create a Role and a Scale
        $role = Role::factory()->create();
        $scale = Scale::factory()->create();

        // Create User
        $user = User::factory()->create([
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        // Create Point
        $point = Point::factory()->create([
            'user_id' => $user->id
        ]);

        return $point;
    }
}
