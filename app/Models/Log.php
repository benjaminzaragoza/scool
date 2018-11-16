<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log.
 *
 * @package App
 */
class Log extends Model
{
    protected $guarded = [];

    protected $appends = ['timestamp','formatted_time','human_time','action','module'];

    protected $with = ['user'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'time'
    ];

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
            'user' => $this->user,
            'text' => $this->text,
            'time' => $this->time,
            'human_time' => $this->human_time,
            'timestamp' => $this->timestamp,
            'action_type' => $this->action_type,
            'action' => $this->action,
            'module_type' => $this->module_type,
            'module' => $this->module,
            'icon' => $this->icon,
            'color' => $this->color,
        ];
    }

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * formatted_time attribute.
     *
     * @return mixed
     */
    public function getFormattedTimeAttribute()
    {
        return optional($this->time)->format('h:i:sA d-m-Y');
    }

    /**
     * created_at_timestamp attribute.
     *
     * @return mixed
     */
    public function getTimestampAttribute()
    {
        return optional($this->time)->timestamp;
    }

    /**
     * human_time attribute.
     *
     * @return mixed
     */
    public function getHumanTimeAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        return optional($this->time)->diffForHumans(Carbon::now());
    }

    /**
     * human_time attribute.
     *
     * @return mixed
     */
    public function getActionAttribute()
    {
        return (Object) [
            'name' => $this->action_type,
            'text' => self::actionText($this->action_type),
            'icon' => self::actionIcon($this->action_type)
        ];
    }

    /**
     * actionText.
     *
     * @param $action
     * @return string
     */
    public static function actionText($action)
    {
        switch ($action) {
            case 'store':
                return 'Creació';
            case 'update':
                return 'Edició';
            case 'destroy':
                return 'Eliminació';
        }
    }

    /**
     * actionIcon
     * @param $action
     * @return string
     */
    public static function actionIcon($action)
    {
        switch ($action) {
            case 'store':
                return 'add';
            case 'update':
                return 'edit';
            case 'destroy':
                return 'delete';
        }
    }

    /**
     * human_time attribute.
     *
     * @return mixed
     */
    public function getModuleAttribute()
    {
        return (Object) [
            'name' => $this->module_type,
            'text' => self::moduleText($this->module_type),
            'icon' => self::moduleIcon($this->module_type)
        ];
    }

    /**
     * moduleText.
     *
     * @param $module
     * @return string
     */
    public static function moduleText($module)
    {
        switch ($module) {
            case 'Incidents':
                return 'Incidències';
        }
    }

    /**
     * moduleIcon
     * @param $module
     * @return string
     */
    public static function moduleIcon($module)
    {
        switch ($module) {
            case 'Incidents':
                return 'build';
        }
    }
}
