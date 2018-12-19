<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Models\StudyTag;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudiesTagsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class StudiesTagsControllerTest extends BaseTenantTest{

    use RefreshDatabase,CanLogin;

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
    public function can_show_studies_tags()
    {
        $this->loginAsSuperAdmin('api');

        $tag= StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/studies/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /**
     * @test
     */
    public function manager_can_show_studies_tags()
    {
        $this->loginAsCurriculumManager('api');

        $tag= StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/studies/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /**
     * @test
     */
    public function regular_user_cannot_show_studies_tags()
    {
        $this->login('api');

        $tag= StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/studies/tags/' . $tag->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function can_list_studies_tags()
    {
        $this->loginAsSuperAdmin('api');

        $tag1 = StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $tag2 = StudyTag::create([
            'value' => 'tag2',
            'description' => 'description2',
            'color' => '#456545'
        ]);

        $tag3 = StudyTag::create([
            'value' => 'tag3',
            'description' => 'description3',
            'color' => '#453445'
        ]);

        $tags = [ $tag1, $tag2, $tag3];

        $response = $this->json('GET','/api/v1/studies/tags');
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertEquals($tags[0]->id, $result[0]->id);
        $this->assertEquals($tags[0]->value, $result[0]->value);
        $this->assertEquals($tags[0]->description, $result[0]->description);
        $this->assertEquals($tags[1]->color, $result[1]->color);
        $this->assertEquals($tags[1]->id, $result[1]->id);
        $this->assertEquals($tags[1]->value, $result[1]->value);
        $this->assertEquals($tags[1]->description, $result[1]->description);
        $this->assertEquals($tags[1]->color, $result[1]->color);
        $this->assertEquals($tags[2]->color, $result[2]->color);
        $this->assertEquals($tags[2]->id, $result[2]->id);
        $this->assertEquals($tags[2]->value, $result[2]->value);
        $this->assertEquals($tags[2]->description, $result[2]->description);
        $this->assertEquals($tags[2]->color, $result[2]->color);
    }


    /**
     * @test
     */
    public function regular_user_cannot_list_studies_tags()
    {
        $this->login('api');

        $response = $this->json('GET','/api/v1/studies/tags');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function can_store_studies_tag()
    {
        $this->loginAsSuperAdmin('api');

        $response = $this->json('POST','/api/v1/studies/tags',$tag = [
            'value' => 'wontfix',
            'description' => 'No es vol resoldre la incidència',
            'color' => '#342334'
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertNotNull($result->id);
        $this->assertEquals($tag['value'], $result->value);
        $this->assertEquals($tag['description'], $result->description);
        $this->assertEquals($tag['color'], $result->color);
    }

    /**
     * @test
     */
    public function regular_user_cannot_store_studiestag()
    {
        $this->login('api');

        $response = $this->json('POST','/api/v1/studies/tags',$tag = [
            'value' => 'wontfix',
            'description' => 'No es vol resoldre la incidència',
            'color' => '#342334'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function store_studies_tag_validation()
    {
        $this->loginAsSuperAdmin('api');

        $response = $this->json('POST','/api/v1/studies/tags',[]);
        $response->assertStatus(422);
    }

    /** @test */
    public function can_update_studies_tag()
    {
        $this->loginAsSuperAdmin('api');

        $tag= StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578',
            'icon' => 'home'
        ]);

        $response = $this->json('PUT','/api/v1/studies/tags/' . $tag->id, [
            'value' => 'newwontfix',
            'description' => 'description',
            'color' => '#111111'
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $tag = $tag->refresh();
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /** @test */
    public function regular_user_cannot_update_studies_tag()
    {
        $this->login('api');

        $tag= StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('PUT','/api/v1/studies/tags/' . $tag->id, [
            'value' => 'newwontfix',
            'description' => 'description',
            'color' => '#111111'
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function can_destroy_studies_tag()
    {
        $this->loginAsSuperAdmin('api');

        $tag= StudyTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('DELETE','/api/v1/studies/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertNull(StudyTag::find($tag->id));
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }
}
