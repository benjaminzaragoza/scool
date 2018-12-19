<?php

namespace Tests\Unit\Tenants\Changelog;

use App\Console\Kernel;
use App\Events\Incidents\IncidentAssigned;
use App\Events\Incidents\IncidentClosed;
use App\Events\Incidents\IncidentDeleted;
use App\Events\Incidents\IncidentDesassigned;
use App\Events\Incidents\IncidentDescriptionUpdated;
use App\Events\Incidents\IncidentOpened;
use App\Events\Incidents\IncidentReplyAdded;
use App\Events\Incidents\IncidentReplyRemoved;
use App\Events\Incidents\IncidentReplyUpdated;
use App\Events\Incidents\IncidentShowed;
use App\Events\Incidents\IncidentStored;
use App\Events\Incidents\IncidentSubjectUpdated;
use App\Events\Incidents\StudyTagAdded;
use App\Events\Incidents\StudyTagRemoved;
use App\Logger\Incidents\IncidentLogger;
use App\Models\Incident;
use App\Models\IncidentTag;
use App\Models\Log;
use App\Models\Reply;
use App\Models\User;
use Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IncidentsLoggerTest.
 *
 * @package Tests\Unit\Tenants
 */
class IncidentsLoggerTest extends TestCase
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
    public function stored()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));

        $event = new IncidentStored($incident);
        IncidentLogger::stored($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha creat la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'store');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->old_loggable);
        $this->assertEquals($log->new_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->icon,'add');
        $this->assertEquals($log->color,'success');
    }

    /** @test */
    public function closed()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));

        Auth::login($user);
        $oldIncident = clone($incident);
        $incident->close();

        $event = new IncidentClosed($incident,$oldIncident);

        IncidentLogger::closed($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha tancat la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'close');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($oldIncident->map(false)));
        $this->assertEquals($log->new_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->old_value, 'Oberta');
        $this->assertEquals($log->new_value, 'Tancada');
        $this->assertEquals($log->icon,'lock');
        $this->assertEquals($log->color,'success');
    }

    /** @test */
    public function opened()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));

        Auth::login($user);
        $incident->close();
        $oldIncident = clone($incident);
        $incident->open();

        $event = new IncidentOpened($incident,$oldIncident);

        IncidentLogger::opened($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha reobert la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'open');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($oldIncident->map(false)));
        $this->assertEquals($log->new_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->new_value, 'Oberta');
        $this->assertEquals($log->old_value, 'Tancada');
        $this->assertEquals($log->icon,'lock_open');
        $this->assertEquals($log->color,'purple');
    }

    /** @test */
    public function showed()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));

        $event = new IncidentShowed($incident);
        Auth::login($user);
        IncidentLogger::showed($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha visitat la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'show');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->new_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->icon,'visibility');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function deleted()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $oldIncident = clone($incident);
        $incident->close();
        $event = new IncidentDeleted($oldIncident);
        Auth::login($user);
        $incident->delete();
        IncidentLogger::deleted($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha eliminat la incidència No funciona res aula 20');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'delete');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($event->oldIncident));
        $this->assertNull($log->new_loggable);
        $this->assertEquals($log->icon,'remove');
        $this->assertEquals($log->color,'error');
    }

    /** @test */
    public function descriptionUpdated()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $oldIncident = clone($incident);
        $incident->description = 'JORL JORL horl';
        $incident->save();
        $event = new IncidentDescriptionUpdated($incident,$oldIncident);
        IncidentLogger::descriptionUpdated($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha modificat la descripció de la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($oldIncident));
        $this->assertEquals($log->new_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->old_value, 'Bla bla bla');
        $this->assertEquals($log->new_value,'JORL JORL horl');
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function subjectUpdated()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $oldIncident = clone($incident);
        $incident->subject = 'No funciona res aula 21';
        $incident->save();
        $event = new IncidentSubjectUpdated($incident,$oldIncident);
        IncidentLogger::subjectUpdated($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha modificat el títol de la incidència <a target="_blank" href="/incidents/1">No funciona res aula 21</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($oldIncident));
        $this->assertEquals($log->new_loggable, json_encode($incident->map(false)));
        $this->assertEquals($log->old_value, 'No funciona res aula 20');
        $this->assertEquals($log->new_value, 'No funciona res aula 21');
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function replyAdded()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $oldIncident = clone($incident);
        $reply = Reply::create([
            'body' => 'Ja hem resolt la incidència',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $event = new IncidentReplyAdded($incident,$reply);
        IncidentLogger::replyAdded($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha afegit un comentari a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'comment');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->old_loggable);
        $this->assertEquals($log->new_loggable, json_encode($reply->map()));
        $this->assertEquals($log->old_value, '');
        $this->assertEquals($log->new_value, 'Ja hem resolt la incidència');
        $this->assertEquals($log->icon,'comment');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function replyUpdated()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $oldIncident = clone($incident);
        $reply = Reply::create([
            'body' => 'Ja hem resolt la incidència',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $oldReply = clone($reply);
        $reply->body = 'Perdo no hem resolt encara la incidència';

        $event = new IncidentReplyUpdated($incident,$reply,$oldReply);
        IncidentLogger::replyUpdated($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha actualitzat un comentari a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'comment');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertEquals($log->old_loggable, json_encode($oldReply));
        $this->assertEquals($log->new_loggable, json_encode($reply->map()));
        $this->assertEquals($log->old_value, 'Ja hem resolt la incidència');
        $this->assertEquals($log->new_value, 'Perdo no hem resolt encara la incidència');
        $this->assertEquals($log->icon,'comment');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function replyRemoved()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $reply = Reply::create([
            'body' => 'Ja hem resolt la incidència',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $oldReply = clone($reply);
        $reply->delete();

        $event = new IncidentReplyRemoved($incident,$oldReply);
        IncidentLogger::replyRemoved($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha esborrat un comentari a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'comment');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->new_loggable);
        $this->assertEquals($log->old_loggable, json_encode($oldReply));
        $this->assertEquals($log->old_value, 'Ja hem resolt la incidència');
        $this->assertNull($log->new_value);
        $this->assertEquals($log->icon,'comment');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function tagAdded()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $tag = IncidentTag::create([
            'value' => 'wontfix'
        ]);
        $event = new StudyTagAdded($incident, $tag);
        IncidentLogger::tagAdded($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha afegit la etiqueta <strong>wontfix</strong> a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'tag');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->old_loggable);
        $this->assertEquals($log->new_loggable, json_encode($tag->map()));
        $this->assertEquals($log->new_value, 'wontfix');
        $this->assertNull($log->old_value);
        $this->assertEquals($log->icon,'tag');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function tagRemoved()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $oldTag = IncidentTag::create([
            'value' => 'wontfix'
        ]);
        $event = new StudyTagRemoved($incident, $oldTag);
        IncidentLogger::tagRemoved($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha eliminat la etiqueta <strong>wontfix</strong> a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'tag');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->new_loggable);
        $this->assertEquals($log->old_loggable, json_encode($oldTag->map()));
        $this->assertEquals($log->old_value, 'wontfix');
        $this->assertNull($log->new_value);
        $this->assertEquals($log->icon,'tag');
        $this->assertEquals($log->color,'primary');
    }

    /** @test */
    public function incidentAssigned()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]));
        $event = new IncidentAssigned($incident,$user);
        IncidentLogger::incidentAssigned($event);
        $log = Log::first();
        $this->assertEquals($log->text,'S\'ha assignat l\'usuari <a target="_blank" href="/users/1">Pepe Pardo Jeans</a> a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'store');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->old_loggable);
        $this->assertEquals($log->new_loggable, json_encode($user->map()));
        $this->assertEquals($log->new_value, 'Pepe Pardo Jeans');
        $this->assertNull($log->old_value);
        $this->assertEquals($log->icon,'add');
        $this->assertEquals($log->color,'success');
    }

    /** @test */
    public function incidentDesassigned()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]));
        $event = new IncidentDesassigned($incident, $user);
        IncidentLogger::incidentDesassigned($event);
        $log = Log::first();
        $this->assertEquals($log->text,'S\'ha desassignat l\'usuari <a target="_blank" href="/users/1">Pepe Pardo Jeans</a> a la incidència <a target="_blank" href="/incidents/1">No funciona res aula 20</a>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'add');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNull($log->old_loggable);
        $this->assertEquals($log->new_loggable, json_encode($user->map()));
        $this->assertEquals($log->old_value, 'Pepe Pardo Jeans');
        $this->assertNull($log->new_value);
        $this->assertEquals($log->icon,'add');
        $this->assertEquals($log->color,'success');
    }
}
