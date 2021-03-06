<?php

namespace App\Http\Controllers\Auth\Tenant;

use App\Http\Controllers\Controller;
use App\Models\FacebookUser;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Redirect;
use Socialite;

/**
 * Class LoginController.
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('tenants.welcome', [
            'action' => 'login'
        ]);
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleFacebookProviderCallback()
    {
        $facebookUser = $this->findOrCreateFacebookUser(Socialite::driver('facebook')->user());

        $user = $this->findOrCreateUser($facebookUser);
        $facebookUser->assignUser($user);
        Auth::login($user, true);

        return Redirect::to('home');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($facebookUser)
    {
        if ($authUser = User::where('email', $facebookUser->email)->first()) {
            return $authUser;
        }

        return User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'password' => str_random()
        ]);
    }

    /**
     * Return facebook user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return FacebookUser
     */
    private function findOrCreateFacebookUser($facebookUser)
    {
        if ($user = FacebookUser::where('facebook_id', $facebookUser->id)->first()) {
            $user = $this->updateFacebookUser($user,$facebookUser);
            return $user;
        }

        return FacebookUser::create([
            'facebook_id' => $facebookUser->id,
            'token' => $facebookUser->token,
            'refreshToken' => $facebookUser->refreshToken,
            'nickname' => $facebookUser->nickname,
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'avatar_original' => $facebookUser->avatar_original,
            'profileUrl' => $facebookUser->profileUrl
        ]);
    }

    /**
     * Update facebook user.
     *
     * @param $facebookUser
     * @return
     */
    private function updateFacebookUser($user, $facebookUser)
    {
        $user->token = $facebookUser->token;
        $user->refreshToken = $facebookUser->refreshToken;
        $user->nickname = $facebookUser->nickname;
        $user->name = $facebookUser->name;
        $user->email = $facebookUser->email;
        $user->avatar_original = $facebookUser->avatar_original;
        $user->profileUrl = $facebookUser->profileUrl;
        $user->update();
        return $user->fresh();
    }
}
