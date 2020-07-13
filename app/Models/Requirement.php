<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
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
        return $this->hasOne(RequirementVersions::class);
    }


}
