<?php

namespace Tests\Feature\Tenants\Api\Settings;

use App\Models\Setting;
use App\Models\User;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class FilteredSettingsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class FilteredSettingsControllerTest extends BaseTenantTest{

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
    public function can_list_settings_for_an_specific_module()
    {
        create_setting('incidents_setting1','VALUE1','ROLE1');
        create_setting('incidents_setting2','VALUE2','ROLE2');
        create_setting('incidents_setting3','VALUE3','ROLE3');
        create_setting('anothermodule_setting1','VALUE1','ROLE1');

        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $response = $this->json('GET','/api/v1/settings/filter/incidents');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);
        $this->assertEquals('incidents_setting1',$result[0]->key);
        $this->assertEquals('VALUE1',$result[0]->value);
        $this->assertEquals('ROLE1',$result[0]->role);
        $this->assertEquals('incidents_setting2',$result[1]->key);
        $this->assertEquals('VALUE2',$result[1]->value);
        $this->assertEquals('ROLE2',$result[1]->role);
        $this->assertEquals('incidents_setting3',$result[2]->key);
        $this->assertEquals('VALUE3',$result[2]->value);
        $this->assertEquals('ROLE3',$result[2]->role);
    }

    /** @test */
    public function can_update_all_module_settings()
    {
        $this->withoutExceptionHandling();
        create_setting('incidents_setting1','VALUE1','ROLE1');
        create_setting('incidents_setting2','VALUE2','ROLE2');
        create_setting('incidents_setting3','VALUE3','ROLE3');

        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $response = $this->json('PUT','/api/v1/settings/filter/incidents',[
            'settings' => [
                [
                    'id' => 1,
                    'key' => 'incidents_setting1',
                    'value' => 'NEWVALUE1',
                ],
                [
                    'id' => 2,
                    'key' => 'incidents_setting2',
                    'value' => 'NEWVALUE2',
                ],
                [
                    'id' => 3,
                    'key' => 'incidents_setting3',
                    'value' => 'NEWVALUE3',
                ]
            ]
        ]);

        $response->assertSuccessful();

        $this->assertEquals(Setting::get('incidents_setting1'),'NEWVALUE1');
        $this->assertEquals(Setting::get('incidents_setting2'),'NEWVALUE2');
        $this->assertEquals(Setting::get('incidents_setting3'),'NEWVALUE3');
    }

}
