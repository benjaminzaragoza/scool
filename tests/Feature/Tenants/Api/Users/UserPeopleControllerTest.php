<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Models\Address;
use App\Models\Identifier;
use App\Models\Location;
use App\Models\Person;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserPeopleControllerTest.
 *
 * @package Tests\Feature
 */
class UserPeopleControllerTest extends BaseTenantTest
{
    use RefreshDatabase, CanLogin;

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
     * @group users
     */
    public function user_manager_can_add_personal_data_to_existing_user()
    {
        $this->withoutExceptionHandling();
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create();
        $this->assertNull($user->person);

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => 43500
        ]);

        $dataForm = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => [
                'type_id' => 1,
                'value' => '23740827C',
            ],
            'mobile' => '679545789',
            'other_mobiles' => '666555444,666999888',
            'phone' => '977405026',
            'other_phones' => '977554478,9774554874',
            'gender' => 'Home',
            'birthdate' => '1978-03-02',
            'birthplace_id' => $tortosa->id,
            'civil_status' => 'Casat/da',
            'address' => [
                'name' => 'Alcanyiz',
                'number' => '12',
                'floor' => 'Àtic',
                'floor_number' => '2a',
                'location_id' => 1,
                'province_id' => 1,
            ],
            'notes' => 'Bla bla bla'
        ];
        $response = $this->json('POST','/api/v1/user/' . $user->id . '/person',$dataForm);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
//        dump('RESULT:');
//        dump($result);
        $user = $user->fresh();
        $this->assertnotNull($resultPerson = $user->person);
        $identifier = Identifier::first();
        $this->assertnotNull($identifier);
        $this->assertEquals('23740827C',$identifier->value);
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('23740827C',$resultPerson->identifier->value);
        $this->assertEquals(1,$resultPerson->identifier->type_id);

        // TODO OTHER IDENTIFIERS

        // TODO EMAIL

        // TODO OTHER EMAILS

        $this->assertEquals('Pepe',$resultPerson->givenName);
        $this->assertEquals('Pardo',$resultPerson->sn1);
        $this->assertEquals('Jeans',$resultPerson->sn2);
        $this->assertEquals('679545789',$resultPerson->mobile);
        $this->assertEquals('666555444,666999888',$resultPerson->other_mobiles);
        $this->assertEquals('977405026',$resultPerson->phone);
        $this->assertEquals('977554478,9774554874',$resultPerson->other_phones);
        $this->assertEquals('Home',$resultPerson->gender);
        $this->assertEquals('Illuminate\Support\Carbon',get_class($resultPerson->birthdate));
        $this->assertEquals('1978-03-02',$resultPerson->birthdate->format('Y-m-d'));
        $this->assertEquals($tortosa->id,$resultPerson->birthplace_id);
        $this->assertEquals('App\Models\Location',get_class($resultPerson->birthplace));
        $this->assertEquals('TORTOSA',$resultPerson->birthplace->name);
        $this->assertEquals(43500,$resultPerson->birthplace->postalcode);
        $this->assertEquals('Casat/da',$resultPerson->civil_status);

        $this->assertEquals('Bla bla bla',$resultPerson->notes);

        $address = Address::first();
        $this->assertnotNull($address);

        $this->assertEquals('Alcanyiz',$address->name);
        $this->assertEquals('12',$address->number);
        $this->assertEquals('Àtic',$address->floor);
        $this->assertEquals('2a',$address->floor_number);
        $this->assertEquals(1,$address->location_id);
        $this->assertEquals(1,$address->province_id);

        // Address TODO
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_add_personal_data_to_existing_user()
    {
        $this->login('api');
        $user = factory(User::class)->create();
        $dataForm = [];
        $response = $this->json('POST','/api/v1/user/' . $user->id . '/person',$dataForm);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_add_personal_data_to_existing_user()
    {
        $user = factory(User::class)->create();
        $dataForm = [];
        $response = $this->json('POST','/api/v1/user/' . $user->id . '/person',$dataForm);
        $response->assertStatus(401);
    }
}
