<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyField extends Model
{
    protected  $fillable = [
        'file_name',
        'description',
        'company_id',
    ];

    public function data()
    {
        return $this->hasMany(CompanyFieldsData::class, 'version_id', 'id');
    }

}
