<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
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

    protected $casts = [
        'due_date'  => 'date:d.m.Y',
        'effectiveness_review_date'  => 'date:d.m.Y',
        'response_date'  => 'date:d.m.Y',
        'extension_due_date'  => 'date:d.m.Y',
        'closed_date'  => 'date:d.m.Y',
    ];


//    public function setDueDateAttribute($value)
//    {
//        if(!empty($value)){
//            $this->attributes['due_date'] = Carbon::parse($value)->format('Y-m-d');
//        } else {
//            $this->attributes['due_date'] = null;
//        }
//    }


//    public function requirementData()
//    {
//        return $this->belongsTo(RequirementsData::class);
//    }

    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id', 'id');
    }

    public function auditee()
    {
        return $this->belongsTo(User::class, 'auditee_id', 'id');
    }

    public function investigator()
    {
        return $this->belongsTo(User::class, 'investigator_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'rule_id', 'id');
    }

}
