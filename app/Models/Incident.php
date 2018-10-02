<?php

namespace App\Models;

use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Incident.
 *
 * @package App\Models
 */
class Incident extends Model
{
    protected $guarded = ['user_id'];

    /**
     * Assign user.
     *
     * @param $user
     * @return Incident
     */
    public function assignUser($user)
    {
        if (! $user instanceof User && ! is_integer($user) )
            throw new \InvalidArgumentException("L'usuari especificat no és correcte");
        if ($user instanceof User) {
            $this->user_id = $user->id;
        } else if (is_integer($user)) {
            $this->user_id = $user;
        }
        $this->save();
        return $this;

    }

    /**
     * Get the user that owns the incident.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
