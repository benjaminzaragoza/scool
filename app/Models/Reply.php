<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reply
 * @package App\Models
 */
class Reply extends Model
{
    use FormattedDates;

    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function repliable()
    {
        return $this->morphTo();
    }

    /**
     * Return associated incident.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function incident()
    {
        return $this->repliable();
    }

    /**
     * Get the user that owns the reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array
     */
    public function map()
    {
        return [
            'body' => $this->body,
            'user_id' => $this->user_id,
            'user_name' => optional($this->user)->name,
            'user_email' => optional($this->user)->email,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
        ];
    }

    /**
     * Map collection.
     *
     * @param $replies
     * @return mixed
     */
    public static function mapCollection($replies)
    {
        return $replies->map(function($reply) {
            return $reply->map();
        });
    }
}
