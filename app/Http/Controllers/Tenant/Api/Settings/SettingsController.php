<?php

namespace App\Http\Controllers\Tenant\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateSetting;
use App\Models\Setting;
use App\Tenant;
use Cache;
use Illuminate\Http\Request;

/**
 * Class SettingController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SettingsController extends Controller
{
    /**
     * Update setting.
     *
     * @param UpdateSetting $request
     * @param $tenant
     * @param Setting $setting
     */
    public function update(UpdateSetting $request, $tenant, Setting $setting)
    {
        $setting->value = $request->value;
        $setting->save();
        Cache::forget('setting_' . $setting->key);
    }
}
