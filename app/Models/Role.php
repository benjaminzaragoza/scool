<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role: Decorator of Spatie Role.
 *
 * @package App\Models
 */
class Role extends SpatieRole
{
    use FormattedDates, ApiURI;

    public $guarded = [];

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'api_uri' => $this->api_uri,

            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
        ];
    }
}
