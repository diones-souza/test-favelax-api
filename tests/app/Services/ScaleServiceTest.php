<?php

namespace Tests\App\Http\Repositories;

use App\Repositories\ScaleRepository;
use App\Services\ScaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=ScaleServiceTest
class ScaleServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
}
