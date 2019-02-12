<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\Google\EditGoogleUsers;
use App\Http\Requests\Google\UnAssociateGsuiteUserToUser;
use App\Models\GoogleUser;
use App\Http\Requests\AssociateGsuiteUserToUser;
use App\Models\User;
use Google_Service_Exception;

/**
 * Class UserGsuiteController.
 *
 * @package App\Http\Controllers
 */
class UserGsuiteController extends Controller
{

    protected $repository;

    /**
     * @param AssociateGsuiteUserToUser $request
     * @param $tenant
     * @param User $user
     * @return void
     */
    public function store(AssociateGsuiteUserToUser $request, $tenant, User $user)
    {
        if  ($existing = GoogleUser::where('user_id',$user->id)->first()) {
            GoogleUser::destroy($existing->id);
        }
        GoogleUser::create([
            'user_id' => $user->id,
            'google_id' => $request->google_id,
            'google_email' => $request->google_email,
        ]);

        // TODO edit google user to modify employeeId == user->id i emailPersonal => user.email
    }

    /**
     * Update.
     *
     * @param EditGoogleUsers $request
     * @param $tenant
     * @param User $user
     * @return array
     */
    public function update(EditGoogleUsers $request, $tenant, User $user)
    {
        if (!$user->googleUser) abort (422, "L'usuari $user->name no té un compte de Google associat");
        dd($user->googleUser->google_email);
        if (!google_user_exists($googleEmail = $user->googleUser->google_email)) abort('422',
            "No existeix el compte de Google $googleEmail");
        try {
            $nameArray = explode(" ", $user->name);
            $givenName = $nameArray[0];
            if ($user->person) $givenName =  $user->person->givenName;
            $familyName = implode(" ",array_splice($nameArray,1,count($nameArray)-1));
            if ($user->person) $familyName = fullsurname($user->person->sn1,$user->person->sn2);
            return get_object_vars ((new GoogleDirectory())->editUser([
                'primaryEmail' => $user->googleUser->google_email,
                'givenName' => $givenName ,
                'familyName' => $familyName,
                'fullName' => $user->name,
                'hashFunction' => 'SHA-1',
                'password' => $user->password,
                'secondaryEmail' => $user->email,
                'mobile' => $user->mobile,
                'id' => $user->id
            ]));
        } catch (Google_Service_Exception $e) {
            dd($e);
            abort('422',$e);
        }
    }

    /**
     * Destroy.
     *
     * @param UnAssociateGsuiteUserToUser $request
     * @param $tenant
     * @param $userid
     */
    public function destroy(UnAssociateGsuiteUserToUser $request, $tenant, $userid)
    {
        $user = GoogleUser::where('user_id', $userid)->first();
        if ($user) $user->delete();
    }
}
