<?php

namespace Tests\Feature;

use App;
use App\Models\Address;
use App\Models\Employee;
use App\Models\Identifier;
use App\Models\Job;
use App\Models\Location;
use App\Models\Person;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ApprovedTeacherControllerTest.
 *
 * @package Tests\Feature
 */
class ApprovedTeacherControllerTest extends BaseTenantTest
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
    public function store_approved_teacher()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $pendingTeacher = add_fake_pending_teacher();

        $job = Job::create([
            'code' => '018',
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'order' => 1,
        ]);

        Role::firstOrCreate([
            'name' => 'Teacher',
            'guard_name' => 'web'
        ]);

        $response = $this->json('POST','/api/v1/approved_teacher', [
            'username' => 'pepepardo',
            'name' => $pendingTeacher->name,
            'sn1' => $pendingTeacher->sn1,
            'sn2' => $pendingTeacher->sn2,
            'photo' => 'tenant_test/user_photos/photo.png',

            //Job
            'job_id' => $job->id,

            //Teacher
            'administrative_status_id' => $pendingTeacher->administrative_status_id,
            'specialty_id' => $pendingTeacher->specialty_id,
            'degree' => $pendingTeacher->degree,
            'altres_titulacions' => $pendingTeacher->other_degrees,
            'idiomes' => $pendingTeacher->languages,
            'altres_formacions' => $pendingTeacher->other_training,
            'perfil_professional' => $pendingTeacher->profiles,
            'data_inici_treball' => $pendingTeacher->teacher_start_date,
            'data_incorporacio_centre' =>  $pendingTeacher->start_date,
            'data_superacio_oposicions' => $pendingTeacher->opositions_date,
            'lloc_destinacio_definitiva' => $pendingTeacher->destination_place,

            //Person
            'identifier' => $pendingTeacher->identifier,
            'identifier_type' => $pendingTeacher->identifier_type,
            'birthdate' => $pendingTeacher->birthdate,
            'phone' => $pendingTeacher->phone,
            'other_phones' => $pendingTeacher->other_phones,
            'mobile' => $pendingTeacher->mobile,
            'other_mobiles' => $pendingTeacher->other_mobiles,
            'email' => $pendingTeacher->email,
            'other_emails' => $pendingTeacher->other_emails,

            //Address
            'street' => $pendingTeacher->street,
            'number' => $pendingTeacher->number,
            'floor' => $pendingTeacher->floor,
            'floor_number' => $pendingTeacher->floor_number,
            'locality' => $pendingTeacher->locality,
            'province_id' => $pendingTeacher->province_id,
            'postal_code' => $pendingTeacher->postal_code,

            'pending_teacher_id' => $pendingTeacher->id

        ]);

        $response->assertSuccessful();

        //Check pending teacher is removed
        $pendingTeacher = $pendingTeacher->fresh();
        $this->assertNull($pendingTeacher);

        //Check user
        $this->assertNotNull($user = User::findByEmail('pepepardo@iesebre.com'));
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardo@iesebre.com',$user->email);
        $this->assertEquals('tenant_test/user_photos/photo.png',$user->photo);
        $this->assertEquals('7e98f5986f80d2cbd012df6bd8801558',$user->photo_hash);

        // Check teacher rol
        $this->assertTrue($user->hasRole('Teacher'));

        // Check employee (a job is assigned to user)
        $this->assertNotNull($employee = Employee::where('user_id',$user->id)->where('job_id', $job->id)->first());
        $this->assertTrue($employee->start_at->diffInMinutes() === 0);
        $this->assertEquals(null,$employee->end_at);

        //Check teacher
        $this->assertNotNull($teacher = $user->teacher);

        $this->assertEquals(5,$teacher->administrative_status_id);
        $this->assertEquals(1,$teacher->specialty_id);
        $this->assertEquals('Enginyer en chapuzas varias',$teacher->titulacio_acces);
        $this->assertEquals('Master emerito por la Juan Carlos Primero',$teacher->altres_titulacions);
        $this->assertEquals('Suajili',$teacher->idiomes);
        $this->assertEquals('Fuster',$teacher->altres_formacions);
        $this->assertEquals('Master of the Universe',$teacher->perfil_professional);
        $this->assertEquals('2015',$teacher->data_inici_treball);
        $this->assertEquals('2017-03-06',$teacher->data_incorporacio_centre);
        $this->assertEquals('2009-06-10',$teacher->data_superacio_oposicions);
        $this->assertEquals('La Seu Urgell',$teacher->lloc_destinacio_definitiva);

        //Check person
        $this->assertNotNull($person = Person::where('user_id',$user->id)->first());
        $this->assertEquals($user->id, $person->user_id);
        $this->assertEquals(1,$person->identifier_id);
        $this->assertEquals('Pepe',$person->givenName);
        $this->assertEquals('Pardo',$person->sn1);
        $this->assertEquals('Jeans',$person->sn2);
        $this->assertEquals('04-02-1980',$person->birthdate->format('d-m-Y'));
        $this->assertEquals('977405689',$person->phone);
        $this->assertEquals('["977854265","9778542456"]',$person->other_phones);
        $this->assertEquals('679852467',$person->mobile);
        $this->assertEquals('["651750489","689534729"]',$person->other_mobiles);
        $this->assertEquals('pepe@pardo.com',$person->email);
        $this->assertEquals('["pepepardojeans@gmail.com","ppardo@xtec.cat"]',$person->other_emails);

        //Check identifier
        $this->assertNotNull($identifier = Identifier::where('value','84008343S')->first());
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('84008343S',$identifier->value);

        //Check address
        $this->assertNotNull($address = Address::where('person_id',$person->id)->first());
        $this->assertEquals($person->id,$address->person_id);
        $this->assertEquals('Alcanyiz',$address->name);
        $this->assertEquals('40',$address->number);
        $this->assertEquals('3',$address->floor);
        $this->assertEquals('1',$address->floor_number);
        $this->assertEquals('1',$address->location_id);
        $this->assertEquals('36',$address->province_id);

        //Check Location
        $this->assertNotNull($location = Location::find($address->location_id)->first());
        $this->assertEquals('TORTOSA',$location->name);
        $this->assertEquals('43500',$location->postalcode);

        // Check another teacher using an existing Location

        Location::create([
            'name' => 'ROQUETES',
            'postalcode' => '43520'
        ]);
        $pendingTeacher = add_fake_pending_teacher();

        $response = $this->json('POST','/api/v1/approved_teacher', [
            'username' => 'pepaparda',
            'name' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeans',
            'photo' => 'tenant_test/user_photos/photo.png',

            'code' => '041',
            'administrative_status_id' => 1,
            'specialty_id' => 1,
            'degree' => 'Enginyer en Enginyeria',
            'altres_titulacions' => 'Doctorat en Filologia Hispànica',
            'idiomes' => 'Anglès',
            'altres_formacions' => 'Nivell D Català',
            'perfil_professional' => 'Digital, Clil, Projectes FP',
            'data_inici_treball' => '2004',
            'data_incorporacio_centre' => '1993-09-01',
            'data_superacio_oposicions' => '2009',
            'lloc_destinacio_definitiva' => 'Tarragona',

            'identifier' => '72024157W',
            'identifier_type' => 'NIF',
            'birthdate' => '2008-05-25',
            'birthplace_id' => 1,
            'gender' => 'Home',
            'civil_status' => 'Casat/da',
            'phone' => '977405689',
            'other_phones' => '977405675,977405678',
            'mobile' => '679585427',
            'other_mobiles' => '689585457,679582424',
            'email' => 'pepaparda@jeans.com',
            'other_emails' => 'pepaparda@gmail.com,pepaparda@xtec.cat',
            'notes' => 'Bla bla bla',

            //Address

            'street' => 'C/ Beseit',
            'number' => '24',
            'floor' => '1r',
            'floor_number' => '2a',
            'locality' => 'TORTOSA',
            'province_id' => 1,
            'postal_code' => '43500',

            'job_id' => 1,

            'pending_teacher_id' => $pendingTeacher->id
        ]);
        $response->assertSuccessful();

        //Check Location
        $user2 = User::findByEmail('pepaparda@iesebre.com');
        $person2 = Person::where('user_id',$user2->id)->first();
        $address2 = Address::where('person_id',$person2->id)->first();
        $this->assertNotNull($location2 = Location::find($address2->location_id));
        $this->assertEquals('TORTOSA',$location2->name);
        $this->assertEquals('43500',$location2->postalcode);

    }

    /** @test */
    public function store_approved_teacher_validation()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $response = $this->json('POST','/api/v1/approved_teacher', []);

        $response->assertStatus(422);
        $response = json_decode($response->getContent());
        $this->assertEquals('Les dades proporcionades no són vàlides', $response->message);

    }

    /** @test */
    public function regular_user_cannot_store_approved_teacher()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/approved_teacher');
        $response->assertStatus(403);
    }

    /** @test */
    public function remove_approved_teacher()
    {
        $admin = factory(User::class)->create();
        $admin->admin = true;
        $admin->save();
        $this->actingAs($admin,'api');

        $user = create_fake_teacher();
        $person = $user->person;
        $this->assertNotNull($person);
        $person_id = $person->id;
        $identifier = $person->identifier;
        $identifier_id = $identifier->id;
        $this->assertNotNull($identifier);
        $address = $person->address;
        $address_id = $address->id;
        $this->assertNotNull($address);
        $user_id = $user->id;

        $response = $this->json('DELETE','/api/v1/approved_teacher/' . $user_id);

        $response->assertSuccessful();
        $user = $user->fresh();
        $person = $person->fresh();
        $identifier = $identifier->fresh();
        $address = $address->fresh();

        $this->assertNull($user);
        $this->assertNull(User::find($user_id));
        $this->assertNull($person);
        $this->assertNull(Person::find($person_id));
        $this->assertNull($identifier);
        $this->assertNull(Person::find($identifier_id));
        $this->assertNull($address);
        $this->assertNull(Person::find($address_id));

    }
}
