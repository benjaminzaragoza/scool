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
     * findByEmail
     *
     * @test
     * @group slow
     * @group ldap
     */
    public function findByEmail()
    {
        $result = LdapUser::findByEmail('nonexistingemail@impossible.com');
        $this->assertNull($result);

        $result = LdapUser::findByEmail('stur@iesebre.com');
        $this->assertEquals('stur@iesebre.com',$result->email[0]);
    }

    /**
     * findByEmail
     *
     * @test
     * @group slow
     * @group ldap
     */
    public function findByUid()
    {
        $result = LdapUser::findByUid('nonexistinguid');
        $this->assertNull($result);

        $result = LdapUser::findByUid('stur');
        $this->assertEquals('stur',$result->uid[0]);
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
        $this->assertEquals('ou=All,dc=iesebre,dc=com',$users[0]->base_dn);
        $this->assertEquals('uid=root',$users[0]->rdn);
        $this->assertEquals('root',$users[0]->cn);
        $this->assertEquals('uid=root,ou=All,dc=iesebre,dc=com',$users[0]->dn);
        $this->assertEquals('root',$users[0]->uid);
        $this->assertTrue(starts_with($users[0]->userpassword,'{SSHA}'));
        $this->assertEquals(0,$users[0]->uidnumber);
        $this->assertEquals(0,$users[0]->gidnumber);
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
        $this->assertTrue(property_exists($users[0],'jpegphoto'));
    }

    /**
     * @test
     * @group slow
     * @group ldap
     */
    public function adapt()
    {
        $originalUser = LdapUser::findByUid('stur');
        $this->assertEquals('stur',$originalUser->uid[0]);
        $user = LdapUser::adapt($originalUser);

//        dd($user->objectClass);

        $this->assertTrue(is_array($user->objectClass));
        $this->assertCount(12, $user->objectClass);
        $this->assertEquals('top',$user->objectClass[0]);
        $this->assertEquals('person',$user->objectClass[1]);
        $this->assertEquals('organizationalPerson',$user->objectClass[2]);
        $this->assertEquals('inetOrgPerson',$user->objectClass[3]);
        $this->assertEquals('gosaAccount',$user->objectClass[4]);
        $this->assertEquals('extensibleObject',$user->objectClass[5]);
        $this->assertEquals('irisPerson',$user->objectClass[6]);
        $this->assertEquals('posixAccount',$user->objectClass[7]);
        $this->assertEquals('shadowAccount',$user->objectClass[8]);
        $this->assertEquals('gotoEnvironment',$user->objectClass[9]);
        $this->assertEquals('sambaSamAccount',$user->objectClass[10]);
        $this->assertEquals('highSchoolUser',$user->objectClass[11]);

        $this->assertEquals('ou=All,dc=iesebre,dc=com',$user->base_dn);
        $this->assertEquals('cn=Sergi Tur Badenas,ou=people,ou=Informatica,ou=Profes',$user->rdn);
        $this->assertEquals('Sergi Tur Badenas',$user->cn);
        $this->assertEquals('cn=Sergi Tur Badenas,ou=people,ou=Informatica,ou=Profes,ou=All,dc=iesebre,dc=com',$user->dn);
        $this->assertEquals('stur',$user->uid);
        $this->assertTrue(starts_with($user->userpassword,'{MD5}'));
        $this->assertEquals(2374,$user->uidnumber);
        $this->assertEquals(513,$user->gidnumber);
        $this->assertEquals('/samba/homes/stur',$user->homedirectory);
        $this->assertEquals('S-1-5-21-4045161930-1404234508-1517741366-7090',$user->sambasid);
        $this->assertEquals('7090',$user->sambarid);

        $this->assertEquals('Sergi',$user->givenname);
        $this->assertEquals('Tur Badenas',$user->sn);
        $this->assertEquals('Tur',$user->sn1);
        $this->assertEquals('Badenas',$user->sn2);

        $this->assertEquals('profe',$user->employeetype);
        $this->assertEquals(41,$user->employeenumber);

        $this->assertEquals('201112-1005',$user->highschooluserid);
        $this->assertEquals('acacha@gmail.com',$user->highschoolpersonalemail);
        $this->assertEquals('stur@iesebre.com',$user->email);


        $this->assertEquals('20130929223451Z',$user->modifyTimestamp);
        $this->assertEquals('22:34:51 29-09-2013',$user->modifyFormatted);
        $this->assertTrue(property_exists($user,'modifyHuman'));

        $this->assertEquals('20110907064243Z',$user->createTimestamp);
        $this->assertEquals('06:42:43 07-09-2011',$user->createFormatted);
        $this->assertTrue(property_exists($user,'createHuman'));

        $this->assertEquals('cn=admin,dc=iesebre,dc=com',$user->creatorsName);
        $this->assertEquals('cn=admin',$user->creatorsNameRDN);

        $this->assertEquals('cn=admin,dc=iesebre,dc=com',$user->modifiersName);
        $this->assertEquals('cn=admin',$user->modifiersNameRDN);

//        dd($user->creatorsName);

        $this->assertTrue(property_exists($user,'irispersonaluniqueid'));
        $this->assertTrue(property_exists($user,'l'));
        $this->assertTrue(property_exists($user,'st'));
        $this->assertTrue(property_exists($user,'telephonenumber'));
        $this->assertTrue(property_exists($user,'mobile'));
        $this->assertTrue(property_exists($user,'postalCode'));
        $this->assertTrue(property_exists($user,'createtimestamp'));

        $this->assertTrue(property_exists($user,'creatorsName'));

        $this->assertTrue(property_exists($user,'modifiersName'));
        $this->assertTrue(property_exists($user,'modifyTimestamp'));
        $this->assertTrue(property_exists($user,'jpegphoto'));
    }

}
