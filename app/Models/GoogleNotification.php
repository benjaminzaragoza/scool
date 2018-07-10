<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GoogleNotification.
 * @package App\Models
 */
class GoogleNotification extends Model
{
    protected $guarded = [];

    const GOOGLE_HEADER_CHANNEL_ID = 'x-goog-channel-id';
    const GOOGLE_HEADER_CHANNEL_TOKEN = 'x-goog-channel-token';
    const GOOGLE_HEADER_CHANNEL_EXPIRATION = 'x-goog-channel-expiration';
    const GOOGLE_HEADER_RESOURCE_STATE = 'x-goog-resource-state';
    const GOOGLE_HEADER_MESSAGE_NUMBER = 'x-goog-message-number';

    /**
     * Validate request.
     *
     * @param $request
     * @return bool
     */
    public static function validate($request)
    {
        dump(1);
        $type = self::getType($request);
        dump($type);
        if (!$type) return false;
        $watch = GoogleWatch::latest()->where('channel_id',self::getChannelId($request))->where('token',self::getToken($request))->first();
        if ( $watch && self::checkExpiration(self::getExpiration($request))) return true;
        return false;
    }

    /**
     * Check expiration.
     *
     * @param $expiration
     * @return boolean
     */
    public static function checkExpiration($expiration)
    {
        return Carbon::createFromTimestampMs($expiration)->lt(Carbon::now());
    }

    /**
     * Get expiration.
     *
     * @param $request
     */
    public static function getExpiration($request)
    {
        $request->header(self::GOOGLE_HEADER_CHANNEL_EXPIRATION,null);
    }

    /**
     * Get token.
     *
     * @param $request
     * @return mixed
     */
    public static function getToken($request)
    {
        return $request->header(self::GOOGLE_HEADER_CHANNEL_TOKEN,null);
    }

    /**
     * Get channel id.
     *
     * @param $request
     * @return mixed
     */
    public static function getChannelId($request)
    {
        return $request->header(self::GOOGLE_HEADER_CHANNEL_ID,null);
    }

    /**
     * Get notification type.
     *
     * @param $request
     * @return mixed
     */
    public static function getType($request)
    {
        return $request->header(self::GOOGLE_HEADER_RESOURCE_STATE,null);
    }
}
