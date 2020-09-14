<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
//    public $timestamps = false;

    protected $fillable = [
        'name','params', 'user_id'
    ];

}
