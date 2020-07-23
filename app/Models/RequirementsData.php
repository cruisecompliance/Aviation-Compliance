<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequirementsData extends Model
{
    protected $table = 'requirements_data';

    protected $fillable = [
        'rule_section',
        'rule_group',
        'rule_reference',
        'rule_title',
        'rule_manual_reference',
        'rule_chapter',
        'version_id',
    ];

    /**
     * Get the version record associated with the requirement.
     */
    public function version()
    {
        return $this->hasOne(Requirement::class, 'id', 'version_id');
    }

    public function flowsData()
    {
        return $this->hasMany(FlowsData::class);
    }


}
