<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Class FilteredSettingsController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class FilteredSettingsController extends Controller
{
    /**
     * List settings for a specific module.
     *
     * @param Request $request
     * @param $tenant
     * @param $module
     * @return Setting[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, $tenant, $module)
    {
        return Setting::all()->filter(function ($setting) use ($module) {
            return starts_with($setting->key, $module);
        });
    }
}
