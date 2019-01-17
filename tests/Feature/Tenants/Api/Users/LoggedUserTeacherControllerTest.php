<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Models\AdministrativeStatus;
use App\Models\Family;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Location;
use App\Models\Province;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class LoggedUserTeacherControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class LoggedUserTeacherControllerTest extends BaseTenantTest
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
     * @group users
     */
    public function logged_user_as_teacher_not_found()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/teacher');

        $response->assertStatus(404);
    }

    /** @test
     *  @group working
     *  @group users
     *  TODO: funciona a PHPSTORM però no per línia de comandes!!!
     */
    public function logged_user_as_teacher()
    {
//        $this->markTestSkipped('TODO');
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_departments();
        initialize_specialities();

        $user = User::createIfNotExists([
            'name' => 'Jaume Ramos Prades',
            'email' => 'jaumeramos@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Jaume',
                'sn1' => 'Ramos',
                'sn2' => 'Prades',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '041',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '041'
            ]));
        Location::create([
            'name' => 'BARCELONA',
            'postalcode' => '08080'
        ]);
        Location::create([
            'name' => 'TORTOSA',
            'postalcode' => '43500'
        ]);
        Province::create([
            'name' => 'TARRAGONA',
            'state_id' => 9,
            'postal_code_prefix' => '43'
        ]);
        AdministrativeStatus::create([
            'name' => 'Substitut/a',
            'code' => 'SUBSTITUT'
        ]);

        fake_personal_data_teachers();

        $this->actingAs(Teacher::findByCode('041')->user,'api');

        $response = $this->json('GET','/api/v1/teacher');

        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $this->assertEquals(1,$result->id);
        $this->assertEquals("1",$result->user_id);
        $this->assertEquals("041",$result->code);
        $this->assertEquals("1",$result->administrative_status_id);
        $this->assertEquals("11",$result->specialty_id);
        $this->assertEquals("Enginyer Superior en Telecomunicacions",$result->titulacio_acces);
        $this->assertEquals("Postgrau en Programari Lliure",$result->altres_titulacions);
        $this->assertEquals("Certificat Aptitud Anglès Escola Oficial Idiomes",$result->idiomes);
        $this->assertEquals("Nivell D de Català",$result->altres_formacions);
        $this->assertEquals("De perfil més guapo sí",$result->perfil_professional);
        $this->assertEquals("29/09/2006",$result->data_inici_treball);
        $this->assertEquals("2009-09-01 00:00:00",$result->data_incorporacio_centre);
        $this->assertEquals("Juny 2008",$result->data_superacio_oposicions);
        $this->assertEquals("Al quinto pino!",$result->lloc_destinacio_definitiva);
        $this->assertEquals("Juny 2008",$result->data_superacio_oposicions);
        $this->assertEquals("041 Jaume Ramos Prades jaumeramos@iesebre.com Jaume Ramos Prades 507 Informàtica INF Informàtica Substitut/a SUBSTITUT INF_507_1_041",$result->full_search);
        $this->assertEquals(11,$result->specialty->id);
        $this->assertEquals("507",$result->specialty->code);
        $this->assertEquals("Informàtica",$result->specialty->name);
        $this->assertEquals("2",$result->specialty->force_id);
        $this->assertEquals("2",$result->specialty->family_id);
        $this->assertEquals("Professors d'ensenyament secundari",$result->specialty->force->name);
        $this->assertEquals("SECUNDARIA",$result->specialty->force->code);

        $this->assertEquals(2,$result->specialty->family->id);
        $this->assertEquals("Informàtica",$result->specialty->family->name);
        $this->assertEquals("INF", $result->specialty->family->code);

        $this->assertEquals(1, $result->user->id);
        $this->assertEquals("Jaume Ramos Prades", $result->user->name);
        $this->assertEquals("jaumeramos@iesebre.com", $result->user->email);
        $this->assertEquals("041", $result->user->jobs[0]->code);
        $this->assertEquals("Informàtica", $result->user->jobs[0]->family->name);
        $this->assertEquals("INF", $result->user->jobs[0]->family->code);

        $this->assertEquals("Substitut/a", $result->administrative_status->name);
        $this->assertEquals("SUBSTITUT", $result->administrative_status->code);

        $this->assertEquals("Jaume", $result->user->person->givenName);
        $this->assertEquals("Ramos", $result->user->person->sn1);
        $this->assertEquals("Prades", $result->user->person->sn2);
        $this->assertEquals("Coordinador d'informàtica", $result->user->person->notes);
        $this->assertEquals("1978-03-02 00:00:00", $result->user->person->birthdate);
        $this->assertEquals("Home", $result->user->person->gender);
        $this->assertEquals("Casat/da", $result->user->person->civil_status);
        $this->assertEquals("977500949", $result->user->person->phone);
        $this->assertEquals("[\"9677508695\",\"977500949\"]", $result->user->person->other_phones);
        $this->assertEquals("679525437", $result->user->person->mobile);
        $this->assertEquals("[\"650192821\"]", $result->user->person->other_mobiles);
        $this->assertEquals("sergiturbadenas@gmail.com", $result->user->person->email);
        $this->assertEquals("[\"acacha@gmail.com\",\"sergitur@iesebre.com\"]", $result->user->person->other_emails);

        $this->assertEquals("C/ Beseit", $result->user->person->address->name);
        $this->assertEquals("16", $result->user->person->address->number);
        $this->assertEquals("4", $result->user->person->address->floor);
        $this->assertEquals("2", $result->user->person->address->floor_number);
        $this->assertEquals("TARRAGONA", $result->user->person->address->province->name);
        $this->assertEquals("TORTOSA", $result->user->person->address->location->name);
        $this->assertEquals("43500", $result->user->person->address->location->postalcode);

        $this->assertEquals("14268002K", $result->user->person->identifier->value);
        $this->assertEquals("BARCELONA", $result->user->person->birthplace->name);

        $this->assertEquals("INF", $result->user->jobs[0]->family->code);
        $this->assertEquals("507", $result->user->jobs[0]->specialty->code);
        $this->assertEquals("1", $result->user->jobs[0]->order);
        $this->assertEquals("041", $result->user->jobs[0]->code );

    }

}
