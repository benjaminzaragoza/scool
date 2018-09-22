<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\UnAssociateGsuiteUserToUser;
use App\Models\GoogleUser;
use App\Http\Requests\AssociateGsuiteUserToUser;
use App\Http\Requests\GetUser;
use App\Models\User;

/**
 * Class UserGsuiteController.
 *
 * @package App\Http\Controllers
 */
class UserGsuiteController extends Controller
{

    protected $repository;

    /**
     * @param GetUser $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
    }

    /**
     * Destroy.
     * 
     * @param UnAssociateGsuiteUserToUser $request
     * @param $tenant
     * @param User $user
     */
    public function destroy(UnAssociateGsuiteUserToUser $request, $tenant, User $user)
    {
        if ($existing = GoogleUser::where('user_id', $user->id)->first()) {
            GoogleUser::destroy($existing->id);
        }
    }
}
