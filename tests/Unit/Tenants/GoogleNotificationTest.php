<?php

namespace Tests\Unit\Tenants;

use App\Models\GoogleNotification;
use App\Models\GoogleWatch;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use stdClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleNotificationTest.
 *
 * @package Tests\Unit\Tenants
 */
class GoogleNotificationTest extends TestCase
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

    /** @test
     *  @group working
     */
    public function validate_request()
    {
        $request = $this->getMockBuilder('nonexistant')
            ->setMockClassName('Request')
            ->setMethods(array('header'))
            ->getMock();

        $request->expects($this->once())->method('header');
        $this->assertFalse(GoogleNotification::validate($request));

        $request = $this->getMockBuilder('nonexistant')
            ->setMockClassName('Request')
            ->setMethods(array('header'))
            ->getMock();
        $request->expects($this->once())->method('header')->with('x-goog-resource-state',null)
            ->willReturn('sync');
        $request->expects($this->once())->method('header')->with('x-goog-channel-id',null)
            ->willReturn('12345');
        GoogleWatch::create([
            'channel_id' => '12345',
            'token' => 'MY_TOKEN',
            'channel_type' => 'add'
        ]);

        $this->assertFalse(GoogleNotification::validate($request));
    }
}
