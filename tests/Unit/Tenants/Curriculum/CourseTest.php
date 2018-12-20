<?php

namespace Tests\Unit\Tenants\Curriculum;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CourseTest.
 *
 * @package Tests\Unit
 */
class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate',[
            '--path' => 'database/migrations/tenant'
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * @test
     * @group curriculum
     */
    public function map()
    {
        $course = create_sample_course();

        $mappedCourse = $course->map();

        $this->assertSame(1,$mappedCourse['id']);
        $this->assertEquals('Segon Curs Desenvolupament Aplicacions Multiplataforma',$mappedCourse['name']);
        $this->assertEquals('2DAM',$mappedCourse['code']);
        $this->assertSame(2,$mappedCourse['order']);

        $this->assertNotNull($mappedCourse['created_at']);
        $this->assertNotNull($mappedCourse['updated_at']);
        $this->assertNotNull($mappedCourse['created_at_timestamp']);
        $this->assertNotNull($mappedCourse['updated_at_timestamp']);
        $this->assertNotNull($mappedCourse['formatted_created_at']);
        $this->assertNotNull($mappedCourse['formatted_updated_at']);
        $this->assertNotNull($mappedCourse['formatted_created_at_diff']);
        $this->assertNotNull($mappedCourse['formatted_updated_at_diff']);
    }
}
