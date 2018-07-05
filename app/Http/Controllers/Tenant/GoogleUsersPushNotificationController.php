<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Log;

/**
 * Class GoogleUsersPushNotificationController.
 *
 * @package App\Http\Controllers\Tenant
 */
class GoogleUsersPushNotificationController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request->headers);
        $GoogleHeaders = collect($request->headers)->filter(function ($header, $key) {
            return starts_with($key, 'X-Goog-');
        });
        Log::info($GoogleHeaders);
        Log::info($request->input());
    }
}
