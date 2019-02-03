<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\User;
use App\Repositories\PersonRepository;
use Config;
use Illuminate\Contracts\Console\Kernel;
use stdClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class PersonRepositoryTest.
 *
 * @package Tests\Unit
 */
class PersonRepositoryTest extends TestCase
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
    public function store_person()
    {
        $personRepository = new PersonRepository();

        $person = new Stdclass();
        $person->user_id = 1;
        $person->identifier_id = 1;
        $person->givenName = 'Pepe';
        $person->sn1 = 'Pardo';
        $person->sn2 = 'Jeans';
        $person->birthdate = '2008-05-25';
        $person->birthplace_id = 1;
        $person->gender = 'Home';
        $person->civil_status = 'Casat/da';
        $person->phone = '977405689';
        $person->other_phones = '977405675,977405678';
        $person->mobile = '679585427';
        $person->other_mobiles = '689585457,679582424';
        $person->email = 'pepepardo@jeans@com';
        $person->other_emails = 'pepepardo@gmail.com,pepepardo@xtec.cat';
        $person->notes = 'Bla bla bla';

        $personEloquent = $personRepository->store($person);
        $this->assertInstanceOf(Person::class, $personEloquent);
        $this->assertEquals(1, $personEloquent->user_id);
        $this->assertEquals(1, $personEloquent->identifier_id);
        $this->assertEquals('Pepe', $personEloquent->givenName);
        $this->assertEquals('Pardo', $personEloquent->sn1);
        $this->assertEquals('Jeans', $personEloquent->sn2);
        $this->assertEquals('Jeans', $personEloquent->sn2);
        $this->assertEquals('25-05-2008', $personEloquent->birthdate->format('d-m-Y'));
        $this->assertEquals(1, $personEloquent->birthplace_id);
        $this->assertEquals('Home', $personEloquent->gender);
        $this->assertEquals('Casat/da', $personEloquent->civil_status);
        $this->assertEquals('977405689', $personEloquent->phone);
        $this->assertEquals('977405675,977405678', $personEloquent->other_phones);
        $this->assertEquals('679585427', $personEloquent->mobile);
        $this->assertEquals('689585457,679582424', $personEloquent->other_mobiles);
        $this->assertEquals('pepepardo@jeans@com', $personEloquent->email);
        $this->assertEquals('pepepardo@gmail.com,pepepardo@xtec.cat', $personEloquent->other_emails);
        $this->assertEquals('Bla bla bla', $personEloquent->notes);
    }
}
