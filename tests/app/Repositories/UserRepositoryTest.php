<?php

namespace Tests\App\Http\Repositories;

use App\Models\Role;
use App\Models\Scale;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=UserRepositoryTest
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=UserServiceTest::testExtendsClassService
     *
     * Checks if the service class is behaving as expected
     *
     * @return void
     */
    public function testExtendsClassService()
    {
        // New instance Repository
        $repo = new UserRepository();

        // Assert that the 'findByID' exists in the UserRepository.
        // This test ensures that the desired function is implemented and available for use.
        $this->assertTrue(method_exists($repo, 'findByID'));
    }

    /**
     * docker-compose exec app php artisan test --filter=UserRepositoryTest::testInstanceOfFindByID
     *
     * Checks if the repository class is behaving as expected
     *
     * Verifies if the return is a valid model
     *
     * @return void
     */
    public function testInstanceOfFindByID()
    {
        // Create User
        $user = $this->createUser();

        // New instance Repository
        $repo = new UserRepository();

        // Get record by id
        $result = $repo->findByID($user->id);

        // Check if it is a valid instance
        $this->assertInstanceOf(User::class, $result);
    }

    /**
     * Create user
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model|\App\Models\User>|\Illuminate\Database\Eloquent\Model
     */
    private function createUser()
    {
        // Create a Role and a Scale
        $role = Role::factory()->create();
        $scale = Scale::factory()->create();

        // Create User
        $user = User::factory()->create([
            'role_id' => $role->id,
            'scale_id' => $scale->id
        ]);

        return $user;
    }
}
