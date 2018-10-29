<?php

namespace Tests\Feature\Tenants;

use App;
use Illuminate\Contracts\Console\Kernel;
use App\Events\UnassignedTeacherPhotoUploaded;
use App\Models\User;
use Config;
use Event;
use File;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UnassignedTeacherPhotoControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class UnassignedTeacherPhotoControllerTest extends BaseTenantTest
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
    public function manager_can_see_list_of_unassigned_teacher_photos()
    {
        Storage::fake('local');

        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[1]->getBasename(),
            $files[1]->getContents()
        );

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[2]->getBasename(),
            $files[2]->getContents()
        );

        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('GET','/api/v1/unassigned_teacher_photo');
        $response->assertSuccessful();

        $response->assertJsonStructure([[
           'filename',
            'slug'
        ]]);
    }

    /** @test */
    public function user_cannot_see_list_of_unassigned_teacher_photos()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $response = $this->json('GET','/api/v1/unassigned_teacher_photo');
        $response->assertStatus(403);
    }

    /** @test */
    public function manager_can_upload_new_unassigned_teacher_photo()
    {
        Storage::fake('local');
        Event::fake();

        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photo', [
            'teacher_photo' => UploadedFile::fake()->image('040 Sergi Tur.jpg', 670, 790)
        ]);

        $response->assertSuccessful();
        $response = json_decode($response->getContent());
        Storage::disk('local')->assertExists($path = $response->path);
        $this->assertEquals($response->slug,'040-sergi-turjpg');
        $this->assertEquals($response->filename,'040 Sergi Tur.jpg');
        Event::assertDispatched(UnassignedTeacherPhotoUploaded::class, function ($e) use ($path) {
            return $e->path === $path;
        });
    }

    /** @test */
    public function user_cannot_upload_new_unassigned_teacher_photo()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photo', [
            'teacher_photo' => UploadedFile::fake()->create('photo.jpg')
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function upload_new_unassigned_teacher_photo_validation()
    {
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photo');

        $response->assertStatus(422);
        $result = json_decode($response->getContent());
        $this->assertEquals($result->message,'Les dades proporcionades no són vàlides');
        $this->assertEquals($result->errors->teacher_photo[0],"El camp teacher photo és obligatori." );

        $response = $this->json('POST','/api/v1/unassigned_teacher_photo',[
            'teacher_photo' => 'dsasd'
        ]);

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,'Les dades proporcionades no són vàlides' );
        $this->assertEquals($result->errors->teacher_photo[0],"teacher photo ha de ser una imatge." );

        $response = $this->json('POST','/api/v1/unassigned_teacher_photo',[
            'teacher_photo' => UploadedFile::fake()->create('prova.pdf')
        ]);

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,'Les dades proporcionades no són vàlides' );
        $this->assertEquals($result->errors->teacher_photo[0],"teacher photo ha de ser una imatge." );

        $response = $this->json('POST','/api/v1/unassigned_teacher_photo',[
            'teacher_photo' => UploadedFile::fake()->image('photo.jpg')
        ]);

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,'Les dades proporcionades no són vàlides' );
        $this->assertEquals($result->errors->teacher_photo[0],"Les dimensions de la imatge teacher photo no són vàlides." );
    }

    /** @test */
    public function manager_can_delete_unassigned_teacher_photos()
    {
        Storage::fake('local');

        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );

        $response = $this->json('DELETE','/api/v1/unassigned_teacher_photo/' . str_slug($files[0]->getBasename()));

        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertFalse(Storage::disk('local')->exists('teacher_photos/' .  $result->filename));

        $this->assertEquals($result->filename,$files[0]->getBasename());
        $this->assertEquals($result->slug,str_slug($files[0]->getBasename()));

    }

    /** @test */
    public function user_cannot_delete_unassigned_teacher_photos()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('DELETE','/api/v1/unassigned_teacher_photo/01-sergiturjpg');
        $response->assertStatus(403);
    }

}
