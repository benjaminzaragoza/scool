<?php

namespace Tests\Unit\Tenants;

use App\Models\GoogleUser;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class PersonTest.
 *
 * @package Tests\Unit
 */
class PersonTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function fullname_accessor()
    {
        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $this->assertEquals($person->fullname,'Pardo Jeans, Pepe');

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
        ]);

        $this->assertEquals($person->fullname,'Pardo, Pepe');
    }

    /** @test */
    public function name_accessor()
    {
        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $this->assertEquals($person->name,'Pepe Pardo Jeans');

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo'
        ]);

        $this->assertEquals($person->name,'Pepe Pardo');
    }

    /** @test */
    public function find_by_identifier()
    {
        $dni = IdentifierType::create([
            'name' => 'NIF'
        ]);
        $nie = IdentifierType::create([
            'name' => 'NIE'
        ]);

        $this->assertNull(Person::findByIdentifier('50496311H'));
        $this->assertNull(Person::findByIdentifier('Z2514326V','NIE'));

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);
        $person->identifier()->associate(Identifier::create([
            'value' => '50496311H',
            'type_id' => $dni->id
        ]));
        $person->save();

        $person2 = Person::create([
            'givenName' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeans'
        ]);
        $person2->identifier()->associate(Identifier::create([
            'value' => 'Z2514326V',
            'type_id' => $nie->id
        ]));
        $person2->save();

        $this->assertTrue($person->is(Person::findByIdentifier('50496311H')));
        $this->assertTrue($person2->is(Person::findByIdentifier('Z2514326V','NIE')));
        $this->assertTrue($person2->is(Person::findByIdentifier('Z2514326V',2)));
        $this->assertTrue($person2->is(Person::findByIdentifier('Z2514326V', $nie)));
        $this->assertNull(Person::findByIdentifier('Z2514326V'));
    }

    /**
     * @test
     */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        GoogleUser::create([
            'user_id' => $user->id,
            'google_id' => 123125634,
            'google_email' => 'pepepardo@iesebre.com'
        ]);

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'birthdate' => Carbon::createFromFormat('Y-m-d', '1978-03-02'),
            'birthplace_id' => 1,
            'gender' => 'Home',
            'civil_status' => 'Casat/da',
            'phone' => '977504878',
            'other_phones' => '977854859',
            'mobile' => '678514427',
            'other_mobiles' => '666514412',
            'email' => 'pepepardojeans@gmail.com',
            'other_emails' => 'pepepardojeans@gmail.com',
            'notes' => 'Bla bla bla',
        ]);
        $person->user_id = $user->id;
        $person->save();
        seed_identifier_types();
        $identifier1 = Identifier::create([
            'value' => '45784578C',
            'type_id' => 1
        ]);
        $person->identifier()->associate($identifier1);

        $identifier2 = Identifier::create([
            'value' => '47653688C',
            'type_id' => 1
        ]);

        $person->identifiers()->save($identifier2);

        $location = Location::create([
            'name' => 'Tortosa',
            'postalcode' => 43500
        ]);
        $person->birthplace_id = $location->id;

        $mappedPerson = $person->map();

        $this->assertEquals(1,$mappedPerson['id']);
        $this->assertEquals($user->id,$mappedPerson['userId']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedPerson['name']);
        $this->assertEquals('Pepe',$mappedPerson['givenName']);
        $this->assertEquals('Pardo',$mappedPerson['sn1']);
        $this->assertEquals('Jeans',$mappedPerson['sn2']);
        $this->assertEquals('pepepardojeans@gmail.com',$mappedPerson['email']);
        $this->assertEquals('pepepardojeans@gmail.com',$mappedPerson['userEmail']);
        $this->assertEquals(1,$mappedPerson['userId']);
        $this->assertEquals('pepepardo@iesebre.com',$mappedPerson['corporativeEmail']);
        $this->assertEquals('123125634',$mappedPerson['googleId']);


        $this->assertEquals('678514427',$mappedPerson['mobile']);

        $this->assertInstanceOf(Carbon::class,$mappedPerson['created_at']);
        $this->assertInstanceOf(Carbon::class,$mappedPerson['updated_at']);
        $this->assertEquals($mappedPerson['created_at']->format('h:i:sA d-m-Y'),$mappedPerson['formatted_created_at']);
        $this->assertEquals($mappedPerson['updated_at']->format('h:i:sA d-m-Y'),$mappedPerson['formatted_updated_at']);
        $this->assertEquals($mappedPerson['created_at']->timestamp,$mappedPerson['created_at_timestamp']);
        $this->assertEquals($mappedPerson['updated_at']->timestamp,$mappedPerson['updated_at_timestamp']);

        $this->assertEquals($identifier1->id, $mappedPerson['identifier_id']);
        $this->assertEquals('45784578C', $mappedPerson['identifier_value']);

        $this->assertNotNull($mappedPerson['extra_identifiers']);
        $this->assertEquals($identifier2->id, json_decode($mappedPerson['extra_identifiers'])[0]->id);
        $this->assertEquals('47653688C', json_decode($mappedPerson['extra_identifiers'])[0]->value);

        $this->assertEquals('02-03-1978', $mappedPerson['birthdate']->format('d-m-Y'));
        $this->assertEquals(1, $mappedPerson['birthplace_id']);
        $this->assertEquals('Tortosa', $mappedPerson['birthplace_name']);
        $this->assertEquals(43500, $mappedPerson['birthplace_postalcode']);
        $this->assertEquals('Casat/da', $mappedPerson['civil_status']);
        $this->assertEquals('Home', $mappedPerson['gender']);
        $this->assertEquals('977504878', $mappedPerson['phone']);
        $this->assertEquals('977854859', $mappedPerson['other_phones']);
        $this->assertEquals('678514427', $mappedPerson['mobile']);
        $this->assertEquals('666514412', $mappedPerson['other_mobiles']);
        $this->assertEquals('pepepardojeans@gmail.com', $mappedPerson['other_emails']);
        $this->assertEquals('Bla bla bla', $mappedPerson['notes']);

    }

    /**
     * @test
     */
    public function can_assign_user()
    {
        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'birthdate' => 'Jeans',
            'birthplace_id' => 'Jeans',
            'gender' => 'Home',
            'civil_status' => 'Casat/da',
            'phone' => '977504878',
            'other_phones' => 'Casat/da',
            'mobile' => '678514427',
            'other_mobiles' => '678514427',
            'email' => 'pepepardojeans@gmail.com',
            'other_emails' => 'pepepardojeans@gmail.com',
            'notes' => 'Bla bla bla',
        ]);
        $this->assertNull($person->user_id);

        $user = factory(User::class)->create();
        $person->assignUser($user);
        $this->assertEquals($person->user_id, $user->id);
        $user = factory(User::class)->create();
        $person->assignUser($user->id);
        $this->assertEquals($person->user_id, $user->id);
    }
}
