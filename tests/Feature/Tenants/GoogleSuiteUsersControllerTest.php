<?php

namespace Tests\Feature\Tenants;

use Illuminate\Contracts\Console\Kernel;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleSuiteUsersControllerTest
 * @package Tests\Feature\Tenants
 */
class GoogleSuiteUsersControllerTest extends BaseTenantTest
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
     * @group working
     * @group gsuite
     */
    public function show_google_users()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('GET','/api/v1/gsuite/users');
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group working
     * @group gsuite
     */
    public function user_cannot_show_google_users()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/gsuite/users/');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group working
     * @group gsuite
     */
    public function show_google_user_info()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = factory(User::class)->create([
            'email' => 'sergitur@iesebre.com'
        ]);

        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('GET','/api/v1/gsuite/users/' . $user->email);
        $response->assertSuccessful();


        $response->assertJsonStructure([
            'addresses',
            'agreedToTerms',
            'aliases',
            'changePasswordAtNextLogin',
            'creationTime',
            'customSchemas',
            'customerId',
            'deletionTime',
            'emails' => [],
            'etag',
            'name' => [
                'familyName',
                'fullName',
                'givenName'
            ],
            'orgUnitPath'
        ]);

        $result = json_decode($response->getContent());
//        dump($result);

        $this->assertEquals('sergitur@iesebre.com',$result->emails[0]->address);
        $this->assertTrue($result->emails[0]->primary);

//        {#130
//            +"addresses": null
//        +"agreedToTerms": true
//        +"aliases": null
//        +"changePasswordAtNextLogin": false
//        +"creationTime": "2010-09-15T07:18:30.000Z"
//        +"customSchemas": null
//        +"customerId": "C02a980r0"
//        +"deletionTime": null
//        +"emails": array:2 [
//            0 => {#985
//            +"address": "sergitur@iesebre.com"
//            +"primary": true
//    }
//    1 => {#979
//            +"address": "sergitur@insebre.cat"
//    }
//  ]
//  +"etag": ""LW8ywR8igJCaw6tx8gAGB-fySsA/o8MF9LNLXtKde5U35RwjxrkAxJY""
//        +"externalIds": null
//        +"gender": {#983
//            +"type": "male"
//  }
//  +"hashFunction": null
//        +"id": "105113589570978987751"
//        +"ims": null
//        +"includeInGlobalAddressList": true
//        +"ipWhitelisted": false
//        +"isAdmin": true
//        +"isDelegatedAdmin": false
//        +"isEnforcedIn2Sv": false
//        +"isEnrolledIn2Sv": true
//        +"isMailboxSetup": true
//        +"keywords": null
//        +"kind": "admin#directory#user"
//        +"languages": null
//        +"lastLoginTime": "2018-05-16T11:06:01.000Z"
//        +"locations": null
//        +"nonEditableAliases": array:1 [
//            0 => "sergitur@insebre.cat"
//        ]
//        +"notes": null
//        +"orgUnitPath": "/All/Personal/maninfo/people"
//        +"organizations": null
//        +"password": null
//        +"phones": null
//        +"posixAccounts": null
//        +"primaryEmail": "sergitur@iesebre.com"
//        +"relations": null
//        +"sshPublicKeys": null
//        +"suspended": false
//        +"suspensionReason": null
//        +"thumbnailPhotoEtag": null
//        +"thumbnailPhotoUrl": null
//        +"websites": null
//        +"name": {#982
//            +"familyName": "Tur Badenas"
//            +"fullName": "Sergi Tur Badenas"
//            +"givenName": "Sergi"
//  }
//}
    }

    /** @test */
    public function user_cannot_show_google_user_info()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/gsuite/users/sergitur@iesebre.com');
        $response->assertStatus(403);
    }

    /** @ test */
    public function create_google_user()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $response = $this->json('POST','/api/v1/gsuite/users', [

        ]);
        $response->assertSuccessful();
    }

    /** @ test */
    public function user_cannot_create_google_user()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/gsuite/users', [

        ]);
        $response->assertStatus(403);
    }


    /**
     * @test
     * @group working
     */
    public function remove_google_user()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create([
            'email' => 'testy.testerton@iesebre.com'
        ]);
        $response = $this->json('DELETE','/api/v1/gsuite/users/' . $user->email);
        $response->assertSuccessful();

        // Stop here and mark this test as incomplete.

    }

    /** @test */
    public function user_cannot_remove_google_user()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $otheruser = factory(User::class)->create([
            'email' => 'testy.testerton@iesebre.com'
        ]);
        $response = $this->json('DELETE','/api/v1/gsuite/users/' . $otheruser->email);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group working
     */
    public function unremove_google_user()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create([
            'email' => 'testy.testerton@iesebre.com'
        ]);
        $response = $this->json('POST','/api/v1/gsuite/users/undelete' . $user->email);
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group working
     * @group gsuite
     */
    public function user_cannot_unremove_google_user()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $otheruser = factory(User::class)->create([
            'email' => 'testy.testerton@iesebre.com'
        ]);
        $response = $this->json('POST','/api/v1/gsuite/users/undelete' . $otheruser->email);
        $response->assertStatus(403);
    }

}
