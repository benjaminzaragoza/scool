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
        if ($user instanceof User) {
            $this->user()->associate($user);
            return $this;
        } else if (is_integer($user)) {
            $this->user()->associate(User::findOrFail($user));
            return $this;
        }
        throw new \InvalidArgumentException("L'usuari especificat no és correcte");
    }

    /**
     * Get the user that owns the incident.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
