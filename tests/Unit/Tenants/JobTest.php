<?php

namespace Tests\Unit\Tenants;

use App\Models\Employee;
use App\Models\Family;
use App\Models\Force;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class JobTest.
 *
 * @package Tests\Unit
 */
class JobTest extends TestCase
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

    /**
     * Create example job.
     *
     * @return mixed
     */
    protected function create_example_job() {
        JobType::create([
            'name' => 'Professor/a'
        ]);

        Force::create([
            'name' => 'Professors d\'ensenyament secundari',
            'code' => 'SECUNDARIA'
        ]);

        Family::create([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);

        Specialty::create([
            'name' => 'Processos sanitaris',
            'code' => '518',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);

        return Job::create([
            'code' => '002',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('518')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'order' => 1
        ]);
    }

    /** @test */
    public function fullcode_accessor()
    {
        $job = $this->create_example_job();

        $this->assertEquals($job->fullcode,'SANITAT_518_1_002');

        $job2 = Job::create([
            'code' => '003',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('518')->id,
            'order' => 1
        ]);

        $this->assertEquals($job2->fullcode,'TOTES_518_1_003');

    }

    /** @test */
    public function description_accessor()
    {
        $job = $this->create_example_job();
        $this->assertEquals($job->description,'Plaça num 1 de la família Sanitat, especialitat Processos sanitaris');

        $job2 = Job::create([
            'code' => '003',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('518')->id,
            'order' => 1
        ]);

        $this->assertEquals($job2->description,'Plaça num 1, especialitat Processos sanitaris');
    }

    /** @test */
    public function find_by_code()
    {
        $this->assertNull(Job::findByCode('041'));

        $job = Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '041',
            'order' => 1
        ]);

        $this->assertTrue($job->is(Job::findByCode('041')));
    }

    /** @test */
    public function active_user_accessor()
    {
        $job = Job::firstOrCreate([
            'type_id' => 1,
            'code' => '041',
            'order' => 1
        ]);
        $this->assertEquals(null,$job->activeUser);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@iesebre.com'
        ]);
        $user->assignJob($job);
        $job = $job->fresh();
        $this->assertTrue($user->is($job->activeUser));

        $user2 = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'pepapardajeans@iesebre.com'
        ]);
        $user2->assignJob($job,false, Carbon::now()->subMonths(1), Carbon::now()->addMonths(1));
        $job = $job->fresh();
        $this->assertTrue($user2->is($job->activeUser));


        $job2 = Job::firstOrCreate([
            'type_id' => 1,
            'code' => '042',
            'order' => 1
        ]);
        $this->assertEquals(null,$job2->activeUser);

        $user3 = factory(User::class)->create([
            'name' => 'Ramona',
            'email' => 'ramona@iesebre.com'
        ]);
        $user3->assignJob($job2);

        $job2 = $job2->fresh();
        $this->assertTrue($user3->is($job2->activeUser));

        $user4 = factory(User::class)->create([
            'name' => 'Heidi',
            'email' => 'heidi@iesebre.com'
        ]);
        $user4->assignJob($job2,false, Carbon::now()->subMonths(1));
        $job2 = $job2->fresh();
        $this->assertTrue($user4->is($job2->activeUser));

    }

    /** @test */
    public function first_available_code()
    {
        $this->assertEquals('001',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '001',
            'order' => 1
        ]);

        $this->assertEquals('002',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '002',
            'order' => 1
        ]);

        $this->assertEquals('003',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '004',
            'order' => 1
        ]);

        $this->assertEquals('003',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '003',
            'order' => 1
        ]);

        $this->assertEquals('005',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '005',
            'order' => 1
        ]);

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '006',
            'order' => 1
        ]);
        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '007',
            'order' => 1
        ]);
        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '008',
            'order' => 1
        ]);

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '009',
            'order' => 1
        ]);

        $this->assertEquals('010',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '011',
            'order' => 1
        ]);

        $this->assertEquals('010',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '010',
            'order' => 1
        ]);

        $this->assertEquals('012',Job::firstAvailableCode());

        Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '080',
            'order' => 1
        ]);

        $this->assertEquals('012',Job::firstAvailableCode());
    }

    /** @test */
    public function add_substitute()
    {
        $job = Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '041',
            'order' => 1
        ]);

        $this->assertNull($job->subtitutes);
        $job->addSubtitute($user = factory(User::class)->create(['name' => 'Pepe Pardo Jeans']));
        $job = $job->fresh();
        $this->assertNotNull($job->substitutes);
        $substitute = $job->substitutes()->first();
        $this->assertEquals('Pepe Pardo Jeans',$substitute->name);

    }

    /**
     * @test
     */
    public function holder_description()
    {
        $job = Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'code' => '040',
            'order' => 2
        ]);
        $this->assertEquals('', $job->holder_description);

        $user = factory(User::class)->create(['name' => 'Pepe Pardo Jeans']);
        Employee::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'holder' => 1
        ]);
        $job = $job->refresh();
        $this->assertEquals('Pepe Pardo Jeans', $job->holder_description);
        Teacher::create([
            'user_id' => $user->id,
            'code' => 40
        ]);
        $job = $job->refresh();
        $this->assertEquals('40 Pepe Pardo Jeans', $job->holder_description);
    }

    /**
     * @test
     */
    public function full_search()
    {
        $specialty = Specialty::create([
            'code' => '507',
            'name' => 'INFORMÀTICA',
            'force_id' => 1
        ]);
        $family = Family::create([
            'name' => 'INFORMÀTICA',
            'code' => 'INF',
        ]);
        $job = Job::firstOrCreate([
            'type_id' => 1,
            'specialty_id' => $specialty->id,
            'family_id' => $family->id,
            'code' => '040',
            'order' => 2
        ]);

        $this->assertEquals('INF_507_2_040', $job->full_search);

    }
}
