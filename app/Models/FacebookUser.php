<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Facebook.
 *
 * @package App\Models
 */
class FacebookUser extends Model
{
    protected $guarded = [];

    /**
     * Map.
     *
     * @return array
     */
    public function map()
    {
        return [
            'id' => $this->id,
            'facebook_id' => $this->facebook_id,
            'token' => $this->token,
            'refreshToken' => $this->refreshToken,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'avatar_original' => $this->avatar_original,
            'profileUrl' => $this->profileUrl,
        ];
    }

    /**
     * Assign user.
     *
     * @param $user
     * @return $this
     */
    public function assignUser($user)
    {
        $this->user_id = $user->id;
        $this->save();
        return $this;
    }

    /**
     * Get the user record associated with the facebook user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
