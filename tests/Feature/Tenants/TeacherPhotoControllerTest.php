<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Testing\File;
use File as FileFacade;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherPhotoControllerTest.
 *
 * @package Tests\Feature
 */
class TeacherPhotoControllerTest extends BaseTenantTest
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
    public function manager_teacher_photos_see_404_for_non_existing_photo()
    {
        $photoTeachersManager = create(User::class);
        $this->actingAs($photoTeachersManager);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);

        $photo_slug = '040_sergi_tur_badenas';
        $response = $this->json('GET','/teacher_photo/' . $photo_slug);

        $response->assertStatus(404);

    }

    /** @test */
    public function manager_teacher_photos_can_store_a_teacher_photo()
    {
        Storage::fake('local');

        $photoTeachersManager = create(User::class);
        $user = create(User::class);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager);

        $response = $this->json('POST','/teacher_photo/', [
            'photo' => File::image('teacher_photo.jpg'),
            'user_id' => $user->id
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        Storage::disk('local')->assertExists($result->photo);
        $user = $user->fresh();
        $this->assertEquals($result->photo,$user->photo);

    }

    /** @test */
    public function regular_user_cannot_store_a_teacher_photo()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->json('POST','/teacher_photo/', [
            'photo' => File::image('teacher_photo.jpg'),
            'user_id' => 1
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function can_download_photo()
    {
        $photoTeachersManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager);

        Storage::fake('local');
        $file = UploadedFile::fake()->create('040 - TUR, Sergi.jpg');
        Storage::disk('local')->putFileAs('tenant_test/teacher_photos', $file, '040 - TUR, Sergi.jpg');

        $response = $this->get('/teacher_photo/' . str_slug($file->name,'-') . '/download');

        $response->assertSuccessful();
        $baseResponse = $response->baseResponse;
        $this->assertEquals(get_class($baseResponse),'Symfony\Component\HttpFoundation\BinaryFileResponse');
        $file = $response->baseResponse->getFile();
        $this->assertEquals(get_class($file),'Symfony\Component\HttpFoundation\File\File');
        $this->assertEquals($file->getFileName(),'040 - TUR, Sergi.jpg');

    }

    /** @test */
    public function regular_user_cannot_download_photo()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/teacher_photo/sergitur-png');

        $response->assertStatus(403);
    }

    /** @test */
    public function can_change_photo_filename()
    {
        Storage::fake('local');

        $photoTeachersManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager,'api');
        $file = UploadedFile::fake()->create('040 - TUR, Sergi.jpg');
        Storage::disk('local')->putFileAs('tenant_test/teacher_photos', $file, '040 - TUR, Sergi.jpg');

        $response = $this->json('PUT','api/v1/teacher_photo/' . str_slug($file->name,'-'), [
            'filename' => '040 - TUR Badenas, Sergi.jpg'
        ] );

        $response->assertSuccessful();

        $this->assertEquals('040-tur-badenas-sergijpg',$response->getContent());

        Storage::disk('local')->assertExists('tenant_test/teacher_photos/040 - TUR Badenas, Sergi.jpg');

        // Assert a file does not exist...
        Storage::disk('local')->assertMissing('tenant_test/teacher_photos/040 - TUR, Sergi.jpg');
        
    }

    /** @test */
    public function user_cannot_change_photo_filename()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('PUT','api/v1/teacher_photo/040-tur-sergijpg', [
            'filename' => '040 - TUR Badenas, Sergi.jpg'
        ] );

        $response->assertStatus(403);
    }

}

