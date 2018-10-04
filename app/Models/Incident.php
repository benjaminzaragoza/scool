<?php

namespace App\Models;

use App\Http\Resources\Tenant\IncidentCollection;
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

    /**
     * Map.
     *
     * @return array
     */
    public function map()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' => optional($this->user)->name,
            'subject' => $this->subject,
            'description' => $this->description
        ];
    }

    /**
     * Get incidents.
     *
     * @return mixed
     */
    public static function getIncidents()
    {
        return (new IncidentCollection(Incident::with('user')->get()))->transform();
    }
}
