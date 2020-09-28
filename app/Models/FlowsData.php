<?php

namespace App\Models;

use App\User;
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
        'due_date'  => 'date:Y-m-d', // format for correct display in form input
        'effectiveness_review_date'  => 'date:Y-m-d', // format for correct display in form input
        'response_date'  => 'date:Y-m-d', // format for correct display in form input
        'extension_due_date'  => 'date:Y-m-d', // format for correct display in form input
        'closed_date'  => 'date:Y-m-d', // format for correct display in form input
    ];

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

    public static function checkAssignedUser(int $user_id, int $flow_id)
    {
        $data = self::query()
            ->orWhere('auditor_id', $user_id)
            ->orWhere('auditee_id', $user_id)
            ->orWhere('investigator_id', $user_id)
            ->where('flow_id', $flow_id)
            ->first();

        return ($data) ? true : false;
    }

}
