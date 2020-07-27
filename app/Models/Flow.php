<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    protected $guarded = [];

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function flowData() {
        return $this->hasMany(FlowsData::class);
    }

}
