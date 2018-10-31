<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Incident;
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
        $this->assertTrue(($mappedReply['user']->is($user)));
        $this->assertNotNull($mappedReply['created_at']);
        $this->assertNotNull($mappedReply['created_at_timestamp']);
        $this->assertNotNull($mappedReply['formatted_created_at']);
        $this->assertNotNull($mappedReply['updated_at']);
        $this->assertNotNull($mappedReply['updated_at_timestamp']);
        $this->assertNotNull($mappedReply['formatted_updated_at']);
    }

    /** @test */
    public function mapCollection() {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);

        $user = factory(User::class)->create([
            'name' => 'Oriol Junqueras',
            'email' => 'oriol@junqueras.cat'
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'Carles Puigdemont',
            'email' => 'krls@republica.cat'
        ]);
        $incident->addComment(Reply::create([
            'body' => 'Ja us hem arreglat la incidència',
            'user_id' =>  $user->id
        ]));
        $incident->addComment(Reply::create([
            'body' => 'Que passa',
            'user_id' =>  $user->id
        ]));

        $incident->addComment(Reply::create([
            'body' => 'Ja us hem arreglat la incidència 2',
            'user_id' =>  $user2->id
        ]));

        $incident->addComment(Reply::create([
            'body' => 'Prova comentari 2',
            'user_id' =>  $user->id
        ]));

        $comments = Reply::mapCollection($incident->comments);

        $this->assertEquals($comments[0]['body'],'Ja us hem arreglat la incidència');
        $this->assertEquals($comments[0]['user_id'],1);
        $this->assertEquals($comments[0]['user_name'],'Oriol Junqueras');
        $this->assertEquals($comments[0]['user_email'],'oriol@junqueras.cat');
        $this->assertnotNull($comments[0]['created_at']);
        $this->assertnotNull($comments[0]['created_at_timestamp']);
        $this->assertnotNull($comments[0]['formatted_created_at']);
        $this->assertnotNull($comments[0]['formatted_created_at_diff']);

        $this->assertEquals($comments[1]['body'],'Que passa');
        $this->assertEquals($comments[1]['user_id'],1);
        $this->assertEquals($comments[1]['user_name'],'Oriol Junqueras');
        $this->assertEquals($comments[1]['user_email'],'oriol@junqueras.cat');
        $this->assertnotNull($comments[1]['created_at']);
        $this->assertnotNull($comments[1]['created_at_timestamp']);
        $this->assertnotNull($comments[1]['formatted_created_at']);
        $this->assertnotNull($comments[1]['formatted_created_at_diff']);

        $this->assertEquals($comments[2]['body'],'Ja us hem arreglat la incidència 2');
        $this->assertEquals($comments[2]['user_id'],2);
        $this->assertEquals($comments[2]['user_name'],'Carles Puigdemont');
        $this->assertEquals($comments[2]['user_email'],'krls@republica.cat');
        $this->assertnotNull($comments[2]['created_at']);
        $this->assertnotNull($comments[2]['created_at_timestamp']);
        $this->assertnotNull($comments[2]['formatted_created_at']);
        $this->assertnotNull($comments[2]['formatted_created_at_diff']);
    }

    /**
     * @test
     */
    public function a_reply_can_have_an_associated_incident()
    {
        $user = factory(User::class)->create([
            'name' => 'Oriol Junqueras',
            'email' => 'oriol@junqueras.cat'
        ]);
        $reply = Reply::create([
            'body' => 'Ja us hem arreglat la incidència',
            'user_id' =>  $user->id
        ]);
        $incident = Incident::create([
            'subject' => 'No funciona PC 1 Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $incident->addComment($reply);

        $this->assertTrue($reply->incident->is($incident));
    }
}
