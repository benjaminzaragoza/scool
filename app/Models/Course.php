<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course.
 *
 * @package App\Models
 */
class Course extends Model
{
    use FormattedDates, ApiURI;

    protected $guarded = [];

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'order' => (int) $this->order,
            'api_uri' => $this->api_uri,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff
        ];
    }
}
