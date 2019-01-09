<?php

namespace Tests\Unit\Tenants;

use App\Models\FacebookUser;
use App\Models\Position;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class FacebookTest.
 *
 * @package Tests\Unit
 */
class FacebookUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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
    public function assignUser()
    {
        $facebookUser = FacebookUser::create([
            'facebook_id' => '10213078247690634',
            'token' => 'EAAFC8fYC4x0BAChKtiElTZA5vrZBgAPhBr4zZBtyjhLR2elantvQ0bWFZCT8uOaLNKqpzhf0P7Ow8XoGvTjaJmlqCu8j6g4a6ejc5ZCpzkV0ehlUKBhrrYNNiA9cbnUI2ZCc3FI9WWbhRYd49ZA2JsYtK1ebNcg',
            'refreshToken' => null,
            'nickname' => 'nickname',
            'name' => 'Sergi Tur Badenas',
            'email' => 'acacha@gmail.com',
            'avatar' =>'https://graph.facebook.com/v3.0/89748978984689798641/picture?type=normal',
            'avatar_original' =>'https://graph.facebook.com/v3.0/89748978984689798641/picture?width=1920',
            'profileUrl' => null
        ]);

        $this->assertNull($facebookUser->user);
        $user = factory(User::class)->create();
        $facebookUser->assignUser($user);
        $facebookUser = $facebookUser->fresh();
        $this->assertNotNull($facebookUser->user);
        $this->assertEquals($user->name, $facebookUser->user->name);
    }

    /**
     * Map.
     * @test
     * @group curriculum
     */
    public function map()
    {
        $facebook = FacebookUser::create([
            'facebook_id' => '10213078247690634',
            'token' => 'EAAFC8fYC4x0BAChKtiElTZA5vrZBgAPhBr4zZBtyjhLR2elantvQ0bWFZCT8uOaLNKqpzhf0P7Ow8XoGvTjaJmlqCu8j6g4a6ejc5ZCpzkV0ehlUKBhrrYNNiA9cbnUI2ZCc3FI9WWbhRYd49ZA2JsYtK1ebNcg',
            'refreshToken' => null,
            'nickname' => 'nickname',
            'name' => 'Sergi Tur Badenas',
            'email' => 'acacha@gmail.com',
            'avatar' =>'https://graph.facebook.com/v3.0/89748978984689798641/picture?type=normal',
            'avatar_original' =>'https://graph.facebook.com/v3.0/89748978984689798641/picture?width=1920',
            'profileUrl' => null
        ]);
        $facebookMap = $facebook->map();

        $this->assertSame(1,$facebookMap['id']);
        $this->assertSame('EAAFC8fYC4x0BAChKtiElTZA5vrZBgAPhBr4zZBtyjhLR2elantvQ0bWFZCT8uOaLNKqpzhf0P7Ow8XoGvTjaJmlqCu8j6g4a6ejc5ZCpzkV0ehlUKBhrrYNNiA9cbnUI2ZCc3FI9WWbhRYd49ZA2JsYtK1ebNcg',
            $facebookMap['token']);
        $this->assertNull($facebookMap['refreshToken']);
        $this->assertSame('nickname',$facebookMap['nickname']);
        $this->assertSame('Sergi Tur Badenas',$facebookMap['name']);
        $this->assertSame('acacha@gmail.com',$facebookMap['email']);
        $this->assertSame('https://graph.facebook.com/v3.0/89748978984689798641/picture?type=normal',$facebookMap['avatar']);
        $this->assertSame('https://graph.facebook.com/v3.0/89748978984689798641/picture?width=1920',$facebookMap['avatar_original']);
        $this->assertNull($facebookMap['profileUrl']);
    }
}
