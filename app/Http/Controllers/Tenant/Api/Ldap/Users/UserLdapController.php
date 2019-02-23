<?php

namespace App\Http\Controllers\Tenant\Api\Ldap\Users;

use App\Events\Ldap\LdapUserAssociated;
use App\Events\Ldap\LdapUserUnAssociated;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Ldap\Users\AssociateLdapUserToUser;
use App\Http\Requests\Ldap\Users\LdapUserUpdate;
use App\Http\Requests\Ldap\Users\UnassociateLdapUserToUser;
use App\Models\LdapUser;
use App\Models\User;

/**
 * Class UserLdapController.
 *
 * @package App\Http\Controllers
 */
class UserLdapController extends Controller
{

    protected $repository;

    /**
     * Store.
     *
     * @param AssociateLdapUserToUser $request
     * @param $tenant
     * @param User $user
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(AssociateLdapUserToUser $request, $tenant, User $user)
    {
        if  ($existing = LdapUser::where('user_id',$user->id)->first()) {
            LdapUser::destroy($existing->id);
        }
        $ldapUser = LdapUser::create([
            'user_id' => $user->id,
            'dn' => $request->dn
        ]);
        event(new LdapUserAssociated($user, $ldapUser));
    }

    /**
     * Update. (sync)
     *
     * @param LdapUserUpdate $request
     * @param $tenant
     * @param User $user
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(LdapUserUpdate $request, $tenant, User $user)
    {
        if (!$user->ldapUser) abort (422, "L'usuari $user->name no té un compte de Ldap associat");
        LdapUser::sync($user->ldapUser->cn, $user);
    }

    /**
     * Destroy.
     *
     * @param UnassociateLdapUserToUser $request
     * @param $tenant
     * @param $userId
     */
    public function destroy(UnassociateLdapUserToUser $request, $tenant, $userId)
    {
        $ldapUser = LdapUser::where('user_id', $userId)->firstOrFail();
        $ldapUser->delete();
        event(new LdapUserUnAssociated(User::findOrFail($userId), $ldapUser));
    }
}
