<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Cache;
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

    /**
     * Update all module settings.
     * 
     * @param Request $request
     * @param $tenant
     * @param $module
     */
    public function update(Request $request, $tenant, $module)
    {
        foreach ($request->settings as $settingKey => $settingValue) {
            if (!starts_with($settingKey,$module)) continue;
            if ($setting = Setting::where('key',$settingKey)->first()) {
                $setting->value = $settingValue;
                $setting->save();
                Cache::forget('setting_' . $setting->key);
            }
        }
    }
}
