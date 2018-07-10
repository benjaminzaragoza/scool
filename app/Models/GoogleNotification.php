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
        $type = self::getType($request);
        if (!$type) return false;
        $watch = GoogleWatch::where('channel_type',$type)->order_by('created_at', 'desc')->first();
        if ( $watch->token === self::getToken($request) &&
             $watch->channel_id === self::getChannelId($request) &&
             self::checkExpiration(self::getExpiration($request))) return true;
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
     */
    public static function getToken($request)
    {
        $request->header(self::GOOGLE_HEADER_CHANNEL_TOKEN,null);
    }

    /**
     * Get channel id.
     *
     * @param $request
     */
    public static function getChannelId($request)
    {
        $request->header(self::GOOGLE_HEADER_CHANNEL_ID,null);
    }

    /**
     * Get notification type.
     *
     * @param $request
     */
    public static function getType($request)
    {
        $request->header(self::GOOGLE_HEADER_RESOURCE_STATE,null);
    }
}
