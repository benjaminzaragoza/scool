<?php

namespace Tests\Feature\Tenants\Api\Curriculum\SubjectGroups;

use App\Models\SubjectGroupTag;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectGroupsTagsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class SubjectGroupsTagsControllerTest extends BaseTenantTest{

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
     * @group curriculum
     */
    public function can_show_subject_groups_tags()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $tag= SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/subjectGroups/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_show_subjectGroups_tags()
    {
        $this->loginAsCurriculumManager('api');

        $tag= SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/subjectGroups/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_show_subjectGroups_tags()
    {
        $this->login('api');

        $tag= SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/subjectGroups/tags/' . $tag->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_list_subjectGroups_tags()
    {
        $this->loginAsSuperAdmin('api');

        $tag1 = SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578'
        ]);

        $tag2 = SubjectGroupTag::create([
            'value' => 'tag2',
            'description' => 'description2',
            'color' => '#456545'
        ]);

        $tag3 = SubjectGroupTag::create([
            'value' => 'tag3',
            'description' => 'description3',
            'color' => '#453445'
        ]);

        $tags = [ $tag1, $tag2, $tag3];

        $response = $this->json('GET','/api/v1/subjectGroups/tags');
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
     * @group curriculum
     */
    public function regular_user_cannot_list_subjectGroups_tags()
    {
        $this->login('api');

        $response = $this->json('GET','/api/v1/subjectGroups/tags');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjectGroups_tag()
    {
        $this->loginAsSuperAdmin('api');

        $response = $this->json('POST','/api/v1/subjectGroups/tags',$tag = [
            'value' => 'Normal',
            'description' => 'Normal',
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
     * @group curriculum
     */
    public function regular_user_cannot_store_subjectGroupstag()
    {
        $this->login('api');

        $response = $this->json('POST','/api/v1/subjectGroups/tags',$tag = [
            'value' => 'normal',
            'description' => 'No es vol resoldre la incidència',
            'color' => '#342334'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function store_subjectGroups_tag_validation()
    {
        $this->loginAsSuperAdmin('api');

        $response = $this->json('POST','/api/v1/subjectGroups/tags',[]);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_update_subjectGroups_tag()
    {
        $this->loginAsSuperAdmin('api');

        $tag= SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578',
            'icon' => 'home'
        ]);

        $response = $this->json('PUT','/api/v1/subjectGroups/tags/' . $tag->id, [
            'value' => 'newnormal',
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

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_subjectGroups_tag()
    {
        $this->login('api');

        $tag= SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578'
        ]);

        $response = $this->json('PUT','/api/v1/subjectGroups/tags/' . $tag->id, [
            'value' => 'newnormal',
            'description' => 'description',
            'color' => '#111111'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_destroy_subjectGroups_tag()
    {
        $this->loginAsSuperAdmin('api');

        $tag= SubjectGroupTag::create([
            'value' => 'normal',
            'description' => 'normal',
            'color' => '#456578'
        ]);

        $response = $this->json('DELETE','/api/v1/subjectGroups/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertNull(SubjectGroupTag::find($tag->id));
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }
}
