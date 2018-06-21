<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\AdministrativeStatus;
use App\Models\Employee;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Job;
use App\Models\Location;
use App\Models\Person;
use App\Models\Teacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class MediaControllerTest.
 *
 * @package Tests\Feature
 */
class MediaControllerTest extends BaseTenantTest
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
    public function cand_download_media()
    {
        $this->withoutExceptionHandling();

        // some test function
        Storage::fake('images');
        config()->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => Storage::disk('images')->getAdapter()->getPathPrefix(),
        ]);

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $person = Person::create([
            'givenName' => 'Pepe'
        ]);
        $person->addMedia(UploadedFile::fake()->image('avatar.jpg'))->toMediaCollection('dnis');
        $media = $person->media()->first();
        $response = $this->get('/media/' . $media->id . '/download');

        $response->assertSuccessful();
    }
}
