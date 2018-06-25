<?php

namespace Tests\Feature\Tenants;

use App\Events\TeacherPhotosUploaded;
use App\Models\User;
use Config;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Testing\File;
use File as FileFacade;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeachersPhotosControllerTest.
 *
 * @package Tests\Feature
 */
class TeachersPhotosControllerTest extends BaseTenantTest
{
    use RefreshDatabase;

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

    /** @test */
    public function regular_user_cannot_see_teacher_photos_management()
    {
        $user = create(User::class);
        $this->actingAs($user);
        $response = $this->get('/teachers_photos');

        $response->assertStatus(403);
    }

    /** @test */
    public function manager_teacher_photos_can_see_teacher_photos_management()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_departments();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_teachers();

        $photoTeachersManager = create(User::class);
        $this->actingAs($photoTeachersManager);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);

        $response = $this->get('/teachers_photos');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.teachers.photos.show');
        $response->assertViewHas('photos');
        $response->assertViewHas('zips');
        $response->assertViewHas('teachers');

    }

    /** @test */
    public function manager_teacher_photos_can_see_a_teacher_photo()
    {
        Storage::fake('local');
        $files = FileFacade::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );

        $photoTeachersManager = create(User::class);
        $this->actingAs($photoTeachersManager);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);

        $photo_slug = '040-tur-sergijpg';
        $response = $this->json('GET','/teacher_photo/' . $photo_slug);

        $response->assertSuccessful();

    }

    /** @test */
    function regular_user_cannot_upload_teachers_photos()
    {
        $response = $this->actingAs(factory(User::class)->create(), 'api')
            ->json('POST','/api/v1/teachers_photos', [
                'photos' => File::create('photos.zip'),
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    function teacher_photos_validate_zip()
    {
        $file = File::create('not-a-zip.pdf');
        Storage::fake('local');
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/teachers_photos', [
            'teacher_photos' => $file
        ]);

        $response->assertStatus(422);
        $result =json_decode($response->getContent());
        $this->assertEquals($result->message,'The given data was invalid.');
        $this->assertEquals($result->errors->teacher_photos[0],'teacher photos ha de ser un arxiu amb format: zip.');
    }

    /** @test */
    function teacher_photos_validation()
    {
        Storage::fake('local');
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/teachers_photos');

        $response->assertStatus(422);
        $result =json_decode($response->getContent());
        $this->assertEquals($result->message,'The given data was invalid.');
        $this->assertEquals($result->errors->teacher_photos[0],'El camp teacher photos és obligatori.');
    }

    /** @test */
    function teacher_photos_are_uploaded()
    {
        Storage::fake('local');
        Event::fake();

        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/teachers_photos', [
            'teacher_photos' => UploadedFile::fake()->create('photos.zip')
        ]);

        $response->assertSuccessful();
        $path = $response->getContent();

        Storage::disk('local')->assertExists($path);
        Event::assertDispatched(TeacherPhotosUploaded::class, function ($e) use ($path) {
            return $e->path === $path;
        });

    }
}
