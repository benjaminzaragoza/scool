<?php

namespace App\Http\Controllers\Tenant\Api\Changelog;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\ListChangelog;
use App\Http\Requests\Incidents\ListModuleChangelog;
use App\Models\Log;
use App\Models\Module;

/**
 * Class ChangelogModuleController.
 *
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class ChangelogModuleController extends Controller
{
    /**
     * @param ListChangelog $request
     * @return mixed
     */
    public function index(ListModuleChangelog $request, $tenant, Module $module)
    {
        return map_collection(Log::module($module->name)->get());
    }
}
