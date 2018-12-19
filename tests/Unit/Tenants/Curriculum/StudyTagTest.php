<?php

namespace Tests\Unit\Tenants;

use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class StudyTagTest.
 *
 * @package Tests\Unit
 */
class StudyTagTest extends TestCase
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
        $tag= StudyTag::create([
            'value' => 'LOE',
            'description' => 'Lley Organica Educació',
            'color' => '#456578',
            'icon' => 'label'
        ]);

        $mappedTag = $tag->map();
        $this->assertEquals(1,$mappedTag['id']);
        $this->assertEquals('LOE',$mappedTag['value']);
        $this->assertEquals('Lley Organica Educació',$mappedTag['description']);
        $this->assertEquals('#456578',$mappedTag['color']);
        $this->assertEquals('label',$mappedTag['icon']);
        $this->assertNotNull($mappedTag['created_at']);
        $this->assertNotNull($mappedTag['updated_at']);
        $this->assertNotNull($mappedTag['created_at_timestamp']);
        $this->assertNotNull($mappedTag['updated_at_timestamp']);
        $this->assertNotNull($mappedTag['formatted_created_at']);
        $this->assertNotNull($mappedTag['formatted_updated_at']);
        $this->assertNotNull($mappedTag['formatted_created_at_diff']);
        $this->assertNotNull($mappedTag['formatted_updated_at_diff']);
        $this->assertEquals('study_tags',$mappedTag['api_uri']);

    }
}
