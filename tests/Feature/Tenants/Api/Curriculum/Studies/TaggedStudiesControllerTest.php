<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Events\Studies\StudyTagAdded;
use App\Events\Studies\StudyTagRemoved;
use App\Models\StudyTag;
use App\Models\User;
use Event;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class TaggedStudiesControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class TaggedStudiesControllerTest extends BaseTenantTest{

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
     * @group curriculum
     */
    public function can_add_tag_to_study()
    {
        $this->loginAsSuperAdmin('api');
        $study = create_sample_study();
        $tag = StudyTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $this->assertCount(0,$study->tags);
        Event::fake();
        $response = $this->json('POST','/api/v1/studies/' . $study->id . '/tags/' . $tag-> id);
        $response->assertSuccessful();
        Event::assertDispatched(StudyTagAdded::class,function ($event) use ($study){
            return $event->study->is($study) && $event->tag->value === 'etiqueta 2';
        });
        $study = $study->fresh();
        $this->assertCount(1,$study->tags);
        $this->assertTrue($study->tags[0]->is($tag));
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_add_tag_to_study()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $study = create_sample_study();

        $tag = StudyTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);

        $this->assertCount(0,$study->tags);
        $response = $this->json('POST','/api/v1/studies/' . $study->id . '/tags/' . $tag-> id);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_remove_tag_from_study()
    {
        $this->loginAsSuperAdmin('api');
        $study = create_sample_study();

        $tag = StudyTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $study->addTag($tag);
        $this->assertCount(1,$study->tags);
        Event::fake();
        $response = $this->json('DELETE','/api/v1/studies/' . $study->id . '/tags/' . $tag-> id);
        $response->assertSuccessful();
        Event::assertDispatched(StudyTagRemoved::class,function ($event) use ($study){
            return $event->study->is($study) && $event->oldTag->value === 'etiqueta 2';
        });
        $study = $study->fresh();
        $this->assertCount(0,$study->tags);
    }


    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_remove_tag_from_study()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $study = create_sample_study();

        $tag = StudyTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $study->addTag($tag);
        $this->assertCount(1,$study->tags);
        $response = $this->json('DELETE','/api/v1/studies/' . $study->id . '/tags/' . $tag-> id);
        $response->assertStatus(403);
    }
}
