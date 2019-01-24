<?php

namespace App\Http\Controllers\Tenant\Api\Moodle\Users;

use App\Events\Moodle\MoodleUserAssociated;
use App\Events\Moodle\MoodleUserUnAssociated;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Moodle\Users\AssociateMoodleUserToUser;
use App\Http\Requests\Moodle\Users\MoodleUserUpdate;
use App\Http\Requests\Moodle\Users\UnassociateMoodleUserToUser;
use App\Models\MoodleUser;
use App\Models\User;

/**
 * Class UserMoodleController.
 *
 * @package App\Http\Controllers
 */
class UserMoodleController extends Controller
{

    protected $repository;

    /**
     * Store.
     *
     * @param AssociateMoodleUserToUser $request
     * @param $tenant
     * @param User $user
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(AssociateMoodleUserToUser $request, $tenant, User $user)
    {
        if  ($existing = MoodleUser::where('user_id',$user->id)->first()) {
            MoodleUser::destroy($existing->id);
        }
        $moodleUser = MoodleUser::create([
            'user_id' => $user->id,
            'moodle_id' => $request->moodle_id,
            'moodle_username' => $user->email,
        ]);
        event(new MoodleUserAssociated($user, $moodleUser));
    }

    /**
     * Update. (sync)
     *
     * @param MoodleUserUpdate $request
     * @param $tenant
     * @param User $user
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(MoodleUserUpdate $request, $tenant, User $user)
    {
        if (!$user->moodleUser) abort (422, "L'usuari $user->name no té un compte de Moodle associat");
        MoodleUser::sync($user->moodleUser->moodle_id, $user);
    }

    /**
     * Destroy.
     *
     * @param UnassociateMoodleUserToUser $request
     * @param $tenant
     * @param $userId
     */
    public function destroy(UnassociateMoodleUserToUser $request, $tenant, $userId)
    {
        $moodleUser = MoodleUser::where('user_id', $userId)->firstOrFail();
        $moodleUser->delete();
        event(new MoodleUserUnAssociated(User::findOrFail($userId), $moodleUser->moodle_id));
    }
}
