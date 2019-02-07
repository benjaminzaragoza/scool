<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Identifier.
 *
 * @package Acacha\Relationships\Models
 */
class Identifier extends Model
{
    protected $guarded = [];

    /**
     * The relationships to eager load.
     *
     * @var array
     */
    public $with = ['type'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type_name'];

    /**
     * Get the identifier's type.
     */
    public function type()
    {
        return $this->belongsTo(IdentifierType::class);
    }

    /**
     * Get the type name.
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return optional($this->type)->name;
    }

    /**
     * Get the person that owns the identifier.
     */
    public function person()
    {
        return $this->hasOne(Person::class);
    }

}
