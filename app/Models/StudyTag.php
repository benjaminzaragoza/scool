<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StudyTag.
 *
 * @package App\Models
 */
class StudyTag extends Model
{
    use FormattedDates, ApiURI;

    protected $guarded = [];

    public function map()
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'api_uri' => $this->api_uri
        ];
    }
}
