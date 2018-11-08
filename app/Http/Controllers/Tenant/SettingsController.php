<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Setting;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Tenant
 */
class SettingsController extends Controller
{
    public function index()
    {
        dump('REAL:');
        dump('KEY: incidents_manager_email');
        dump('VALUE:' . Setting::get('incidents_manager_email'));
        dump('DATABASE:');
        foreach (Setting::all() as $setting) {
            dump('KEY: ' . $setting->key);
            dump('VALUE: ' . $setting->value);
            dump('Role: ' . $setting->role);
        }
    }
}
