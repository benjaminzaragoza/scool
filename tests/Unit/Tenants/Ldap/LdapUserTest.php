<?php

namespace Tests\Unit\Tenants;

use App\Models\Address;
use App\Models\Identifier;
use App\Models\LdapUser;

use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class LdapUserTest.
 *
 * @package Tests\Unit
 */
class LdapUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up.
     */
    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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
     * @group slow
     * @group ldap
     */
    public function createUser()
    {
        $user = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
        ])->assignFullName([
                'givenName' => 'Pepe',
                'sn1' => 'Pardo',
                'sn2' => 'Jeans',
            ]);
        $user->assignPersonalData([
        'identifier_id' => Identifier::firstOrCreate([
            'value' => '87300779R',
            'type_id' => 1
        ])->id,
        'birthdate' => Carbon::createFromFormat('Y-m-d','1978-03-02'),
        'birthplace_id' => 1,
        'gender' => 'Home',
        'civil_status' => 'Casat/da',
        'notes' => "Possiblement el telèfon fix no és correcte",
        'mobile' => '6795789437',
        'other_mobiles' => '68875544,66655544',
        'email' => 'pepepardo@jeans.com',
        'other_emails' => 'prova@gmail.com,hey@asdads.com',
        'phone' => '977445689',
        'other_phones' => '977445690,977445691'
    ])->assignAddress(Address::create([
        'name' => 'C/ Alcanyiz',
        'number' => '11',
        'floor' => '2',
        'floor_number' => '2',
        'location_id' => 2,
        'province_id' => 3,
    ]));
        $result = LdapUser::createUser($user);
        dd($result);

    }

    /**
     * @test
     * @group slow
     * @group ldap
     */
    public function getLdapUsers()
    {
        $users = LdapUser::getLdapUsers(10);
        $this->assertInstanceOf(Collection::class,$users);

//        dd($users[3]);
//        dd($users[0]->cn);
        $this->assertEquals('root',$users[0]->cn);
        $this->assertEquals('uid=root,ou=All,dc=iesebre,dc=com',$users[0]->dn);
        $this->assertEquals('root',$users[0]->uid);
        $this->assertTrue(starts_with($users[0]->userpassword,'{SSHA}'));
        $this->assertEquals(0,$users[0]->uidnumber);
        $this->assertTrue(property_exists($users[0],'givenname'));
        $this->assertTrue(property_exists($users[0],'sn'));
        $this->assertTrue(property_exists($users[0],'sn1'));
        $this->assertTrue(property_exists($users[0],'sn2'));
        $this->assertTrue(property_exists($users[0],'irispersonaluniqueid'));
        $this->assertTrue(property_exists($users[0],'employeetype'));
        $this->assertTrue(property_exists($users[0],'l'));
        $this->assertTrue(property_exists($users[0],'st'));
        $this->assertTrue(property_exists($users[0],'telephonenumber'));
        $this->assertTrue(property_exists($users[0],'mobile'));
        $this->assertTrue(property_exists($users[0],'postalCode'));
        $this->assertTrue(property_exists($users[0],'createtimestamp'));
        $this->assertTrue(property_exists($users[0],'creatorsName'));
        $this->assertTrue(property_exists($users[0],'modifiersName'));
        $this->assertTrue(property_exists($users[0],'modifyTimestamp'));
    }

}
