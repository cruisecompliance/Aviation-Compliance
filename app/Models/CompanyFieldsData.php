<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFieldsData extends Model
{
    public $timestamps = false;

    protected  $fillable = [
        'rule_reference',
        'company_manual',
        'company_chapter',
        'version_id',
    ];

}
