<?php

namespace Tests\App\Http\Repositories;

use App\Models\Point;
use App\Models\Role;
use App\Models\Scale;
use App\Models\User;
use App\Repositories\PointRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=PointRepositoryTest
class PointRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=PointServiceTest::testExtendsClassService
     *
     * Checks if the service class is behaving as expected
     *
     * @return void
     */
    public function testExtendsClassService()
    {
        // New instance Repository
        $repo = new PointRepository();

        // Assert that the 'findByID' exists in the PointRepository.
        // This test ensures that the desired function is implemented and available for use.
        $this->assertTrue(method_exists($repo, 'findByID'));
    }

    /**
     * docker-compose exec app php artisan test --filter=PointRepositoryTest::testInstanceOfFindByID
     *
     * Checks if the repository class is behaving as expected
     *
     * Verifies if the return is a valid model
     *
     * @return void
     */
    public function testInstanceOfFindByID()
    {
        // Create Point
        $point = $this->createPoint();

        // New instance Repository
        $repo = new PointRepository();

        // Get record by id
        $result = $repo->findByID($point->id);

        // Check if it is a valid instance
        $this->assertInstanceOf(Point::class, $result);
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
