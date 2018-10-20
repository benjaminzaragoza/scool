<?php

namespace App\Models;

use App\Http\Resources\Tenant\IncidentCollection;
use App\Models\Traits\FormattedDates;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Incident.
 *
 * @package App\Models
 */
class Incident extends Model
{
    use FormattedDates;

    protected $guarded = ['user_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'closed_at'
    ];

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
            'user_email' => optional($this->user)->email,
            'subject' => $this->subject,
            'description' => $this->description,
            'closed_at' => $this->closed_at,
            'formatted_closed_at' => $this->formatted_closed_at,
            'closed_at_timestamp' => $this->closed_at_timestamp,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at
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

    /**
     * Close incident.
     *
     * @return $this
     */
    public function close()
    {
        $this->closed_at = Carbon::now();
        $this->save();
        return $this;
    }

    /**
     * Open incident.
     *
     * @return $this
     */
    public function open()
    {
        $this->closed_at = null;
        $this->save();
        return $this;
    }

    /**
     * formatted_closed_at_date attribute.
     *
     * @return mixed
     */
    public function getFormattedClosedAtAttribute()
    {
        return optional($this->closed_at)->format('h:i:sA d-m-Y');
    }

    /**
     * closed_at_timestamp attribute.
     *
     * @return mixed
     */
    public function getClosedAtTimestampAttribute()
    {
        return optional($this->closed_at)->timestamp;
    }
}
