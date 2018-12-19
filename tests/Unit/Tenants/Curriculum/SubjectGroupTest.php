<?php

namespace Tests\Unit\Tenants\Curriculum;


use App\Models\User;
use App\Models\SubjectGroup;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SubjectGroupTest.
 *
 * @package Tests\Unit
 */
class SubjectGroupTest extends TestCase
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
    public function find_by_code()
    {
        $this->assertNull(SubjectGroup::findByCode('040'));

        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Desenvolupament d’interfícies',
            'name' => 'Desenvolupament d’interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => 1,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
            'type' => 'Normal'
        ]);

        $this->assertTrue($group->is(SubjectGroup::findByCode('DAM_MP7')));
    }
}
