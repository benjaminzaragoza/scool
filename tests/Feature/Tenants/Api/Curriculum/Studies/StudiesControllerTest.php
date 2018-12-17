<?php

namespace Tests\Feature\Api\Studies\Curriculum;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CurriculumControllerTest.
 *
 * @package Tests\Feature
 */
class StudiesControllerTest extends BaseTenantTest
{
    use RefreshDatabase, CanLogin;

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
     */
    public function can_list_studies()
    {
        create_sample_studies();
        $this->loginAsSuperAdmin('api');

        $response =  $this->json('GET','/api/v1/studies');
        $response->assertSuccessful();
        $studies = json_decode($response->getContent());
        $this->assertCount(4,$studies);
        $this->assertSame(1,$studies[0]->id);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$studies[0]->name);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('DAM',$studies[0]->code);
        $this->assertNotNull($studies[0]->created_at);
        $this->assertNotNull($studies[0]->created_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_created_at);
        $this->assertNotNull($studies[0]->formatted_created_at_diff);
        $this->assertNotNull($studies[0]->updated_at);
        $this->assertNotNull($studies[0]->updated_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_updated_at);
        $this->assertNotNull($studies[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     */
    public function curriculum_manager_can_list_studies()
    {
        create_sample_studies();
        $this->loginAsCurriculumManager('api');

        $response =  $this->json('GET','/api/v1/studies');
        $response->assertSuccessful();
        $studies = json_decode($response->getContent());
        $this->assertCount(4,$studies);
        $this->assertSame(1,$studies[0]->id);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$studies[0]->name);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('DAM',$studies[0]->code);
        $this->assertNotNull($studies[0]->created_at);
        $this->assertNotNull($studies[0]->created_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_created_at);
        $this->assertNotNull($studies[0]->formatted_created_at_diff);
        $this->assertNotNull($studies[0]->updated_at);
        $this->assertNotNull($studies[0]->updated_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_updated_at);
        $this->assertNotNull($studies[0]->formatted_updated_at_diff);
    }

    /** @test */
    public function regular_user_cannot_list_studies()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/studies');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_list_studies()
    {
        $response = $this->json('GET','/api/v1/studies');
        $response->assertStatus(401);
    }

}
