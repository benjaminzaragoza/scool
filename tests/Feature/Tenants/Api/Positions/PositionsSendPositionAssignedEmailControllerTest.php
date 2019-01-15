<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Mail\Positions\PositionAssigned;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PositionsSendPositionAssignedEmailControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class PositionsSendPositionAssignedEmailControllerTest extends BaseTenantTest {

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
     * @group positions
     */
    public function can_send_positions_assigned_email()
    {
        $this->loginAsSuperAdmin('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user->assignPosition($position);

        Mail::fake();

        $response = $this->json('GET','/api/v1/positions/' . $position->id . '/email/send');
        $response->assertSuccessful();
        Mail::assertSent(PositionAssigned::class, function ($mail) use ($position,$user) {
            return $position->is($mail->position) &&
                $mail->hasTo($user->email);
        });
    }

    /**
     * @test
     * @group positions
     */
    public function can_send_positions_assigned_email_to_multiple_users()
    {
        $this->loginAsSuperAdmin('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user->assignPosition($position);
        $user2->assignPosition($position);

        Mail::fake();

        $response = $this->json('GET','/api/v1/positions/' . $position->id . '/email/send');
        $response->assertSuccessful();
        Mail::assertSent(PositionAssigned::class, 2);
    }

    /**
     * @test
     * @group positions
     */
    public function manager_can_send_positions_assigned_email()
    {
        $this->loginAsPositionsManager('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user->assignPosition($position);

        Mail::fake();

        $response = $this->json('GET','/api/v1/positions/' . $position->id . '/email/send');
        $response->assertSuccessful();
        Mail::assertSent(PositionAssigned::class, function ($mail) use ($position,$user) {
            return $position->is($mail->position) &&
                $mail->hasTo($user->email);
        });
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_send_positions_assigned_email()
    {
        $this->login('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user->assignPosition($position);

        $response = $this->json('GET','/api/v1/positions/' . $position->id . '/email/send');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_send_positions_assigned_email()
    {
        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user->assignPosition($position);

        $response = $this->json('GET','/api/v1/positions/' . $position->id . '/email/send');
        $response->assertStatus(401);
    }
}
