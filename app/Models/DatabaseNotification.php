<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification as LaravelDatabaseNotification;

/**
 * Class DatabaseNotification.
 *
 * @package Acacha\Relationships\Models
 */
class DatabaseNotification extends LaravelDatabaseNotification
{
    use FormattedDates;

    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * formatted_created_at_date attribute.
     *
     * @return mixed
     */
    public function getApiURIAttribute()
    {
        return 'notifications';
    }

    /**
     * Map.
     */
    public function mapSimple()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'notifiable_type' => $this->notifiable_type,
            'notifiable_id' => $this->notifiable_id,
            'data' => $this->data,
            'read_at' => $this->read_at,
            'read_at_timestamp' => $this->read_at_timestamp,
            'read_at_formatted' => $this->read_at_formatted,
            'read_at_diff' => $this->read_at_diff,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'api_uri' => $this->api_uri,
        ];
    }

    /**
     * Map.
     */
    public function map()
    {
        $notification = [
            'id' => $this->id,
            'type' => $this->type,
            'notifiable' => $this->notifiable,
            'notifiable_type' => $this->notifiable_type,
            'notifiable_id' => $this->notifiable_id,
            'data' => $this->data,
            'read_at' => $this->read_at,
            'read_at_timestamp' => $this->read_at_timestamp,
            'read_at_formatted' => $this->read_at_formatted,
            'read_at_diff' => $this->read_at_diff,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'api_uri' => $this->api_uri,
        ];
        if ($this->notifiable_type === User::class) {
            $notification['user_hashid'] = $this->notifiable->hash_id;
            $notification['user_name'] = $this->notifiable->name;
        }

        return $notification;
    }

    /**
     * email_verified_at_formatted attribute.
     *
     * @return mixed
     */
    public function getReadAtFormattedAttribute()
    {
        return optional($this->read_at)->format('h:i:sA d-m-Y');
    }

    /**
     * read_at_diff attribute.
     *
     * @return mixed
     */
    public function getReadAtDiffAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        return optional($this->read_at)->diffForHumans(Carbon::now());
    }

    /**
     * read_at_timestamp attribute.
     *
     * @return mixed
     */
    public function getReadAtTimestampAttribute()
    {
        return optional($this->read_at)->timestamp;
    }

}
