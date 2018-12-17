<?php

namespace Tests\Unit\Tenants;

use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class StudyTest.
 *
 * @package Tests\Unit
 */
class StudyTest extends TestCase
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
     * @test
     * @group curriculum
     */
    public function assignFamily()
    {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $this->assertNull($dam->family);
        $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $dam->assignFamily($informatica);
        $dam = $dam->fresh();
        $this->assertNotNull($dam->family);
        $this->assertTrue($dam->family->is($informatica));
    }

    /**
     * @test
     * @group curriculum
     */
    public function assignDepartment()
    {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $this->assertNull($dam->department);
        $informatica = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
        ]);
        $dam->assignDepartment($informatica);
        $dam = $dam->fresh();
        $this->assertNotNull($dam->department);
        $this->assertTrue($dam->department->is($informatica));
    }

    /**
     * @test
     * @group curriculum
     */
    public function assignTag()
    {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $this->assertCount(0, $dam->tags);
        $loe = StudyTag::create([
            'value' => 'LOE',
            'description' => 'Ley Orgànica de Educación'
        ]);
        $dam->assignTag($loe);
        $dam = $dam->fresh();
        $this->assertCount(1,$dam->tags);
        $this->assertTrue($dam->tags[0]->is($loe));
    }

    /**
     * @test
     * @group curriculum
     */
    public function map()
    {
        $study = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        $mappedStudy = $study->map();

        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedStudy['name']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedStudy['shortname']);
        $this->assertEquals('DAM',$mappedStudy['code']);
    }
}
