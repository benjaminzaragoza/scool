<?php

namespace Tests\Feature\Tenants;

use App\Events\TeacherPhotoAssigned;
use App\Events\TeacherPhotoUnassigned;
use App\Models\Teacher;
use App\Models\User;
use Config;
use Event;
use File;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AssignedTeacherPhotoControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class AssignedTeacherPhotoControllerTest extends BaseTenantTest
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
    public function store_teacher_photo_from_available_teacher_photos()
    {
        Storage::fake('local');
        Event::fake();

        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $response = $this->json('POST','/api/v1/teacher/' . $user->id . '/photo', [
            'photo' => str_slug($files[0]->getBasename())
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'photo' => 'tenant_test/user_photos/2_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg',
            'photo_hash' => '953e72e084ecbe0e952150af27f71768'
        ]);
        Storage::exists($result->photo);
        Event::assertDispatched(TeacherPhotoAssigned::class, function ($e) use ($user) {
            return $e->user->is($user) && $e->photo === 'tenant_test/user_photos/2_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg';
        });
    }

    /** @test */
    public function store_teacher_photo_from_available_teacher_photos_validation()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $response = $this->json('POST','/api/v1/teacher/' . $user->id . '/photo');
        $response->assertStatus(422);
    }

    /** @test */
    public function user_cannot_store_teacher_photo_from_available_teacher_photos()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/teacher/1/photo');
        $response->assertStatus(403);
    }

    /** @test */
    public function unassign_photo_to_teacher()
    {
        Storage::fake('local');
        Event::fake();

        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $teacher = Teacher::create([
            'code' => '040'
        ]);
        $teacher->user_id = $user->id;
        $teacher->save();

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/sergi.jpg',
            File::get(base_path('tests/__Fixtures__/photos/users/sergi.jpg'))
        );

        $user->assignPhoto('tenant_test/teacher_photos/sergi.jpg','tenant_test');
        $this->assertEquals('tenant_test/user_photos/2_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg',$user->photo);
        $response = $this->json('DELETE','/api/v1/teacher/' . $user->id . '/photo');
        $response->assertSuccessful();

        $user = $user->fresh();
        $this->assertEquals('',$user->photo);
        $this->assertFalse(Storage::exists($user->photo));

        $this->assertTrue(Storage::exists('tenant_test/teacher_photos/040_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg'));
        Event::assertDispatched(TeacherPhotoUnassigned::class, function ($e) use ($user) {
            return $e->user->is($user) && $e->photo === 'tenant_test/teacher_photos/040_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg';
        });
    }

    /** @test */
    public function regular_user_cannot_unassign_photo_to_teacher()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $user1 = factory(User::class)->create();
        $response = $this->json('DELETE','/api/v1/teacher/' . $user1->id . '/photo');
        $response->assertStatus(403);
    }

    /** @test */
    public function assign_all_available_photos_to_teachers()
    {
        Storage::fake('local');

        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_departments();
        initialize_specialities();
        initialize_users();
        initialize_teachers();


        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

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

        $response = $this->json('POST','/api/v1/teachers/photos');

        $this->assertEquals(3,$response->getContent());

        $response->assertSuccessful();

        $teacher1 = Teacher::where('code','040')->get()->first();
        $teacher2 = Teacher::where('code','041')->get()->first();
        $teacher3 = Teacher::where('code','042')->get()->first();

        $this->assertEquals('tenant_test/user_photos/' . $teacher1->user->id . '_sergi-tur-badenas_stur-at-iesebrecom.jpg', $teacher1->user->photo);
        $this->assertEquals('tenant_test/user_photos/' . $teacher2->user->id . '_jaume-ramos-prades_jaumeramos-at-iesebrecom.jpg', $teacher2->user->photo);
        $this->assertEquals('tenant_test/user_photos/' . $teacher3->user->id . '_mireia-consarnau-pallares_mireiaconsarnau-at-iesebrecom.jpg', $teacher3->user->photo);

        Storage::exists($teacher1->user->photo);
        Storage::exists($teacher2->user->photo);
        Storage::exists($teacher3->user->photo);

    }

    /** @test */
    public function regular_user_cannot_assign_all_available_photos_to_teachers()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/teachers/photos');
        $response->assertStatus(403);

    }
}
