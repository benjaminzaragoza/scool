<?php

namespace App\Http\Controllers\Tenant\Api\Changelog;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListModuleChangelog;
use App\Http\Requests\Changelog\ListUserChangelog;
use App\Models\Log;
use App\Models\Module;
use App\Models\User;

/**
 * Class ChangelogUserController.
 *
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class ChangelogUserController extends Controller
{
    /**
     * ListUserChangelog.
     *
     * @param ListUserChangelog $request
     * @param $tenant
     * @param User $user
     * @return mixed
     */
    public function index(ListUserChangelog $request, $tenant, User $user)
    {
        return map_collection(Log::fromUser($user)->get());
    }
}
