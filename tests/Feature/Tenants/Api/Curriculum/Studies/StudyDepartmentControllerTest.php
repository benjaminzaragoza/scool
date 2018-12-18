<?php

namespace Tests\Feature\Api\Studies\Curriculum;

use App\Events\Studies\StudyDepartmentUpdated;
use App\Models\Department;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudyDepartmentControllerTest.
 *
 * @package Tests\Feature
 */
class StudyDepartmentControllerTest extends BaseTenantTest
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
     */
    public function can_assign_department_to_study()
    {
        $study = create_sample_study();
        $department = Department::create([
            'name' => 'Departament de Sanitat',
            'shortname' => 'Sanitat',
            'code' => 'SANITAT',
            'order' => 2
        ]);
        $this->loginAsSuperAdmin('api');

        Event::fake();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/department/' . $department->id);
        Event::assertDispatched(StudyDepartmentUpdated::class,function ($event) use ($study, $department) {
            return $event->study->is($study) && $event->department->is($department);
        });
        $result = json_decode($response->getContent());

        $this->assertEquals(1,$result->id);
        $this->assertEquals("Desenvolupament Aplicacions Multiplataforma",$result->name);
        $this->assertEquals($department->id,$result->department_id);
        $this->assertEquals('Departament de Sanitat',$result->department_name);
        $this->assertEquals('Sanitat',$result->department_shortname);
        $this->assertEquals('SANITAT',$result->department_code);

        $study = $study->fresh();
        $this->assertNotNull($study->department);
        $this->assertTrue($department->is($study->department));
        $this->assertEquals($study->department->name,'Departament de Sanitat');
    }

}
