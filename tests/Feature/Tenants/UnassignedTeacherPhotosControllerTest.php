<?php

namespace Tests\Feature\Tenants;

use Illuminate\Contracts\Console\Kernel;
use App\Events\TeacherPhotosZipUploaded;
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
 * Class UnassignedTeacherPhotosControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class UnassignedTeacherPhotosControllerTest extends BaseTenantTest
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
    public function manager_can_upload_zip()
    {
        Storage::fake('local');
        Event::fake();

        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photos', [
            'photos' => UploadedFile::fake()->create('photos.zip')
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('photos.zip',$result->filename);
        $this->assertEquals('photoszip',$result->slug);

        Storage::disk('local')->assertExists($result->path);

        $this->assertEquals($result->path,'tenant_test/teacher_photos_zip/photos.zip');

        Event::assertDispatched(TeacherPhotosZipUploaded::class, function ($e) use ($result) {
            return $e->path === $result->path;
        });
    }

    /** @test */
    public function user_cannot_upload_new_unassigned_teacher_photo()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photos', [
            'teacher_photo' => UploadedFile::fake()->create('photos.zip')
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

        $this->assertEquals($result->message,'Les dades proporcionades no són vàlides' );
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

        $this->assertEquals($result->message,'Les dades proporcionades no són vàlides');
        $this->assertEquals($result->errors->teacher_photo[0],"Les dimensions de la imatge teacher photo no són vàlides." );
    }

    /** @test */
    public function can_download_zip()
    {
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager);

        Storage::fake('local');
        $file = UploadedFile::fake()->create('photos 2.zip');
        Storage::disk('local')->putFileAs('tenant_test/teacher_photos_zip', $file, 'photos 2.zip');

        $response = $this->get('/unassigned_teacher_photos/photos-2zip');
        $response->assertSuccessful();
        $baseResponse = $response->baseResponse;
        $this->assertEquals(get_class($baseResponse),'Symfony\Component\HttpFoundation\BinaryFileResponse');
        $file = $response->baseResponse->getFile();
        $this->assertEquals(get_class($file),'Symfony\Component\HttpFoundation\File\File');
        $this->assertEquals($file->getFileName(),'photos 2.zip');

    }

    /** @test */
    public function user_cannot_download_zip()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('/unassigned_teacher_photos/photos-2zip');
        $response->assertStatus(403);
    }

    /** @test */
    public function can_delete_zip()
    {
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager,'api');

        Storage::fake('local');
        $file = UploadedFile::fake()->create('photos 2.zip');
        Storage::disk('local')->putFileAs('tenant_test/teacher_photos_zip', $file, 'photos 2.zip');

        $response = $this->json('DELETE','/api/v1/unassigned_teacher_photos/photos-2zip');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $this->assertEquals('photos 2.zip',$result->filename);
        $this->assertEquals('photos-2zip',$result->slug);
        Storage::disk('local')->assertMissing('tenant_test/teacher_photos_zip/photos 2.zip');
    }

    /** @test */
    public function can_download_all_photos_as_zip()
    {
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager);

        Storage::fake('local');
        $names = ['040 - TUR, Sergi.jpg','041 - Pardo, Jeans.jpg','042 - Parda, Jeans Parda.jpg'];
        foreach ($names as $name) {
            $file = UploadedFile::fake()->image($name);
            Storage::disk('local')->putFileAs('tenant_test/teacher_photos/', $file, $name);
        }

        $response = $this->get('/unassigned_teacher_photos');
        $response->assertSuccessful();
        $baseResponse = $response->baseResponse;
        $this->assertEquals(get_class($baseResponse),'Symfony\Component\HttpFoundation\BinaryFileResponse');
        $file = $response->baseResponse->getFile();
        $this->assertEquals(get_class($file),'Symfony\Component\HttpFoundation\File\File');
        $this->assertTrue(starts_with($file->getFileName(),'teachers_'));
        $this->assertTrue(ends_with($file->getFileName(),'.zip'));
    }

    /** @test */
    public function user_cannot_download_all_photos_as_zip()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('/unassigned_teacher_photos');
        $response->assertStatus(403);
    }

    /** @test */
    public function can_remove_all_photos()
    {
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager,'api');

        Storage::fake('local');
        $names = ['040 - TUR, Sergi.jpg','041 - Pardo, Jeans.jpg','042 - Parda, Jeans Parda.jpg'];
        foreach ($names as $name) {
            $file = UploadedFile::fake()->image($name);
            Storage::disk('local')->putFileAs('tenant_test/teacher_photos', $file, $name);
        }

        $response = $this->json('DELETE','/api/v1/unassigned_teacher_photos');
        $response->assertSuccessful();

        foreach ($names as $name) {
            Storage::disk('local')->assertMissing('tenant_test/teacher_photos/' . $name);
        }
    }

    /** @test */
    public function user_cannot_remove_all_photos()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('DELETE','/api/v1/unassigned_teacher_photos');
        $response->assertStatus(403);
    }
}
