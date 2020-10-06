<?php

namespace App\Models;

use App\Enums\RequrementStatus;
use App\Enums\RoleName;
use App\Http\Requests\Flows\RequirementRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'due_date' => 'date:d.m.Y',
        'effectiveness_review_date' => 'date:d.m.Y',
        'response_date' => 'date:d.m.Y',
        'extension_due_date' => 'date:d.m.Y',
        'closed_date' => 'date:d.m.Y H:i:s',
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

    /**
     * Update task data with role permission
     *
     * @param Flow $flow
     * @param RequirementRequest $request
     * @return FlowsData
     */
    public static function store(Flow $flow, RequirementRequest $request): FlowsData
    {
        // find task (flowData) for update
        $task = FlowsData::whereFlowId($flow->id)
            ->whereRuleReference($request->requirements_rule)
            ->first();

        // get role name from Auth::user()
        $roleName = Auth::user()->roles()->first()->name;

        // update task data if CMM or SME
        if ($roleName == RoleName::COMPLIANCE_MONITORING_MANAGER || $roleName == RoleName::SME) {
            $task->company_manual = $request->company_manual;
            $task->company_chapter = $request->company_chapter;
            $task->frequency = $request->frequency;
            $task->month_quarter = $request->month_quarter;
            $task->auditor_id = $request->auditor_id;
            $task->auditee_id = $request->auditee_id;
            $task->due_date = $request->due_date;
            $task->task_status = $request->task_status;
        }

        // update task data if AM or SME
        if ($roleName == RoleName::ACCOUNTABLE_MANAGER || $roleName == RoleName::SME) {
            $task->extension_due_date = $request->extension_due_date;
            $task->task_status = $request->task_status;
        }

        // update task data if Auditor or SME
        if ($roleName == RoleName::AUDITOR || $roleName == RoleName::SME) {
            $task->questions = $request->questions;
            $task->finding = $request->finding;
            $task->deviation_statement = $request->deviation_statement;
            $task->evidence_reference = $request->evidence_reference;
            $task->deviation_level = $request->deviation_level;
            $task->safety_level_before_action = $request->safety_level_before_action;
            $task->due_date = $request->due_date;
            $task->task_status = $request->task_status;
        }

        // update task data if Auditee or SME
        if ($roleName == RoleName::AUDITEE || $roleName == RoleName::SME) {
            $task->repetitive_finding_ref_number = $request->repetitive_finding_ref_number;
            $task->investigator_id = $request->investigator_id;
            $task->corrections = $request->corrections;
            $task->rootcause = $request->rootcause;
            $task->corrective_actions_plan = $request->corrective_actions_plan;
            $task->preventive_actions = $request->preventive_actions;
            $task->action_implemented_evidence = $request->action_implemented_evidence;
            $task->safety_level_after_action = $request->safety_level_after_action;
            $task->effectiveness_review_date = $request->effectiveness_review_date;
            $task->task_status = $request->task_status;
        }

        // update task data if Investigator or SME
        if ($roleName == RoleName::INVESTIGATOR || $roleName == RoleName::SME) {
            $task->safety_level_before_action = $request->safety_level_before_action;
            $task->due_date = $request->due_date;
            $task->repetitive_finding_ref_number = $request->repetitive_finding_ref_number;
            $task->investigator_id = $request->investigator_id;
            $task->corrections = $request->corrections;
            $task->rootcause = $request->rootcause;
            $task->corrective_actions_plan = $request->corrective_actions_plan;
            $task->preventive_actions = $request->preventive_actions;
            $task->action_implemented_evidence = $request->action_implemented_evidence;
            $task->task_status = $request->task_status;
        }

        // update closed_date (if status cmm_done)
        if ($request->task_status == RequrementStatus::CMM_Done) {
            $task->closed_date = Carbon::now();
        } else {
            $task->closed_date = NULL;
        }

        // update task data
        $task->update();

        // return task (FlowData)
        return $task;
    }
}
