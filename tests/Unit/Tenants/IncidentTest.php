<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Incident;
use App\Models\IncidentTag;
use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IncidentTest.
 *
 * @package Tests\Unit\Tenants
 */
class IncidentTest extends TestCase
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
    public function assign_user() {
        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($incident->user);
        $result = $incident->assignUser($user = factory(User::class)->create());
        $incident = $incident->fresh();
        $this->assertNotNull($incident->user);
        $this->assertEquals($user->id,$incident->user->id);
        $this->assertTrue($incident->is($result));
    }

    /** @test */
    public function assign_user_by_id() {
        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($incident->user);
        $user = factory(User::class)->create();
        $result = $incident->assignUser($user->id);
        $incident = $incident->fresh();

        $this->assertNotNull($incident->user);
        $this->assertEquals($user->id,$incident->user->id);
        $this->assertTrue($incident->is($result));
    }

    /** @test */
    public function throwns_exception_assigning_incorrect_user() {
        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($incident->user);
        try {
            $incident->assignUser('pepe');
        } catch (\InvalidArgumentException $e) {

        }
    }

    /** @test */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);

        $mappedIncident = $incident->map();

        $this->assertEquals(1,$mappedIncident['id']);
        $this->assertEquals($user->id,$mappedIncident['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedIncident['user_name']);
        $this->assertEquals('pepepardo@jeans.com',$mappedIncident['user_email']);
        $this->assertTrue($mappedIncident['user']->is($user));
        $this->assertEquals('No funciona pc2 aula 15',$mappedIncident['subject']);
        $this->assertEquals('bla bla bla',$mappedIncident['description']);

        $this->assertNotNull($mappedIncident['created_at']);
        $this->assertNotNull($mappedIncident['updated_at']);
        $this->assertNotNull($mappedIncident['created_at_timestamp']);
        $this->assertNotNull($mappedIncident['updated_at_timestamp']);
        $this->assertNotNull($mappedIncident['formatted_created_at']);
        $this->assertNotNull($mappedIncident['formatted_updated_at']);
        $this->assertNotNull($mappedIncident['formatted_created_at_diff']);
        $this->assertNotNull($mappedIncident['formatted_updated_at_diff']);

        $this->assertEquals('incidents',$mappedIncident['api_uri']);

        $this->assertNull($mappedIncident['closed_at']);
        $this->assertNull($mappedIncident['formatted_closed_at']);
        $this->assertNull($mappedIncident['formatted_closed_at_diff']);
        $this->assertNull($mappedIncident['closed_at_timestamp']);

        $this->assertCount(0, $mappedIncident['comments']);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepe@pardojeans.com'
        ]);
        $comment = Reply::create([
            'body' => 'Si us plau podeu aportar més info',
            'user_id' => $user->id
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'Carles Puigdemont',
            'email' => 'krls@republicacatalana.cat'
        ]);
        $comment2 = Reply::create([
            'body' => 'En concret no funciona bla bla bla',
            'user_id' => $user2->id
        ]);
        $comment3 = Reply::create([
            'body' => 'Ok! Solucionat',
            'user_id' => $user->id
        ]);
        $incident->addComment($comment);
        $incident->addComment($comment2);
        $incident->addComment($comment3);

        $incident= $incident->fresh();
        $mappedIncident = $incident->map();
        $this->assertCount(3, $mappedIncident['comments']);
        $this->assertEquals('Si us plau podeu aportar més info',$mappedIncident['comments'][0]['body']);
        $this->assertEquals(2,$mappedIncident['comments'][0]['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedIncident['comments'][0]['user']->name);
        $this->assertEquals('pepe@pardojeans.com',$mappedIncident['comments'][0]['user']->email);
        $this->assertEquals('En concret no funciona bla bla bla',$mappedIncident['comments'][1]['body']);
        $this->assertEquals(3,$mappedIncident['comments'][1]['user']->id);
        $this->assertEquals('Carles Puigdemont',$mappedIncident['comments'][1]['user']->name);
        $this->assertEquals('krls@republicacatalana.cat',$mappedIncident['comments'][1]['user']->email);
        $this->assertEquals('Ok! Solucionat',$mappedIncident['comments'][2]['body']);
        $this->assertEquals(2,$mappedIncident['comments'][2]['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedIncident['comments'][2]['user']->name);
        $this->assertEquals('pepe@pardojeans.com',$mappedIncident['comments'][2]['user']->email);

        $incident->close();
        $incident= $incident->fresh();
        $mappedIncident = $incident->map();
        $this->assertNotNull($mappedIncident['closed_at']);
        $this->assertNotNull($mappedIncident['formatted_closed_at']);
        $this->assertNotNull($mappedIncident['closed_at_timestamp']);

        // TAGS
        $tag1 = IncidentTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $tag2 = IncidentTag::create([
            'value' => 'Tag2',
            'description' => 'Tag 2 bla bla bla',
            'color' => '#223423'
        ]);
        $tag3 = IncidentTag::create([
            'value' => 'Tag3',
            'description' => 'Tag 3 bla bla bla',
            'color' => '#333423'
        ]);
        $incident->addTag($tag1);
        $incident->addTag($tag2);
        $incident->addTag($tag3);

        $incident= $incident->fresh();
        $mappedIncident = $incident->map();
        $this->assertCount(3, $mappedIncident['tags']);
        $this->assertEquals('Tag1',$mappedIncident['tags'][0]['value']);
        $this->assertEquals('Tag 1 bla bla bla',$mappedIncident['tags'][0]['description']);
        $this->assertEquals('#453423',$mappedIncident['tags'][0]['color']);

        $this->assertEquals('Tag2',$mappedIncident['tags'][1]['value']);
        $this->assertEquals('Tag 2 bla bla bla',$mappedIncident['tags'][1]['description']);
        $this->assertEquals('#223423',$mappedIncident['tags'][1]['color']);

        $this->assertEquals('Tag3',$mappedIncident['tags'][2]['value']);
        $this->assertEquals('Tag 3 bla bla bla',$mappedIncident['tags'][2]['description']);
        $this->assertEquals('#333423',$mappedIncident['tags'][2]['color']);

        // Assigneees
        $assignee1 = factory(User::class)->create();
        $assignee2 = factory(User::class)->create();
        $incident->addAssignee($assignee1);
        $incident->addAssignee($assignee2);

        $incident= $incident->fresh();
        $mappedIncident = $incident->map();
        $this->assertCount(2, $mappedIncident['assignees']);
        $this->assertEquals($assignee1->id,$mappedIncident['assignees'][0]['id']);
        $this->assertEquals($assignee2->id,$mappedIncident['assignees'][1]['id']);

    }

    /**
     * @test
     */
    public function close()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);

        $this->assertNull($incident->closed_at);

        $incident->close();

        $incident = $incident->fresh();
        $this->assertNotNull($incident->closed_at);
    }

    /**
     * @test
     */
    public function open()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);
        $incident->close();

        $incident->open();

        $incident = $incident->fresh();
        $this->assertNull($incident->closed_at);
    }

    /** @test */
    function can_get_formatted_created_at_date()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'created_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $incident->formatted_created_at);
    }

    /** @test */
    function can_get_formatted_closed_at_date()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'closed_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $incident->formatted_closed_at);
    }

    /** @test */
    function can_get_closed_at_timestamp()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'closed_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('1480618800', $incident->closed_at_timestamp);
    }

    /** @test */
    function can_get_api_uri()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);

        $this->assertEquals('incidents', $incident->api_uri);
    }

    /**
     * @test
     */
    public function can_add_reply()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);
        $user = factory(User::class)->create();
        $reply = Reply::create([
            'body' => 'Si us plau podeu detallar una mica més el problema?',
            'user_id' => $user->id
        ]);
        $this->assertCount(0,$incident->replies);
        $incident->addReply($reply);
        $incident = $incident->fresh();
        $this->assertCount(1,$incident->replies);
        $this->assertTrue($incident->replies->first()->is($reply));
        $this->assertEquals('Si us plau podeu detallar una mica més el problema?', $incident->replies->first()->body);
    }

    /**
     * @test
     */
    public function addTag()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);
        $tag = IncidentTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $this->assertCount(0,$incident->tags);
        $incident->addTag($tag);
        $incident = $incident->fresh();
        $this->assertCount(1,$incident->tags);
        $this->assertTrue($incident->tags[0]->is($tag));
    }

    /**
     * @test
     */
    public function addAssignee()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ]);
        $assignee = factory(User::class)->create();
        $this->assertCount(0,$incident->assignees);
        $incident->addAssignee($assignee);
        $incident = $incident->fresh();
        $this->assertCount(1,$incident->assignees);
        $this->assertTrue($incident->assignees[0]->is($assignee));
    }
}
