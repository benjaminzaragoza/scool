<?php

namespace App\Http\Controllers\Tenant\Api\Moodle\Users;

use App\Events\Moodle\MoodleUserAssociated;
use App\Events\Moodle\MoodleUserUnAssociated;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Moodle\Users\AssociateMoodleUserToUser;
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
        ]);
        event(new MoodleUserAssociated($user, $moodleUser));
    }

    // TODO
//    /**
//     * Update. (sync)
//     *
//     * @param EditMoodleUsers $request
//     * @param $tenant
//     * @param User $user
//     * @return array
//     */
//    public function update(EditMoodleUsers $request, $tenant, User $user)
//    {
//        if (!$user->googleUser) abort (422, "L'usuari $user->name no té un compte de Google associat");
//        dd($user->googleUser->google_email);
//        if (!google_user_exists($googleEmail = $user->googleUser->google_email)) abort('422',
//            "No existeix el compte de Google $googleEmail");
//        try {
//            $nameArray = explode(" ", $user->name);
//            $givenName = $nameArray[0];
//            if ($user->person) $givenName =  $user->person->givenName;
//            $familyName = implode(" ",array_splice($nameArray,1,count($nameArray)-1));
//            if ($user->person) $familyName = fullsurname($user->person->sn1,$user->person->sn2);
//            return get_object_vars ((new GoogleDirectory())->editUser([
//                'primaryEmail' => $user->googleUser->google_email,
//                'givenName' => $givenName ,
//                'familyName' => $familyName,
//                'fullName' => $user->name,
//                'hashFunction' => 'SHA-1',
//                'password' => $user->password,
//                'secondaryEmail' => $user->email,
//                'mobile' => $user->mobile,
//                'id' => $user->id
//            ]));
//        } catch (Google_Service_Exception $e) {
//            dd($e);
//            abort('422',$e);
//        }
//    }

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
