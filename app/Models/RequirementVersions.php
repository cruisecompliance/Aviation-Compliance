<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequirementVersions extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'version_id',
    ];

    /**
     *  Get the requirement for the requirement version.
     */
    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }
}
