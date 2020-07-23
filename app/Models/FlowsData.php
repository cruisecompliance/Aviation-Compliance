<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowsData extends Model
{
    protected $guarded = [];

    public function requirementData()
    {
        return $this->belongsTo(RequirementsData::class);
    }
}
