<?php

namespace Tests\App\Http\Repositories;

use App\Models\Scale;
use App\Repositories\ScaleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=ScaleRepositoryTest
class ScaleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=ScaleServiceTest::testExtendsClassService
     *
     * Checks if the service class is behaving as expected
     *
     * @return void
     */
    public function testExtendsClassService()
    {
        // New instance Repository
        $repo = new ScaleRepository();

        // Assert that the 'findByID' exists in the ScaleRepository.
        // This test ensures that the desired function is implemented and available for use.
        $this->assertTrue(method_exists($repo, 'findByID'));
    }

    /**
     * docker-compose exec app php artisan test --filter=ScaleRepositoryTest::testInstanceOfFindByID
     *
     * Checks if the repository class is behaving as expected
     *
     * Verifies if the return is a valid model
     *
     * @return void
     */
    public function testInstanceOfFindByID()
    {
        // Create Scale
        $scale = Scale::factory()->create();

        // New instance Repository
        $repo = new ScaleRepository();

        // Get record by id
        $result = $repo->findByID($scale->id);

        // Check if it is a valid instance
        $this->assertInstanceOf(Scale::class, $result);
    }
}
