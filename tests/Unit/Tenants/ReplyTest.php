<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Reply;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ReplyTest.
 *
 * @package Tests\Unit\Tenants
 */
class ReplyTest extends TestCase
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

    /** @test */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Oriol Junqueras',
            'email' => 'oriol@junqueras.cat'
        ]);
        $reply = Reply::create([
            'body' => 'Ja us hem arreglat la incidència',
            'user_id' =>  $user->id
        ]);

        $mappedReply = $reply->map();

        $this->assertEquals('Ja us hem arreglat la incidència',$mappedReply['body']);
        $this->assertEquals($user->id,$mappedReply['user_id']);
        $this->assertEquals('Oriol Junqueras',$mappedReply['user_name']);
        $this->assertEquals('oriol@junqueras.cat',$mappedReply['user_email']);

    }
}
