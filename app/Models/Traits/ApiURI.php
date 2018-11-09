<?php

namespace App\Models\Traits;

/**
 * Class ApiURI.
 *
 * @package App\Models\Traits
 */
trait ApiURI
{
    /**
     * formatted_created_at_date attribute.
     *
     * @return mixed
     */
    public function getApiURIAttribute()
    {
        return strtolower(snake_case(str_plural(class_basename($this))));
    }

}
