<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowsData extends Model
{
    protected $guarded = [
        'rule_section',
        'rule_group',
        'rule_reference',
        'rule_title',
        'rule_manual_reference',
        'rule_chapter'
    ];

    public function requirementData()
    {
        return $this->belongsTo(RequirementsData::class);
    }
}
