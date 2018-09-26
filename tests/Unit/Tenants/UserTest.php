<?php

namespace Tests\Unit\Tenants;

use App\Models\Address;
use App\Models\AdministrativeStatus;
use App\Models\Employee;
use App\Models\Family;
use App\Models\Force;
use App\Models\GoogleUser;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\Person;
use App\Models\Position;
use App\Models\Province;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserTest.
 *
 * @package Tests\Unit
 */
class UserTest extends TestCase
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
    function can_get_formatted_created_at_date()
    {
        $user = factory(User::class)->make([
            'created_at' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $user->formatted_created_at);
    }

    /** @test */
    function can_get_created_at_timestamp()
    {
        $user = factory(User::class)->make([
            'created_at' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('1480618800', $user->created_at_timestamp);
    }

    /** @test */
    function can_get_updated_at_timestamp()
    {
        $user = factory(User::class)->make([
            'updated_at' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('1480618800', $user->updated_at_timestamp);
    }

    /** @test */
    function can_get_formatted_updated_at_date()
    {
        $user = factory(User::class)->make([
            'updated_at' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $user->formatted_updated_at);
    }

    /** @test */
    function can_create_user_if_not_exists()
    {
        $user = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        $this->assertEquals('Pepe Pardo Jeans', $user->name);
        $this->assertEquals('pepepardo@jeans.com', $user->email);
        $this->assertEquals('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',$user->password);
        $this->assertEquals('Pepe Pardo Jeans', $user->name);

        $newUser = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        $this->assertTrue($newUser->is($user));
    }

    /** @test */
    function can_add_role()
    {
        $user = factory(User::class)->create();

        $role = Role::create([
            'name' => 'Rol1',
        ]);

        $user->addRole($role);
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($role));

        $user->addRole($role);
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    function can_remove_role()
    {
        $user = factory(User::class)->create();
        $role = Role::create([
            'name' => 'Rol1',
        ]);
        $user->addRole($role);
        $user = $user->fresh();

        $user->rmRole($role);
        $user = $user->fresh();
        $this->assertFalse($user->hasRole($role));

        $user->rmRole($role);
        $user = $user->fresh();
        $this->assertFalse($user->hasRole($role));
    }

    /** @test */
    function can_unassign_job()
    {
        $user = factory(User::class)->create();
        $job = create_fake_job();
        $user->assignJob($job, false);
        $this->assertnotNull(Employee::where('user_id',$user->id)->where('job_id',$job->id)->first());

        $user->unassignJob($job);
        $user = $user->fresh();

        $this->assertNull(Employee::where('user_id',$user->id)->where('job_id',$job->id)->first());
    }

    /** @test */
    function can_unassign_jobs()
    {
        $user = factory(User::class)->create();
        $job = create_fake_job();
        $user->assignJob($job, false);
        $this->assertnotNull(Employee::where('user_id',$user->id)->where('job_id',$job->id)->first());

        $user->unassignJobs();
        $user = $user->fresh();

        $this->assertNull(Employee::where('user_id',$user->id)->where('job_id',$job->id)->first());
    }

    /** @test */
    function can_assign_job()
    {
        $user = factory(User::class)->create();

        $job = create_fake_job();

        $this->assertCount(0,$user->jobs);

        $result = $user->assignJob($job, false);
        $user = $user->fresh();
        $this->assertCount(1,$user->jobs);
        $job = $user->jobs()->first();
        $this->assertEquals('01',$job->code);
        $this->assertEquals('Professor/a',$job->type->name);
        $this->assertEquals('507',$job->specialty->code);
        $this->assertEquals('SANITAT',$job->family->code);
        $this->assertInstanceOf(User::class,$result);
        $this->assertEquals(0,Employee::where('user_id',$user->id)->where('job_id',$job->id)->first()->holder);

    }

    /** @test */
    function can_assign_job_as_holder()
    {
        $user = factory(User::class)->create();

        JobType::create(['name' => 'Professor/a']);
        Force::create([
            'name' => 'Secundària',
            'code' => 'SECUNDARIA'
        ]);

        $family = Family::create([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);
        Specialty::create([
            'name' => 'Informàtica',
            'code' => '507',
            'force_id' => Force::findByCode('SECUNDARIA'),
            'family_id' => $family->id
        ]);

        $this->assertCount(0,$user->jobs);

        $result = $user->assignJob(
            Job::firstOrCreate([
                'code' => '01',
                'type_id' => JobType::findByName('Professor/a')->id,
                'specialty_id' => Specialty::findByCode('507')->id,
                'family_id' => Family::findByCode('SANITAT')->id,
                'order' => 1
            ]));
        $user = $user->fresh();
        $this->assertCount(1,$user->jobs);
        $job = $user->jobs()->first();
        $this->assertEquals('01',$job->code);
        $this->assertEquals('Professor/a',$job->type->name);
        $this->assertEquals('507',$job->specialty->code);
        $this->assertEquals('SANITAT',$job->family->code);
        $this->assertInstanceOf(User::class,$result);

        $this->assertEquals(1,Employee::where('user_id',$user->id)->where('job_id',$job->id)->first()->holder);

    }

    /** @test */
    public function can_assign_fullname()
    {
        $user = factory(User::class)->create();
        $this->assertNull($user->fullname);

        $result = $user->assignFullName([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => '',
        ]);
        $user = $user->fresh();
        $this->assertEquals($user->person->givenName,'Pepe');
        $this->assertEquals($user->person->sn1,'Pardo');
        $this->assertEquals($user->person->sn2,'');
        $this->assertTrue($user->is($result));
    }

    /** @test */
    public function can_get_teachers()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_departments();
        initialize_specialities();
        initialize_users();
        initialize_teachers();

        $teachers = User::teachers()->get();

        $count = $teachers->count();
        // Teachers are users with role teacher and staff assigned as staff_type teacher
        foreach ($teachers as $teacher) {
            $this->assertTrue($teacher->hasRole('Teacher'));
            $this->assertNotNull($teacher->jobs->firstWhere('type_id', JobType::findByName('Professor/a')->id));
            $this->assertNotNull($teacher->teacher);
        }

        $user = create(User::class);

        $user->assignRole(Role::findByName('Teacher'));
        $teachers = User::teachers()->get();

        $newCount = $teachers->count();
        // Assert being a teacher is not enough you also have to be assigned a staff place of type teacher
        $this->assertEquals($newCount,$count);
    }

    /** @test */
    public function user_initial_photo_name()
    {
        $user = create(User::class);

        $this->assertEquals($user->photoName,
            $user->id . '_' . str_slug($user->name) . '_' . str_slug($user->email));
    }

    /** @test */
    public function user_photos_path()
    {
        $this->assertEquals(User::PHOTOS_PATH,'user_photos');
    }

    /** @test */
    public function can_assign_photo_to_user_with_partial_path()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar.jpg');
        $photo = Storage::disk('local')->putFile('teacher_photos',$fakeImage);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertNull($user->photo);
        $user->assignPhoto($photo,'tenant_test');
        $this->assertEquals($user->photo,'tenant_test/user_photos/1_pepe-pardo-jeans_pepepardo-at-jeanscom.jpeg');
        $this->assertEquals($user->photo_hash,'c4b959112f6f1d4933dc4506e14338c1');
        $this->assertTrue(Storage::disk('local')->exists($user->photo));

    }

    /** @test */
    public function can_assign_photo_to_user_with_full_path()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar2.jpg');
        $photo2 = Storage::disk('local')->putFile('teacher_photos',$fakeImage);
        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'pepaparda@jeans.com'
        ]);
        $this->assertNull($user->photo);
        $photo2 = Storage::disk('local')->path($photo2);
        $user->assignPhoto($photo2,'tenant_test');
        $this->assertEquals($user->photo,'tenant_test/user_photos/1_pepa-parda-jeans_pepaparda-at-jeanscom.jpeg');
        $this->assertEquals($user->photo_hash,'b3db77742683fb84aa526443af8e85bb');
        $this->assertTrue(Storage::disk('local')->exists($user->photo));
    }

    /** @test */
    public function can_unassign_photo_to_user()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar.jpg');
        $photo = Storage::disk('local')->putFile('tenant_test/teacher_photos',$fakeImage);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertNull($user->photo);
        $user->assignPhoto($photo,'tenant_test');
        $destPath = 'tenant_test/teacher_photos/' . $user->photo_name . '.jpg';
        $user->unassignPhoto($destPath);

        $user = $user->fresh();
        $this->assertEquals($user->photo,'');
        $this->assertEquals($user->photo_hash,'');
        $this->assertFalse(Storage::disk('local')->exists($user->photo));
        $this->assertTrue(Storage::disk('local')->exists($destPath));
    }

    /** @test */
    public function users_have_a_default_photo()
    {
        $this->assertEquals(User::DEFAULT_PHOTO,'default.png');
        $this->assertEquals(User::DEFAULT_PHOTO_PATH,'img/default.png');
        $this->assertEquals(User::PHOTOS_PATH,'user_photos');
    }

    /** @test */
    public function hash_id()
    {
        $user = factory(User::class)->create();
        $hashids = new \Hashids\Hashids(config('scool.salt'));
        $this->assertEquals($user->hashid,$hashids->encode($user->getKey()));
    }

    /** @test */
    public function user_can_have_a_photo()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar.jpg');

//        Storage::disk('local')->put('file.txt', 'Contents');
        Storage::disk('local')->putFile('prova',$fakeImage);


        $user = create(User::class);
        $this->assertNull($user->photo);

        $path = $user->photoPath . $user->photoName;
        $user->photo = $path;
        $user->save();

        $user= User::find($user->id);
        $this->assertEquals($user->photo,$path);
    }

    /** @test */
    public function assign_teacher_to_user()
    {
        $user = create(User::class);
        $result = $user->assignTeacher($teacher = Teacher::create([
            'code' => '040'
        ]));
        $this->assertTrue($teacher->is($user->teacher));
        $this->assertInstanceOf(User::class,$result);
    }

    /** @test */
    public function can_assign_personal_data_to_user()
    {
        $user = create(User::class);

        seed_identifier_types();
        $nif = IdentifierType::findByName('NIF')->id;

        Location::create([
            'name' => 'TORTOSA',
            'postalcode' => '43500'
        ]);
        $identifier = Identifier::firstOrCreate([
            'value' => '28444026H',
            'type_id' => $nif
        ]);
        $identifierId = $identifier->id;
        $location = Location::findByName('TORTOSA');
        $locationId = $location->id;
        $this->assertNull($user->person);
        $result = $user->assignPersonalData([
            'identifier_id' => $identifierId,
            'birthdate' => Carbon::parse('1988-03-02'),
            'birthplace_id' => $locationId,
            'gender' => 'Home',
            'mobile' => '679578437',
            'other_mobiles' => '645192821,645192822',
            'phone' => '977500949',
            'other_phones' => '9677508695,977500949',
            'notes' => "Coordinador d'informàtica",
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepaparda@jeans.com,pepepardo@gmail.com'
        ]);
        $user = $user->fresh();
        $this->assertNotNull($user->person);
        $this->assertEquals($user->person->identifier_id , $identifierId);
        $this->assertEquals($user->person->birthdate , '1988-03-02 00:00:00');
        $this->assertEquals($user->person->birthplace_id , $locationId);
        $this->assertEquals($user->person->gender , 'Home');
        $this->assertEquals($user->person->mobile , '679578437');
        $this->assertEquals($user->person->other_mobiles , '["645192821","645192822"]');
        $this->assertEquals($user->person->phone , '977500949');
        $this->assertEquals($user->person->other_phones , '["9677508695","977500949"]');
        $this->assertEquals($user->person->email , 'pepepardo@jeans.com');
        $this->assertEquals($user->person->other_emails , '["pepaparda@jeans.com","pepepardo@gmail.com"]');
        $this->assertEquals($user->person->notes , "Coordinador d'informàtica");
        $this->assertTrue($identifier->is($user->person->identifier , $identifierId));
        $this->assertTrue($location->is($user->person->birthplace , $location));
        $this->assertInstanceOf(User::class,$result);

        // With a person Already assigned
        $identifier = Identifier::firstOrCreate([
            'value' => '90121496K',
            'type_id' => $nif
        ]);
        $identifierId = $identifier->id;
        Location::create([
            'name' => 'Roquetes',
            'postalcode' => '43520'
        ]);
        $location = Location::findByName('Roquetes');
        $locationId = $location->id;
        $this->assertNotNull($user->person);

        $result = $user->assignPersonalData([
            'identifier_id' => $identifierId,
            'birthdate' => Carbon::parse('1981-03-02'),
            'birthplace_id' => $locationId,
            'gender' => 'Dona',
            'mobile' => '699999999',
            'other_mobiles' => '699999991,699999992',
            'phone' => '977509999',
            'other_phones' => '977509991,977509992',
            'notes' => "bla bla bla",
            'email' => 'pepepardo@black.com',
            'other_emails' => 'pepaparda@black.com,pepepardo@black.com'
        ]);
        $user = $user->fresh();
        $this->assertNotNull($user->person);
        $this->assertEquals($user->person->identifier_id , $identifierId);
        $this->assertEquals($user->person->birthdate , '1981-03-02 00:00:00');
        $this->assertEquals($user->person->birthplace_id , $locationId);
        $this->assertEquals($user->person->gender , 'Dona');
        $this->assertEquals($user->person->mobile , '699999999');
        $this->assertEquals($user->person->other_mobiles , '["699999991","699999992"]');
        $this->assertEquals($user->person->phone , '977509999');
        $this->assertEquals($user->person->other_phones , '["977509991","977509992"]');
        $this->assertEquals($user->person->email , 'pepepardo@black.com');
        $this->assertEquals($user->person->other_emails , '["pepaparda@black.com","pepepardo@black.com"]');
        $this->assertEquals($user->person->notes , "bla bla bla");
        $this->assertTrue($identifier->is($user->person->identifier , $identifierId));
        $this->assertTrue($location->is($user->person->birthplace , $location));

        $this->assertInstanceOf(User::class,$result);

    }

    /** @test */
    public function can_assign_address()
    {
        $location = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => '43500'
        ]);
        $province = Province::create([
            'name' => 'Tarragona',
            'postalcode' => '43500',
            'postal_code_prefix' => '43',
            'state_id' => 9
        ]);

        $user = factory(User::class)->create();
        $this->assertNull($user->address);
        $result = $user->assignAddress(Address::create([
            'name' => 'C/ Beseit',
            'number' => '16',
            'floor' => '4',
            'floor_number' => '2',
            'location_id' => Location::findByName('TORTOSA')->id,
            'province_id' => Province::findByName('Tarragona')->id,
        ]));
        $user = $user->fresh();

        $this->assertNotNull($user->person->address);
        $this->assertEquals($user->person->address->name,'C/ Beseit');
        $this->assertEquals($user->person->address->number,'16');
        $this->assertEquals($user->person->address->floor,'4');
        $this->assertEquals($user->person->address->floor_number,'2');
        $this->assertEquals($user->person->address->location_id, $location->id);
        $this->assertEquals($user->person->address->province_id, $province->id);
        $this->assertTrue($location->is($user->person->address->location));
        $this->assertTrue($province->is($user->person->address->province));

        $this->assertInstanceOf(User::class,$result);

    }

    /** @test */
    public function can_assign_teacher_data()
    {
        AdministrativeStatus::create([
            'name' => 'Funcionari/a amb plaça definitiva',
            'code' => 'FUNCIONARI DEF'
        ]);
        $definitiu = AdministrativeStatus::findByName('Funcionari/a amb plaça definitiva');

        $force = Force::create([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);

        $family = Family::create([
            'name' => "INFORMÀTICA",
            'code' => 'INF'
        ]);

        $specialty = Specialty::create([
            'code' => '507',
            'name' => 'INFORMÀTICA',
            'force_id' => $force->id,
            'family_id' => $family->id,
        ]);

        $user = factory(User::class)->create();
        $this->assertNull($user->teacher);
        $result = $user->assignTeacherData([
            'code' => '020',
            'administrative_status_id' => $definitiu->id,
            'specialty_id' => $specialty->id,
            'titulacio_acces' => 'Enginyer Superior en Telecomunicacions',
            'altres_titulacions' => 'Postgrau en Programari Lliure',
            'idiomes' => 'Certificat Aptitud Anglès Escola Oficial Idiomes',
            'altres_formacions' => 'Nivell D de Català',
            'perfil_professional' => 'De perfil milloro sí',
            'data_inici_treball' => '29/09/2006',
            'data_incorporacio_centre' => Carbon::parse('2009-09-01'),
            'data_superacio_oposicions' => 'Juny 2008'
        ]);
        $user = $user->fresh();
        $this->assertNotNull($user->teacher);
        $this->assertEquals($user->teacher->code,'020');
        $this->assertEquals($user->teacher->administrative_status_id,$definitiu->id);
        $this->assertEquals($user->teacher->specialty_id,$specialty->id);
        $this->assertTrue($definitiu->is($user->teacher->administrativeStatus));
        $this->assertTrue($specialty->is($user->teacher->specialty));
        $this->assertEquals($user->teacher->titulacio_acces,'Enginyer Superior en Telecomunicacions');
        $this->assertEquals($user->teacher->altres_titulacions,'Postgrau en Programari Lliure');
        $this->assertEquals($user->teacher->idiomes, 'Certificat Aptitud Anglès Escola Oficial Idiomes');
        $this->assertEquals($user->teacher->altres_formacions, 'Nivell D de Català');
        $this->assertEquals($user->teacher->perfil_professional, 'De perfil milloro sí');
        $this->assertEquals($user->teacher->data_inici_treball, '29/09/2006');
        $this->assertEquals($user->teacher->data_incorporacio_centre, '2009-09-01 00:00:00');
        $this->assertEquals($user->teacher->data_superacio_oposicions, 'Juny 2008');
        $this->assertInstanceOf(User::class,$result);

        $user2 = factory(User::class)->create();
        $user2->assignTeacher(Teacher::create([
            'code' => '040'
        ]));
        $this->assertNotNull($user2->teacher);
        $result = $user2->assignTeacherData([
            'administrative_status_id' => $definitiu->id,
            'specialty_id' => $specialty->id,
            'titulacio_acces' => 'Enginyer Superior en Telecomunicacions',
            'altres_titulacions' => 'Postgrau en Programari Lliure',
            'idiomes' => 'Certificat Aptitud Anglès Escola Oficial Idiomes',
            'altres_formacions' => 'Nivell D de Català',
            'perfil_professional' => 'De perfil milloro sí',
            'data_inici_treball' => '29/09/2006',
            'data_incorporacio_centre' => Carbon::parse('2009-09-01'),
            'data_superacio_oposicions' => 'Juny 2008'
        ]);
        $this->assertNotNull($user2->teacher);
        $this->assertEquals($user2->teacher->code,'040');
        $this->assertEquals($user2->teacher->administrative_status_id,$definitiu->id);
        $this->assertEquals($user2->teacher->specialty_id,$specialty->id);
        $this->assertTrue($definitiu->is($user2->teacher->administrativeStatus));
        $this->assertTrue($specialty->is($user2->teacher->specialty));
        $this->assertEquals($user2->teacher->titulacio_acces,'Enginyer Superior en Telecomunicacions');
        $this->assertEquals($user2->teacher->altres_titulacions,'Postgrau en Programari Lliure');
        $this->assertEquals($user2->teacher->idiomes, 'Certificat Aptitud Anglès Escola Oficial Idiomes');
        $this->assertEquals($user2->teacher->altres_formacions, 'Nivell D de Català');
        $this->assertEquals($user->teacher->perfil_professional, 'De perfil milloro sí');
        $this->assertEquals($user2->teacher->data_inici_treball, '29/09/2006');
        $this->assertEquals($user2->teacher->data_incorporacio_centre, '2009-09-01 00:00:00');
        $this->assertEquals($user2->teacher->data_superacio_oposicions, 'Juny 2008');
        $this->assertInstanceOf(User::class,$result);
    }

    /** @test */
    public function can_assign_position()
    {
        $user = factory(User::class)->create();
        $this->assertCount(0,$user->positions);
        $result = $user->assignPosition(Position::firstOrCreate([
            'name' => 'Director/a'
        ]));
        $user = $user->fresh();
        $this->assertCount(1, $user->positions);
        $this->assertInstanceOf(User::class,$result);
    }

    /** @test */
    public function can_check_if_user_is_teacher()
    {
        $user = factory(User::class)->create();
        $this->assertFalse($user->isTeacher());

        Role::firstOrCreate(['name' => 'Teacher']);
        JobType::firstOrCreate([
            'name' => 'Professor/a'
        ]);
        Family::firstOrCreate([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);
        Force::firstOrCreate([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);
        Specialty::firstOrCreate([
            'code' => 'CAS',
            'name' => 'Castellà',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);


        $teacher = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('CAS')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '002',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '002'
            ]));
        $teacher = $teacher->fresh();
        $this->assertTrue($teacher->isTeacher());
    }

    /** @test */
    public function can_find_by_name()
    {
        $this->assertEmpty(User::findByName('Pepe Pardo Jeans'));
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);
        $this->assertTrue($user->is(User::findByName('Pepe Pardo Jeans')));
    }

    /** @test */
    public function can_find_by_email()
    {
        $this->assertEmpty(User::findByEmail('pepepardo@jeans.com'));
        $user = factory(User::class)->create([
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertTrue($user->is(User::findByEmail('pepepardo@jeans.com')));
    }

    /** @test */
    public function can_have_google_user()
    {
        $user = factory(User::class)->create();
        $this->assertEmpty($user->googleUser);
    }

    /** @test */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        GoogleUser::create([
            'user_id' => $user->id,
            'google_id' => '87781322135468787',
            'google_email' => 'pepepardo@iesebre.com',
        ]);

        Person::create([
            'user_id' => $user->id,
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
        ]);

        $mappedUser = $user->map();

        $this->assertEquals(1,$mappedUser['id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedUser['name']);
        $this->assertEquals('Pepe',$mappedUser['givenName']);
        $this->assertEquals('Pardo',$mappedUser['sn1']);
        $this->assertEquals('Jeans',$mappedUser['sn2']);
        $this->assertEquals('pepepardojeans@gmail.com',$mappedUser['email']);
        $this->assertEquals('pepepardo@iesebre.com',$mappedUser['corporativeEmail']);
        $this->assertEquals('87781322135468787',$mappedUser['googleId']);
        $this->assertNull($mappedUser['email_verified_at']);
        $this->assertEquals('654789524',$mappedUser['mobile']);
        $this->assertNull($mappedUser['last_login']);
        $this->assertNull($mappedUser['last_login_ip']);
        $this->assertInstanceOf(Carbon::class,$mappedUser['created_at']);
        $this->assertInstanceOf(Carbon::class,$mappedUser['updated_at']);
        $this->assertEmpty($mappedUser['roles']);
        $this->assertEquals($mappedUser['created_at']->format('h:i:sA d-m-Y'),$mappedUser['formatted_created_at']);
        $this->assertEquals($mappedUser['updated_at']->format('h:i:sA d-m-Y'),$mappedUser['formatted_updated_at']);
        $this->assertEquals($mappedUser['created_at']->timestamp,$mappedUser['created_at_timestamp']);
        $this->assertEquals($mappedUser['updated_at']->timestamp,$mappedUser['updated_at_timestamp']);
        $this->assertNull($mappedUser['admin']);
        $this->assertEquals('MX',$mappedUser['hashid']);
        $this->assertEquals('pepepardojeans@gmail.com Pepe Pardo Jeans',$mappedUser['full_search']);
    }

    /** @test */
    public function assignGoogleUser()
    {
        $user = factory(User::class)->create();

        $user->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@iesebre.com'
        ]));

        $this->assertNotNull($user->googleUser);
        $this->assertEquals('231312312',$user->googleUser->google_id);
        $this->assertEquals('prova@iesebre.com',$user->googleUser->google_email);
    }

    /** @test */
    public function full_search_attribute() {
        $user = factory(User::class)->create([
            'email' => 'pepepardojeans@gmail.com',
            'name' => 'Pepe Pardo Jeans'
        ]);

        $this->assertEquals('pepepardojeans@gmail.com Pepe Pardo Jeans',$user->full_search);

    }
}
