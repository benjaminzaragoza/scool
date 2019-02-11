<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Models\Address;
use App\Models\Identifier;
use App\Models\Location;
use App\Models\Person;
use App\Models\User;
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
    public function user_manager_can_add_personal_data_to_existing_user_birthplace()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $dataForm = [
            'birthplace' => [
                'postalcode' => '43501',
                'location' => 'SpringField',
                'province' => 'Massachussets',
            ],
        ];
        $response = $this->json('POST','/api/v1/user/' . $user->id . '/person',$dataForm);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertNull($result->birthplace_id);
        $this->assertEquals('SpringField 43501 Massachussets',$result->birthlocation);
    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_personal_data_to_existing_user_user_email_is_used_as_personal_email()
    {
        $this->withoutExceptionHandling();
        seed_identifier_types();
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertNull($user->person);
        $this->assertNull($user->identifiers);

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => 43500
        ]);

        $dataForm = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'identifier' => [
                'type_id' => 1,
                'value' => '23740827C',
            ],
            'other_identifiers' => [
                [
                    'type_id' => 2,
                    'value' => '789971545456AS',
                ],
                [
                    'type_id' => 3,
                    'value' => 'X1083960Q',
                ],
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
                'name' => 'C/ Major',
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

        $this->assertEquals(1,$result->id);
        $this->assertEquals(2,$result->userId);
        $this->assertEquals(2,$result->user_id);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('pepepardo@jeans.com',$result->userEmail);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);
        $this->assertEquals('NIF',$result->identifier_type);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('679545789',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('Bla bla bla',$result->notes);
        $this->assertNotNull($result->updated_at);
        $this->assertNotNull($result->created_at);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);

        $this->assertNotNull('dsdsa',$result->identifier_type);
        $this->assertNotNull(1,$result->identifier_type_id);
        $extra_identifiers = json_decode($result->extra_identifiers);

        $this->assertEquals(2,$extra_identifiers[0]->id);
        $this->assertEquals('789971545456AS',$extra_identifiers[0]->value);
        $this->assertEquals('2',$extra_identifiers[0]->type_id);
        $this->assertEquals('Pasaporte',$extra_identifiers[0]->type_name);
        $this->assertEquals(3,$extra_identifiers[1]->id);
        $this->assertEquals('X1083960Q',$extra_identifiers[1]->value);
        $this->assertEquals('3',$extra_identifiers[1]->type_id);
        $this->assertEquals('NIE',$extra_identifiers[1]->type_name);

        $this->assertNotNull($result->extra_identifiers);
        $this->assertNotNull($result->birthdate);
        $this->assertEquals('02-03-1978',$result->birthdate_formatted);
        $this->assertEquals(1,$result->birthplace_id);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('43500',$result->birthplace_postalcode);
        $this->assertEquals('Casat/da',$result->civil_status);
        $this->assertEquals('Home',$result->gender);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('["977554478","9774554874"]',$result->other_phones);
        $this->assertEquals('679545789',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$result->other_emails);
        $this->assertEquals('Bla bla bla',$result->notes);

        $user = $user->fresh();
        $this->assertnotNull($resultPerson = $user->person);
        $identifier = Identifier::first();
        $this->assertnotNull($identifier);
        $this->assertEquals('23740827C',$identifier->value);
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('23740827C',$resultPerson->identifier->value);
        $this->assertEquals(1,$resultPerson->identifier->type_id);

        $this->assertNotNull($user->person->identifiers);
        $this->assertEquals('789971545456AS',$resultPerson->identifiers[0]->value);
        $this->assertEquals('2',$resultPerson->identifiers[0]->type_id);
        $this->assertEquals('X1083960Q',$resultPerson->identifiers[1]->value);
        $this->assertEquals('3',$resultPerson->identifiers[1]->type_id);

        $this->assertEquals('pepepardo@jeans.com',$resultPerson->email);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$resultPerson->other_emails);

        $this->assertEquals('Pepe',$resultPerson->givenName);
        $this->assertEquals('Pardo',$resultPerson->sn1);
        $this->assertEquals('Jeans',$resultPerson->sn2);
        $this->assertEquals('679545789',$resultPerson->mobile);
        $this->assertEquals('["666555444","666999888"]',$resultPerson->other_mobiles);
        $this->assertEquals('977405026',$resultPerson->phone);
        $this->assertEquals('["977554478","9774554874"]',$resultPerson->other_phones);
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

        $this->assertEquals(1,$address->person_id);
        $this->assertEquals('C/ Major',$address->name);
        $this->assertEquals('12',$address->number);
        $this->assertEquals('Àtic',$address->floor);
        $this->assertEquals('2a',$address->floor_number);
        $this->assertEquals(1,$address->location_id);
        $this->assertEquals(1,$address->province_id);

    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_personal_data_to_existing_user_user_mobile_is_used_as_personal_mobile()
    {
        $this->withoutExceptionHandling();
        seed_identifier_types();
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'mobile' => '666777888'
        ]);
        $this->assertNull($user->person);
        $this->assertNull($user->identifiers);

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => 43500
        ]);

        $dataForm = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'identifier' => [
                'type_id' => 1,
                'value' => '23740827C',
            ],
            'other_identifiers' => [
                [
                    'type_id' => 2,
                    'value' => '789971545456AS',
                ],
                [
                    'type_id' => 3,
                    'value' => 'X1083960Q',
                ],
            ],
            'other_mobiles' => '666555444,666999888',
            'phone' => '977405026',
            'other_phones' => '977554478,9774554874',
            'gender' => 'Home',
            'birthdate' => '1978-03-02',
            'birthplace_id' => $tortosa->id,
            'civil_status' => 'Casat/da',
            'address' => [
                'name' => 'C/ Major',
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

        $this->assertEquals(1,$result->id);
        $this->assertEquals(2,$result->userId);
        $this->assertEquals(2,$result->user_id);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('pepepardo@jeans.com',$result->userEmail);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);
        $this->assertEquals('NIF',$result->identifier_type);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('666777888',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('Bla bla bla',$result->notes);
        $this->assertNotNull($result->updated_at);
        $this->assertNotNull($result->created_at);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);

        $this->assertNotNull('dsdsa',$result->identifier_type);
        $this->assertNotNull(1,$result->identifier_type_id);
        $extra_identifiers = json_decode($result->extra_identifiers);

        $this->assertEquals(2,$extra_identifiers[0]->id);
        $this->assertEquals('789971545456AS',$extra_identifiers[0]->value);
        $this->assertEquals('2',$extra_identifiers[0]->type_id);
        $this->assertEquals('Pasaporte',$extra_identifiers[0]->type_name);
        $this->assertEquals(3,$extra_identifiers[1]->id);
        $this->assertEquals('X1083960Q',$extra_identifiers[1]->value);
        $this->assertEquals('3',$extra_identifiers[1]->type_id);
        $this->assertEquals('NIE',$extra_identifiers[1]->type_name);

        $this->assertNotNull($result->extra_identifiers);
        $this->assertNotNull($result->birthdate);
        $this->assertEquals('02-03-1978',$result->birthdate_formatted);
        $this->assertEquals(1,$result->birthplace_id);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('43500',$result->birthplace_postalcode);
        $this->assertEquals('Casat/da',$result->civil_status);
        $this->assertEquals('Home',$result->gender);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('["977554478","9774554874"]',$result->other_phones);
        $this->assertEquals('666777888',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$result->other_emails);
        $this->assertEquals('Bla bla bla',$result->notes);

        $user = $user->fresh();
        $this->assertnotNull($resultPerson = $user->person);
        $identifier = Identifier::first();
        $this->assertnotNull($identifier);
        $this->assertEquals('23740827C',$identifier->value);
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('23740827C',$resultPerson->identifier->value);
        $this->assertEquals(1,$resultPerson->identifier->type_id);

        $this->assertNotNull($user->person->identifiers);
        $this->assertEquals('789971545456AS',$resultPerson->identifiers[0]->value);
        $this->assertEquals('2',$resultPerson->identifiers[0]->type_id);
        $this->assertEquals('X1083960Q',$resultPerson->identifiers[1]->value);
        $this->assertEquals('3',$resultPerson->identifiers[1]->type_id);

        $this->assertEquals('pepepardo@jeans.com',$resultPerson->email);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$resultPerson->other_emails);

        $this->assertEquals('Pepe',$resultPerson->givenName);
        $this->assertEquals('Pardo',$resultPerson->sn1);
        $this->assertEquals('Jeans',$resultPerson->sn2);
        $this->assertEquals('666777888',$resultPerson->mobile);
        $this->assertEquals('["666555444","666999888"]',$resultPerson->other_mobiles);
        $this->assertEquals('977405026',$resultPerson->phone);
        $this->assertEquals('["977554478","9774554874"]',$resultPerson->other_phones);
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

        $this->assertEquals(1,$address->person_id);
        $this->assertEquals('C/ Major',$address->name);
        $this->assertEquals('12',$address->number);
        $this->assertEquals('Àtic',$address->floor);
        $this->assertEquals('2a',$address->floor_number);
        $this->assertEquals(1,$address->location_id);
        $this->assertEquals(1,$address->province_id);

    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_personal_data_to_existing_user()
    {
        $this->withoutExceptionHandling();
        seed_identifier_types();
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertNull($user->person);
        $this->assertNull($user->identifiers);

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => 43500
        ]);

        $dataForm = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'identifier' => [
                'type_id' => 1,
                'value' => '23740827C',
            ],
            'other_identifiers' => [
                [
                    'type_id' => 2,
                    'value' => '789971545456AS',
                ],
                [
                    'type_id' => 3,
                    'value' => 'X1083960Q',
                ],
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
                'name' => 'C/ Major',
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

        $this->assertEquals(1,$result->id);
        $this->assertEquals(2,$result->userId);
        $this->assertEquals(2,$result->user_id);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('pepepardo@jeans.com',$result->userEmail);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);
        $this->assertEquals('NIF',$result->identifier_type);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('679545789',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('Bla bla bla',$result->notes);
        $this->assertNotNull($result->updated_at);
        $this->assertNotNull($result->created_at);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);

        $this->assertNotNull('dsdsa',$result->identifier_type);
        $this->assertNotNull(1,$result->identifier_type_id);
        $extra_identifiers = json_decode($result->extra_identifiers);

        $this->assertEquals(2,$extra_identifiers[0]->id);
        $this->assertEquals('789971545456AS',$extra_identifiers[0]->value);
        $this->assertEquals('2',$extra_identifiers[0]->type_id);
        $this->assertEquals('Pasaporte',$extra_identifiers[0]->type_name);
        $this->assertEquals(3,$extra_identifiers[1]->id);
        $this->assertEquals('X1083960Q',$extra_identifiers[1]->value);
        $this->assertEquals('3',$extra_identifiers[1]->type_id);
        $this->assertEquals('NIE',$extra_identifiers[1]->type_name);

        $this->assertNotNull($result->extra_identifiers);
        $this->assertNotNull($result->birthdate);
        $this->assertEquals('02-03-1978',$result->birthdate_formatted);
        $this->assertEquals(1,$result->birthplace_id);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('43500',$result->birthplace_postalcode);
        $this->assertEquals('Casat/da',$result->civil_status);
        $this->assertEquals('Home',$result->gender);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('["977554478","9774554874"]',$result->other_phones);
        $this->assertEquals('679545789',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$result->other_emails);
        $this->assertEquals('Bla bla bla',$result->notes);

        $user = $user->fresh();
        $this->assertnotNull($resultPerson = $user->person);
        $identifier = Identifier::first();
        $this->assertnotNull($identifier);
        $this->assertEquals('23740827C',$identifier->value);
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('23740827C',$resultPerson->identifier->value);
        $this->assertEquals(1,$resultPerson->identifier->type_id);

        $this->assertNotNull($user->person->identifiers);
        $this->assertEquals('789971545456AS',$resultPerson->identifiers[0]->value);
        $this->assertEquals('2',$resultPerson->identifiers[0]->type_id);
        $this->assertEquals('X1083960Q',$resultPerson->identifiers[1]->value);
        $this->assertEquals('3',$resultPerson->identifiers[1]->type_id);

        $this->assertEquals('pepepardo@jeans.com',$resultPerson->email);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$resultPerson->other_emails);

        $this->assertEquals('Pepe',$resultPerson->givenName);
        $this->assertEquals('Pardo',$resultPerson->sn1);
        $this->assertEquals('Jeans',$resultPerson->sn2);
        $this->assertEquals('679545789',$resultPerson->mobile);
        $this->assertEquals('["666555444","666999888"]',$resultPerson->other_mobiles);
        $this->assertEquals('977405026',$resultPerson->phone);
        $this->assertEquals('["977554478","9774554874"]',$resultPerson->other_phones);
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

        $this->assertEquals(1,$address->person_id);
        $this->assertEquals('C/ Major',$address->name);
        $this->assertEquals('12',$address->number);
        $this->assertEquals('Àtic',$address->floor);
        $this->assertEquals('2a',$address->floor_number);
        $this->assertEquals(1,$address->location_id);
        $this->assertEquals(1,$address->province_id);

    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_personal_data_to_existing_user_and_person()
    {
        $this->withoutExceptionHandling();
        seed_identifier_types();
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@gmail.com'
        ]);
        $this->assertNull($user->person);
        $this->assertNull($user->identifiers);

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => 43500
        ]);

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
        ]);
        $person->user_id = $user->id;
        $person->save();

        $dataForm = [
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'identifier' => [
                'type_id' => 1,
                'value' => '23740827C',
            ],
            'other_identifiers' => [
                [
                    'type_id' => 2,
                    'value' => '789971545456AS',
                ],
                [
                    'type_id' => 3,
                    'value' => 'X1083960Q',
                ],
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
                'name' => 'C/ Major',
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

        $this->assertEquals(1,$result->id);
        $this->assertEquals(2,$result->userId);
        $this->assertEquals(2,$result->user_id);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('pepepardo@gmail.com',$result->userEmail);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);
        $this->assertEquals('NIF',$result->identifier_type);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('679545789',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('Bla bla bla',$result->notes);
        $this->assertNotNull($result->updated_at);
        $this->assertNotNull($result->created_at);
        $this->assertEquals(1,$result->identifier_id);
        $this->assertEquals('23740827C',$result->identifier_value);

        $this->assertNotNull('dsdsa',$result->identifier_type);
        $this->assertNotNull(1,$result->identifier_type_id);
        $extra_identifiers = json_decode($result->extra_identifiers);

        $this->assertEquals(2,$extra_identifiers[0]->id);
        $this->assertEquals('789971545456AS',$extra_identifiers[0]->value);
        $this->assertEquals('2',$extra_identifiers[0]->type_id);
        $this->assertEquals('Pasaporte',$extra_identifiers[0]->type_name);
        $this->assertEquals(3,$extra_identifiers[1]->id);
        $this->assertEquals('X1083960Q',$extra_identifiers[1]->value);
        $this->assertEquals('3',$extra_identifiers[1]->type_id);
        $this->assertEquals('NIE',$extra_identifiers[1]->type_name);

        $this->assertNotNull($result->extra_identifiers);
        $this->assertNotNull($result->birthdate);
        $this->assertEquals('02-03-1978',$result->birthdate_formatted);
        $this->assertEquals(1,$result->birthplace_id);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('TORTOSA',$result->birthplace_name);
        $this->assertEquals('43500',$result->birthplace_postalcode);
        $this->assertEquals('Casat/da',$result->civil_status);
        $this->assertEquals('Home',$result->gender);
        $this->assertEquals('977405026',$result->phone);
        $this->assertEquals('["977554478","9774554874"]',$result->other_phones);
        $this->assertEquals('679545789',$result->mobile);
        $this->assertEquals('["666555444","666999888"]',$result->other_mobiles);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$result->other_emails);
        $this->assertEquals('Bla bla bla',$result->notes);

        $user = $user->fresh();
        $this->assertnotNull($resultPerson = $user->person);
        $identifier = Identifier::first();
        $this->assertnotNull($identifier);
        $this->assertEquals('23740827C',$identifier->value);
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('23740827C',$resultPerson->identifier->value);
        $this->assertEquals(1,$resultPerson->identifier->type_id);

        $this->assertNotNull($user->person->identifiers);
        $this->assertEquals('789971545456AS',$resultPerson->identifiers[0]->value);
        $this->assertEquals('2',$resultPerson->identifiers[0]->type_id);
        $this->assertEquals('X1083960Q',$resultPerson->identifiers[1]->value);
        $this->assertEquals('3',$resultPerson->identifiers[1]->type_id);

        $this->assertEquals('pepepardo@jeans.com',$resultPerson->email);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$resultPerson->other_emails);

        $this->assertEquals('Pepe',$resultPerson->givenName);
        $this->assertEquals('Pardo',$resultPerson->sn1);
        $this->assertEquals('Jeans',$resultPerson->sn2);
        $this->assertEquals('679545789',$resultPerson->mobile);
        $this->assertEquals('["666555444","666999888"]',$resultPerson->other_mobiles);
        $this->assertEquals('977405026',$resultPerson->phone);
        $this->assertEquals('["977554478","9774554874"]',$resultPerson->other_phones);
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

        $this->assertEquals(1,$address->person_id);
        $this->assertEquals('C/ Major',$address->name);
        $this->assertEquals('12',$address->number);
        $this->assertEquals('Àtic',$address->floor);
        $this->assertEquals('2a',$address->floor_number);
        $this->assertEquals(1,$address->location_id);
        $this->assertEquals(1,$address->province_id);

    }

    /**
     * @test
     * @group users
     */
    public function user_manager_cannot_add_personal_data_to_existing_user_if_data_is_void()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create();
        $dataForm = [];
        $response = $this->json('POST','/api/v1/user/' . $user->id . '/person',$dataForm);
        $response->assertStatus(422);
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
