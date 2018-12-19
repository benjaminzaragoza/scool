<?php

namespace Tests\Feature\Api\Subjects\Curriculum;

use App\Events\Subjects\SubjectDeleted;
use App\Events\Subjects\SubjectStored;
use App\Models\Department;
use App\Models\Family;
use App\Models\Subject;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectsControllerTest.
 *
 * @package Tests\Feature
 */
class SubjectsControllerTest extends BaseTenantTest
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
     * @group curriculum
     */
    public function can_list_subjects()
    {
        initialize_fake_subjects();
        $this->loginAsSuperAdmin('api');

        $response =  $this->json('GET','/api/v1/subjects');
        $response->assertSuccessful();
        $subjects = json_decode($response->getContent());
        $this->assertCount(2,$subjects);
        $this->assertSame(1,$subjects[0]->id);
        $this->assertEquals('Disseny i implementació d’interfícies',$subjects[0]->name);
        $this->assertEquals('Interfícies',$subjects[0]->shortname);
        $this->assertEquals('DAM_MP7_UF1',$subjects[0]->code);
        $this->assertNotNull($subjects[0]->created_at);
        $this->assertNotNull($subjects[0]->created_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_created_at);
        $this->assertNotNull($subjects[0]->formatted_created_at_diff);
        $this->assertNotNull($subjects[0]->updated_at);
        $this->assertNotNull($subjects[0]->updated_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_updated_at);
        $this->assertNotNull($subjects[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_list_subjects()
    {
        initialize_fake_subjects();
        $this->loginAsCurriculumManager('api');

        $response =  $this->json('GET','/api/v1/subjects');
        $response->assertSuccessful();
        $subjects = json_decode($response->getContent());
        $this->assertCount(2,$subjects);
        $this->assertSame(1,$subjects[0]->id);
        $this->assertEquals('Disseny i implementació d’interfícies',$subjects[0]->name);
        $this->assertEquals('Interfícies',$subjects[0]->shortname);
        $this->assertEquals('DAM_MP7_UF1',$subjects[0]->code);
        $this->assertNotNull($subjects[0]->created_at);
        $this->assertNotNull($subjects[0]->created_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_created_at);
        $this->assertNotNull($subjects[0]->formatted_created_at_diff);
        $this->assertNotNull($subjects[0]->updated_at);
        $this->assertNotNull($subjects[0]->updated_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_updated_at);
        $this->assertNotNull($subjects[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_list_subjects()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/subjects');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_list_subjects()
    {
        $response = $this->json('GET','/api/v1/subjects');
        $response->assertStatus(401);
    }


    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjects()
    {
        $this->loginAsSuperAdmin('api');

        Event::fake();

        $department = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
        ]);

        $family = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF',
        ]);

        $response =  $this->json('POST','/api/v1/subjects',$subject = [
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. aplicacion Multiplataforma',
            'code' => 'DAM',
            'department' => $department->id,
            'family' => $family->id,
        ]);
        $response->assertSuccessful();
        $createdStudy = json_decode($response->getContent());
        Event::assertDispatched(StudyStored::class,function ($event) use ($createdStudy){
            return $event->study->is(Study::findOrFail($createdStudy->id));
        });
        $this->assertSame($createdStudy->id,1);
        $this->assertEquals($createdStudy->name,'Desenvolupament Aplicacions Multiplataforma');
        $this->assertEquals($createdStudy->shortname,'Des. aplicacion Multiplataforma');
        $this->assertEquals($createdStudy->code,'DAM');
        $this->assertNotNull($createdStudy->created_at);
        $this->assertNotNull($createdStudy->created_at_timestamp);
        $this->assertNotNull($createdStudy->formatted_created_at);
        $this->assertNotNull($createdStudy->formatted_created_at_diff);
        $this->assertNotNull($createdStudy->updated_at);
        $this->assertNotNull($createdStudy->updated_at_timestamp);
        $this->assertNotNull($createdStudy->formatted_updated_at);
        $this->assertNotNull($createdStudy->formatted_updated_at_diff);

        try {
            $subject = Study::findOrFail($createdStudy->id);
        } catch (\Exception $e) {
            $this->fails('Study not found at database!');
        }

        $this->assertSame($subject->id,1);
        $this->assertEquals($subject->name,'Desenvolupament Aplicacions Multiplataforma');
        $this->assertEquals($subject->shortname,'Des. aplicacion Multiplataforma');
        $this->assertEquals($subject->code,'DAM');
        $this->assertNotNull($subject->created_at);
        $this->assertNotNull($subject->created_at_timestamp);
        $this->assertNotNull($subject->formatted_created_at);
        $this->assertNotNull($subject->formatted_created_at_diff);
        $this->assertNotNull($subject->updated_at);
        $this->assertNotNull($subject->updated_at_timestamp);
        $this->assertNotNull($subject->formatted_updated_at);
        $this->assertNotNull($subject->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group curriculum
     */
    public function curriculum_manager_can_store_subjects()
    {
        $this->loginAsCurriculumManager('api');

        Event::fake();

        $department = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
        ]);

        $family = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF',
        ]);

        $response =  $this->json('POST','/api/v1/subjects',$subject = [
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. aplicacion Multiplataforma',
            'code' => 'DAM',
            'department' => $department->id,
            'family' => $family->id,
        ]);
        $response->assertSuccessful();
        $createdStudy = json_decode($response->getContent());
        Event::assertDispatched(StudyStored::class,function ($event) use ($createdStudy){
            return $event->study->is(Study::findOrFail($createdStudy->id));
        });
        $this->assertSame($createdStudy->id,1);
        $this->assertEquals($createdStudy->name,'Desenvolupament Aplicacions Multiplataforma');
        $this->assertEquals($createdStudy->shortname,'Des. aplicacion Multiplataforma');
        $this->assertEquals($createdStudy->code,'DAM');
        $this->assertNotNull($createdStudy->created_at);
        $this->assertNotNull($createdStudy->created_at_timestamp);
        $this->assertNotNull($createdStudy->formatted_created_at);
        $this->assertNotNull($createdStudy->formatted_created_at_diff);
        $this->assertNotNull($createdStudy->updated_at);
        $this->assertNotNull($createdStudy->updated_at_timestamp);
        $this->assertNotNull($createdStudy->formatted_updated_at);
        $this->assertNotNull($createdStudy->formatted_updated_at_diff);

        try {
            $subject = Study::findOrFail($createdStudy->id);
        } catch (\Exception $e) {
            $this->fails('Study not found at database!');
        }

        $this->assertSame($subject->id,1);
        $this->assertEquals($subject->name,'Desenvolupament Aplicacions Multiplataforma');
        $this->assertEquals($subject->shortname,'Des. aplicacion Multiplataforma');
        $this->assertEquals($subject->code,'DAM');
        $this->assertNotNull($subject->created_at);
        $this->assertNotNull($subject->created_at_timestamp);
        $this->assertNotNull($subject->formatted_created_at);
        $this->assertNotNull($subject->formatted_created_at_diff);
        $this->assertNotNull($subject->updated_at);
        $this->assertNotNull($subject->updated_at_timestamp);
        $this->assertNotNull($subject->formatted_updated_at);
        $this->assertNotNull($subject->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjects_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response = $this->json('POST', '/api/v1/subjects', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_delete_subjects()
    {
        $this->loginAsSuperAdmin('api');

        $subject = create_sample_subject();
        Event::fake();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        Event::assertDispatched(SubjectDeleted::class,function ($event) use ($subject){
            return $event->oldSubject['id'] === $subject->id;
        });

        $response->assertSuccessful();
        $subject = $subject->fresh();
        $this->assertNull($subject);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_delete_subjects()
    {
        $this->loginAsCurriculumManager('api');

        $subject = create_sample_subject();
        Event::fake();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        Event::assertDispatched(SubjectDeleted::class,function ($event) use ($subject){
            return $event->oldSubject['id'] === $subject->id;
        });

        $response->assertSuccessful();
        $subject = $subject->fresh();
        $this->assertNull($subject);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_delete_subjects()
    {
        $this->login('api');

        $subject = create_sample_subject();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_delete_subjects()
    {
        $subject = create_sample_subject();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        $response->assertStatus(401);
    }
}
