<?php

namespace Tests\Feature\Api\Studies\Curriculum;

use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CurriculumControllerTest.
 *
 * @package Tests\Feature
 */
class StudiesControllerTest extends BaseTenantTest
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
    public function can_list_studies()
    {
        create_sample_studies();
        $this->loginAsSuperAdmin('api');

        $response =  $this->json('GET','/api/v1/studies');
        $response->assertSuccessful();
        $studies = json_decode($response->getContent());
        $this->assertCount(4,$studies);
        $this->assertSame(1,$studies[0]->id);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$studies[0]->name);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('DAM',$studies[0]->code);
        $this->assertNotNull($studies[0]->created_at);
        $this->assertNotNull($studies[0]->created_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_created_at);
        $this->assertNotNull($studies[0]->formatted_created_at_diff);
        $this->assertNotNull($studies[0]->updated_at);
        $this->assertNotNull($studies[0]->updated_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_updated_at);
        $this->assertNotNull($studies[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     */
    public function curriculum_manager_can_list_studies()
    {
        create_sample_studies();
        $this->loginAsCurriculumManager('api');

        $response =  $this->json('GET','/api/v1/studies');
        $response->assertSuccessful();
        $studies = json_decode($response->getContent());
        $this->assertCount(4,$studies);
        $this->assertSame(1,$studies[0]->id);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$studies[0]->name);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('Des. Apps Multiplataforma',$studies[0]->shortname);
        $this->assertEquals('DAM',$studies[0]->code);
        $this->assertNotNull($studies[0]->created_at);
        $this->assertNotNull($studies[0]->created_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_created_at);
        $this->assertNotNull($studies[0]->formatted_created_at_diff);
        $this->assertNotNull($studies[0]->updated_at);
        $this->assertNotNull($studies[0]->updated_at_timestamp);
        $this->assertNotNull($studies[0]->formatted_updated_at);
        $this->assertNotNull($studies[0]->formatted_updated_at_diff);
    }

    /** @test */
    public function regular_user_cannot_list_studies()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/studies');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_list_studies()
    {
        $response = $this->json('GET','/api/v1/studies');
        $response->assertStatus(401);
    }


    /**
     * @test
     */
    public function can_store_studies()
    {
        $this->loginAsSuperAdmin('api');

//        Event::fake();
//        create_setting('studies_manager_email','incidencies@iesebre.com','IncidentsManager');

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

        $response =  $this->json('POST','/api/v1/studies',$study = [
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. aplicacion Multiplataforma',
            'code' => 'DAM',
            'department' => $department->id,
            'family' => $family->id,
        ]);
        $response->assertSuccessful();
        $createdStudy = json_decode($response->getContent());

//        Event::assertDispatched(IncidentStored::class,function ($event){
//            return $event->incident->subject === 'Ordinador Aula 36 no funciona' && $event->incident->description === "El ordinador de l'aula 36 bla bla la";
//        });
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
            $study = Study::findOrFail($createdStudy->id);
        } catch (\Exception $e) {
            $this->fails('Study not found at database!');
        }

        $this->assertSame($study->id,1);
        $this->assertEquals($study->name,'Desenvolupament Aplicacions Multiplataforma');
        $this->assertEquals($study->shortname,'Des. aplicacion Multiplataforma');
        $this->assertEquals($study->code,'DAM');
        $this->assertNotNull($study->created_at);
        $this->assertNotNull($study->created_at_timestamp);
        $this->assertNotNull($study->formatted_created_at);
        $this->assertNotNull($study->formatted_created_at_diff);
        $this->assertNotNull($study->updated_at);
        $this->assertNotNull($study->updated_at_timestamp);
        $this->assertNotNull($study->formatted_updated_at);
        $this->assertNotNull($study->formatted_updated_at_diff);

    }

    /**
     * @test
     */
    public function curriculum_manager_can_store_studies()
    {
        $this->loginAsCurriculumManager('api');

//        Event::fake();
//        create_setting('studies_manager_email','incidencies@iesebre.com','IncidentsManager');

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

        $response =  $this->json('POST','/api/v1/studies',$study = [
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. aplicacion Multiplataforma',
            'code' => 'DAM',
            'department' => $department->id,
            'family' => $family->id,
        ]);
        $response->assertSuccessful();
        $createdStudy = json_decode($response->getContent());

//        Event::assertDispatched(IncidentStored::class,function ($event){
//            return $event->incident->subject === 'Ordinador Aula 36 no funciona' && $event->incident->description === "El ordinador de l'aula 36 bla bla la";
//        });
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
            $study = Study::findOrFail($createdStudy->id);
        } catch (\Exception $e) {
            $this->fails('Study not found at database!');
        }

        $this->assertSame($study->id,1);
        $this->assertEquals($study->name,'Desenvolupament Aplicacions Multiplataforma');
        $this->assertEquals($study->shortname,'Des. aplicacion Multiplataforma');
        $this->assertEquals($study->code,'DAM');
        $this->assertNotNull($study->created_at);
        $this->assertNotNull($study->created_at_timestamp);
        $this->assertNotNull($study->formatted_created_at);
        $this->assertNotNull($study->formatted_created_at_diff);
        $this->assertNotNull($study->updated_at);
        $this->assertNotNull($study->updated_at_timestamp);
        $this->assertNotNull($study->formatted_updated_at);
        $this->assertNotNull($study->formatted_updated_at_diff);

    }

    /**
     * @test
     */
    public function can_store_studies_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response = $this->json('POST', '/api/v1/studies', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function can_delete_studies()
    {
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        $response = $this->json('DELETE','/api/v1/studies/' . $study->id);

        $response->assertSuccessful();
        $study = $study->fresh();
        $this->assertNull($study);

    }
}
