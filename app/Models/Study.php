<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Study.
 *
 * @package App
 */
class Study extends Model
{
    protected $guarded = [];

    /**
     * Assign family.
     *
     * @param $family
     */
    public function assignFamily($family)
    {
        $family = is_object($family) ? $family : Family::where('code',$family)->firstorFail();
        $family->addStudy($this);
    }

    /**
     * Get the family record associated with the study.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

}
