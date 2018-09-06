<?php

namespace Tests\Unit;

use File;
use Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class HelpersTest.
 *
 * @package Tests\Unit
 */
class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group slow
     * @group google
     */
    public function google_group_exists()
    {
        $tenant = create_iesebre_tenant();
        configure_tenant($tenant);
        $this->assertTrue(google_group_exists('claustre@iesebre.com'));
        $this->assertFalse(google_group_exists('sdaawe12asdasdas@iesebre.com'));
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function google_user_exists()
    {
        $tenant = create_iesebre_tenant();
        configure_tenant($tenant);
        $this->assertTrue(google_user_exists('sergitur@iesebre.com'));
        $this->assertFalse(google_user_exists('rqweq123asda@iesebre.com'));
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function google_user_get()
    {
        $tenant = create_iesebre_tenant();
        configure_tenant($tenant);
        $user = google_user_get('sergitur@iesebre.com');
        $this->assertInstanceOf('Google_Service_Directory_User',$user);
        try {
            $user = google_user_get('rqweq123asda@iesebre.com');
        } catch (\Exception $e) {
            return;
        }
        $this->fail('No exception throw when trying to get an unexisting user');
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function google_group_get()
    {
        $tenant = create_iesebre_tenant();
        configure_tenant($tenant);
        $group = google_group_get('claustre@iesebre.com');
        $this->assertInstanceOf('Google_Service_Directory_Group',$group);
        try {
            $group = google_group_get('rqweq123asda@iesebre.com');
        } catch (\Exception $e) {
            return;
        }
        $this->fail('No exception throw when trying to get an unexisting group');
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function google_user_create()
    {
        google_user_create([
            'givenName' => 'Pepe',
            'familyName' => 'Pardo',
            'primaryEmail' => 'pepepardo@iesebre.com',
        ]);
        sleep('3');
        $this->assertTrue(google_user_exists('pepepardo@iesebre.com'));
        sleep('3');
        google_user_remove('pepepardo@iesebre.com');
    }

    /**
     * @test
     * @group slow
     */
    public function google_group_create()
    {
        google_group_create('provaesborrar@iesebre.com');
        sleep('3');
        $this->assertTrue(google_group_exists('provaesborrar@iesebre.com'));
    }

    /**
     * @test
     * @group slow
     */
    public function google_group_remove()
    {
        google_group_create('provaesborrar@iesebre.com');
        sleep('3');
        $this->assertFalse(google_group_exists('provaesborrar@iesebre.com'));
        google_group_remove('provaesborrar@iesebre.com');
    }

    /** @test */
    public function name()
    {
        $this->assertEquals('Pepe Pardo Jeans',name('Pepe','Pardo', 'Jeans'));
        $this->assertEquals('Pepe Pardo',name('Pepe','Pardo'));
        $this->assertEquals('Pepe Pardo',name(' Pepe ',' Pardo '));
    }

    /** @test */
    public function fullname()
    {
        $this->assertEquals('Pardo Jeans, Pepe',fullname('Pepe','Pardo', 'Jeans'));
        $this->assertEquals('Pardo, Pepe',fullname('Pepe','Pardo'));
        $this->assertEquals('Pardo, Pepe',fullname(' Pepe ',' Pardo '));
    }

    /** @test */
    public function get_photo_slugs_from_path()
    {
        Storage::fake('local');
        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        $tenant = 'tenant_test';
        Storage::disk('local')->put(
            $tenant . '/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );

        $photos = get_photo_slugs_from_path($tenant . '/teacher_photos');
        $this->assertInstanceOf(\Illuminate\Support\Collection::class,$photos);
        $this->assertCount(1,$photos);
        foreach ($photos as $photo) {
            $this->assertTrue(is_array($photo));
            $this->assertTrue(array_key_exists('file',$photo));
            $this->assertInstanceOf('Symfony\Component\Finder\SplFileInfo',$photo['file']);
            $this->assertTrue(array_key_exists('filename',$photo));
            $this->assertTrue(array_key_exists('slug',$photo));
        }
    }

    /** @test */
    public function test_propose_user_name(){

        $this->assertEquals('pepepardo',propose_user_name('Pepe','Pardo'));
        $this->assertEquals('pepepardo',propose_user_name('pepe','pardo'));
        $this->assertEquals('pepepardo',propose_user_name(' pepe ', ' pardo '));
        $this->assertEquals('pereramonpardo',propose_user_name(' Pere Ramon', ' pardo '));

        $this->assertEquals('nomlahosticognommolt',propose_user_name('NomLaHostiaDELLARG', 'CognomMOltLlarg'));
        $this->assertEquals(20,strlen(propose_user_name('NomLaHostiaDELLARG', 'CognomMOltLlarg')));
        $this->assertEquals('nomlahostipardo',propose_user_name('NomLaHostiaDELLARG', 'Pardo'));
        $this->assertEquals(15,strlen(propose_user_name('NomLaHostiaDELLARG', 'Pardo')));
    }

}
