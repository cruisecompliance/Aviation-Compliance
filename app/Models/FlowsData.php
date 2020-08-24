<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowsData extends Model
{
    protected $guarded = [];

    protected $dates = [
        'due_date',
        'effectiveness_review_date',
        'response_date',
        'extension_due_date',
        'closed_date'
    ];

    public function requirementData()
    {
        return $this->belongsTo(RequirementsData::class);
    }
}
