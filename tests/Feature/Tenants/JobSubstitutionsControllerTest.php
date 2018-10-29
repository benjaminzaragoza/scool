<?php

namespace Tests\Feature;

use App;
use App\Models\Employee;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class JobSubstitutionsControllerTest.
 *
 * @package Tests\Feature
 */
class JobSubstitutionsControllerTest extends BaseTenantTest
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
    public function can_remove_all_substitutions_associated_to_a_job()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');
        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $job->addSubtitute($user = factory(User::class)->create());
        $job->addSubtitute($user2 = factory(User::class)->create());
        $job->addSubtitute($user3 = factory(User::class)->create());
        $job = $job->fresh();
        $this->assertCount(3,$job->substitutes);


        $response = $this->json('DELETE','/api/v1/job/' . $job->id . '/substitutions');
        $response->assertSuccessful();

        $job = $job->fresh();
        $this->assertCount(0,$job->substitutes);
    }

    protected function create_job()
    {
        return Job::create([
            'type_id' => JobType::create(['name' => 'Professor/a'])->id,
            'code' => 'A1',
            'order' => 1
        ]);
    }

    /** @test */
    public function regular_user_cannot_remove_all_substitutions_associated_to_a_job()
    {
        $job = $this->create_job();
        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/job/' . $job->id . '/substitutions');
        $response->assertStatus(403);
    }

    /** @test */
    public function cannot_remove_all_substitutions_associated_to_an_unexisting_job()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/job/1/substitutions');
        $response->assertStatus(404);
    }

    /** @test */
    public function can_add_substitute_to_job()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();
        $response = $this->json('POST','/api/v1/job/' . $job->id . '/substitution', [
            'user' => $substitute->id,
            'start_at' => $date = Carbon::now()->toDateTimeString()
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $employee = Employee::where('user_id', $result->user_id)->where('job_id', $job->id)->first();
        $this->assertNotNull($employee);
        $this->assertEquals($date,$employee->start_at->toDateTimeString());
    }

    /** @test */
    public function can_add_substitute_to_job_validation()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        $response = $this->json('POST','/api/v1/job/' . $job->id . '/substitution', []);
        $response->assertStatus(422);
        $result = json_decode($response->getContent());
        $this->assertEquals('Les dades proporcionades no són vàlides',$result->message);
        $this->assertEquals('El camp user és obligatori.',$result->errors->user[0]);
        $this->assertEquals('El camp start at és obligatori.',$result->errors->start_at[0]);
    }

    /** @test */
    public function can_add_substitute_to_job_date_validation()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        $response = $this->json('POST','/api/v1/job/' . $job->id . '/substitution', [
            'start_at' => Carbon::now()->addDays(10)->toDateString(),
            'end_at' => Carbon::now()->toDateString()
        ]);
        $response->assertStatus(422);
        $result = json_decode($response->getContent());
        $this->assertEquals('Les dades proporcionades no són vàlides',$result->message);
        $this->assertEquals('El camp user és obligatori.',$result->errors->user[0]);
        $this->assertEquals('end at ha de ser una data posterior a start at.',$result->errors->end_at[0]);
    }

    public function regular_user_cannot_add_substitute_to_job()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $this->actingAs($user,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        $response = $this->json('POST','/api/v1/job/' . $job->id . '/substitution', [
            'user' => $substitute->id,
            'start_at' => Carbon::now()
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_modify_substitution()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', [
            'start_at' => $date = Carbon::now()->subDays(10)->toDateString(),
            'end_at' => $date = Carbon::now()->toDateString()
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function can_modify_substitution_validation()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);
        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', []);
        $response->assertStatus(422);
    }

    /** @test */
    public function can_modify_substitution_date_validation()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);
        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', [
            'start_at' => Carbon::now()->addDays(10)->toDateString(),
            'end_at' => Carbon::now()->toDateString()
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function can_modify_substitution()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        Employee::create([
            'user_id' => $substitute->id,
            'job_id' => $job->id
        ]);

        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', [
            'user_id' => $substitute->id,
            'start_at' => $start = Carbon::now()->subDays(10)->toDateTimeString(),
            'end_at' => $end = Carbon::now()->toDateTimeString()
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $employee = Employee::where('user_id', $substitute->id )->where('job_id', $job->id)->first();
//        dump(json_encode($employee));
//        dump($result);
        $this->assertNotNull($employee);
        $this->assertEquals($start, $result->start_at);
        $this->assertEquals($end, $result->end_at);
    }

    /** @test */
    public function can_modify_substitution_with_dates_without_time()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        Employee::create([
            'user_id' => $substitute->id,
            'job_id' => $job->id
        ]);

        $now = Carbon::now();
        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', [
            'user_id' => $substitute->id,
            'start_at' => $start = Carbon::now()->subDays(10)->toDateString(),
            'end_at' => $end = $now->toDateString()
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $employee = Employee::where('user_id', $substitute->id )->where('job_id', $job->id)->first();

        $this->assertNotNull($employee);

        $this->assertFalse(ends_with('00:00:00',$result->start_at));
        $this->assertTrue(starts_with($result->start_at, $start));
        $this->assertTrue(starts_with($result->end_at, $end));

        $this->assertTrue( (new Carbon($start . ' ' . $now->toTimeString()))->diffInSeconds(new Carbon($result->start_at)) < 2);
        $this->assertTrue( (new Carbon($end . ' ' . $now->toTimeString()))->diffInSeconds(new Carbon($result->end_at)) < 2);
//        $this->assertEquals($start . ' ' . $now->toTimeString(), $result->start_at );
//        $this->assertEquals($end . ' ' . $now->toTimeString(), $result->end_at);
    }

    /** @test */
    public function can_unset_end_date()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        Employee::create([
            'user_id' => $substitute->id,
            'job_id' => $job->id,
            'start_at' => $start = Carbon::now()->subDays(10)->toDateString(),
            'end_at' => $start = Carbon::now()->subDays(1)->toDateString(),
        ]);

        $now = Carbon::now();
        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', [
            'user_id' => $substitute->id,
            'start_at' => $start = Carbon::now()->subDays(5)->toDateString()
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $employee = Employee::where('user_id', $substitute->id )->where('job_id', $job->id)->first();

        $this->assertNotNull($employee);

        $this->assertFalse(ends_with('00:00:00',$result->start_at));
        $this->assertTrue(starts_with($result->start_at, $start));
        $this->assertNull($result->end_at);

        $this->assertTrue( (new Carbon($start . ' ' . $now->toTimeString()))->diffInSeconds(new Carbon($result->start_at)) < 2);

    }

    /** @test */
    public function can_finish_substitution()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        Employee::create([
            'user_id' => $substitute->id,
            'job_id' => $job->id,
            'start_at' => $start = Carbon::now()->subDays(10)->toDateTimeString()
        ]);

        $response = $this->json('PUT','/api/v1/job/' . $job->id . '/substitution', [
            'user_id' => $substitute->id,
            'end_at' => $end = Carbon::now()->toDateTimeString()
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $employee = Employee::where('user_id', $substitute->id )->where('job_id', $job->id)->first();

        $this->assertNotNull($employee);
        $this->assertEquals($start, $result->start_at);
        $this->assertEquals($end, $result->end_at);
    }

    /** @test */
    public function can_remove_substitution()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        $employee = Employee::create([
            'user_id' => $substitute->id,
            'job_id' => $job->id,
            'start_at' => $start = Carbon::now()->subDays(10)->toDateTimeString(),
            'end_at' => $start = Carbon::now()->addDays(5)->toDateTimeString()
        ]);

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $response = $this->json('DELETE','/api/v1/job/' . $job->id . '/substitution/' . $substitute->id );
        $response->assertSuccessful();
        $result = json_decode($response->getContent());

        $employee = $employee->fresh();
        $this->assertNull($employee);

        $job = $job->fresh();
        $this->assertCount(0,$job->substitutes);

        $employee = Employee::where('user_id', $substitute->id )->where('job_id', $job->id)->first();

        $this->assertNull($employee);
    }

    /** @test */
    public function regular_user_cannot_remove_substitution()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $substitute = factory(User::class)->create();

        Employee::create([
            'user_id' => $substitute->id,
            'job_id' => $job->id,
            'start_at' => $start = Carbon::now()->subDays(10)->toDateTimeString(),
            'end_at' => $start = Carbon::now()->addDays(5)->toDateTimeString()
        ]);

        $job = $job->fresh();
        $this->assertCount(1,$job->substitutes);

        $response = $this->json('DELETE','/api/v1/job/' . $job->id . '/substitution/' . $substitute->id );
        $response->assertStatus(403);
    }
}
