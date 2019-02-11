<?php

namespace Tests\Feature;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class PersonalDataControllerTest.
 *
 * @package Tests\Feature
 */
class PersonalDataControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group people
     */
    public function show_personal_data_management()
    {
        create_sample_people();
        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('/personal_data');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.people.show');

        $response->assertViewHas('people', function ($returnedPeople) {
            dump($returnedPeople['user']);
            dump(count($returnedPeople));
            return
                count($returnedPeople) === 3;
//                $returnedPositions[0]['name'] === 'Coordinador TIC/TAC' &&
//                $returnedPositions[0]['shortname'] === 'Coord. TIC' &&
//                $returnedPositions[0]['api_uri'] === 'positions' &&
//                $returnedPositions[0]['users'] !== null &&
//                $returnedPositions[0]['shortname'] === 'Coord. TIC' &&
//                $returnedPositions[0]['code'] === 'TICTAC';
        });
    }

    /**
     * @test
     * @group people
     */
    public function regular_user_cannot_show_personal_data_management()
    {

        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/personal_data');

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group people
     */
    public function guest_user_cannot_show_personal_data_management()
    {
        $response = $this->get('/personal_data');
        $response->assertRedirect('/login');
    }

}
