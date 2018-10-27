<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reply
 * @package App\Models
 */
class Reply extends Model
{
    protected $guarded = [];

    /**
     * Get all of the owning commentable models.
     */
    public function repliable()
    {
        return $this->morphTo();
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
            'user_email' => optional($this->user)->email
        ];
    }
}
