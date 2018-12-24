<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudyFamilyControllerTest.
 *
 * @package Tests\Feature
 */
class StudyFamilyControllerTest extends BaseTenantTest
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
    public function can_assign_family_to_study()
    {
        $study = create_sample_study();
        $family = Family::create([
            'name' => 'Sanitat',
            'code' => 'SANITAT',
        ]);
        $this->loginAsSuperAdmin('api');
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/family/' . $family->id);
        $result = json_decode($response->getContent());
        $this->assertEquals(1,$result->id);
        $this->assertEquals("Desenvolupament Aplicacions Multiplataforma",$result->name);
        $this->assertEquals($family->id,$result->family_id);
        $this->assertEquals('Sanitat',$result->family_name);
        $this->assertEquals('SANITAT',$result->family_code);

        $study = $study->fresh();
        $this->assertNotNull($study->family);
        $this->assertTrue($family->is($study->family));
        $this->assertEquals($study->family->name,'Sanitat');
    }

}
