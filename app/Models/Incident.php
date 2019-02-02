<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use App\Models\Role as ScoolRole;

/**
 * Class Incident.
 *
 * @package App\Models
 */
class Incident extends Model
{
    use FormattedDates, ApiURI;

    const MANAGER_ROLE = ScoolRole::INCIDENTS_MANAGER['name'];

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
     * managerRole.
     *
     * @return string
     */
    public function managerRole() {
        return self::MANAGER_ROLE;
    }

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
    public function map($changelog=true)
    {
        $incidentMapped= [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => optional($this->user)->name,
            'user_email' => optional($this->user)->email,
            'user_hashid' => optional($this->user)->hashid,
            'subject' => $this->subject,
            'description' => $this->description,
            'closed_at' => $this->closed_at,
            'closed_by' => $this->closed_by,
            'closer_id' => optional($this->closer)->id,
            'closer_name' => optional($this->closer)->name,
            'closer_email' => optional($this->closer)->email,
            'closer_hashid' => optional($this->closer)->hashid,
            'formatted_closed_at' => $this->formatted_closed_at,
            'formatted_closed_at_diff' => $this->formatted_closed_at_diff,
            'closed_at_timestamp' => $this->closed_at_timestamp,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'api_uri' => $this->api_uri,
            'comments' => map_collection($this->comments),
            'tags' => map_collection($this->tags),
            'assignees' => map_collection($this->assignees),
        ];
        if ($changelog) {
            $incidentMapped['changelog'] = map_collection($this->changelog);
        }
        return $incidentMapped;
    }

    /**
     * Get all changes associated to loggable Incident (changelog).
     */
    public function changelog()
    {
        return $this->morphMany(Log::class, 'loggable');
    }


    /**
     * Get incidents.
     *
     * @return mixed
     */
    public static function getIncidents()
    {
        return map_collection(Incident::with(
            'user',
            'comments',
            'comments.user',
            'closer',
            'tags',
            'assignees',
            'replies',
            'changelog',
            'changelog.loggable.user',
            'changelog.loggable.closer',
            'changelog.loggable.comments',
            'changelog.loggable.tags',
            'changelog.loggable.assignees'
        )->get());
    }

    /**
     * Close incident.
     *
     * @return $this
     */
    public function close()
    {
        $this->closed_at = Carbon::now();
        $this->closed_by = optional(Auth::user())->id;
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
        $this->closed_by = null;
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

    /**
     * formatted_closed_at_date attribute.
     *
     * @return mixed
     */
    public function getFormattedClosedAtDiffAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        return optional($this->closed_at)->diffForHumans(Carbon::now());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->morphMany(Reply::class, 'repliable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->replies();
    }

    /**
     * @param $reply
     */
    public function addReply($reply)
    {
        $this->replies()->save($reply);
    }

    /**
     * @param $reply
     */
    public function addComment($reply)
    {
        $this->addReply($reply);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class,'assignees')->withTimestamps();
    }

    /**
     * Add assignee.
     *
     * @param $assignee
     */
    public function addAssignee($assignee)
    {
        $this->assignees()->save($assignee);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(IncidentTag::class,'tagged_incidents')->withTimestamps();
    }

    /**
     * Add tag.
     *
     * @param $tag
     */
    public function addTag($tag)
    {
        $this->tags()->save($tag);
    }

    /**
     * Get users with role Incidents
     */
    public static function userWithRoleIncidents()
    {
        try {
            return User::role('Incidents')->get();
        } catch (RoleDoesNotExist $e) {
            return collect([]);
        }
    }

    /**
     * Get users with role incident
     */
    public static function userWithRoleIncidentsManager()
    {
        try {
            return User::role(self::MANAGER_ROLE)->with('googleUser')->get();
        } catch (RoleDoesNotExist $e) {
            return collect([]);
        }
    }

    /**
     * Get users with role incident
     */
    public static function usersWithIncidentsRoles()
    {
        try {
            return User::role([self::MANAGER_ROLE,'Incidents'])->get();
        } catch (RoleDoesNotExist $e) {
            return collect([]);
        }
    }

    /**
     * Get the user that closes the incident.
     */
    public function closer()
    {
        return $this->belongsTo(User::class,'closed_by');
    }

    /**
     * @return string
     */
    public function link() {
        return '<a target="_blank" href="/' . $this->apiURI . '/' . $this->id . '">' . $this->subject . '</a>';
    }
}
