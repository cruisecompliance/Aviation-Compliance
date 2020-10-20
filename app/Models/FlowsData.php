<?php

namespace App\Models;

use App\Enums\RequrementStatus;
use App\Enums\RoleName;
use App\Http\Requests\Flows\RequirementRequest;
use App\Services\Flows\FileUploadService;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable as AuditableTrait;


class FlowsData extends Model implements AuditableContract
{
    use AuditableTrait;

    protected $guarded = [];

    protected $dates = [
        'month_quarter',
        'due_date',
        'effectiveness_review_date',
        'response_date',
        'extension_due_date',
        'closed_date'
    ];

//    protected $casts = [
//        'month_quarter' => 'date:m.Y',
//        'due_date' => 'date:d.m.Y',
//        'effectiveness_review_date' => 'date:d.m.Y',
//        'response_date' => 'date:d.m.Y',
//        'extension_due_date' => 'date:d.m.Y',
//        'closed_date' => 'date:d.m.Y H:i:s',
//    ];


    // Auditable
    protected $auditExclude = [
        'id',
        'flow_id',
        'rule_section',
        'rule_group',
        'rule_reference',
        'rule_title',
        'rule_manual_reference',
        'rule_chapter',
    ];

    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        if (Arr::has($data, 'new_values.task_owner')) {
            $data['old_values']['task_owner'] = (User::find($this->getOriginal('task_owner'))->name) ??  NULL;
            $data['new_values']['task_owner'] = (User::find($this->getAttribute('task_owner'))->name) ??  NULL;
        }

        return $data;
    }


    /**
     * Set Month / Quarter Date format
     *
     * @param $value
     */
    public function setMonthQuarterAttribute($value)
    {
        if (!empty($value)) {
            $dateMonthArray = explode('.', $value);
            $month = $dateMonthArray[0];
            $year = $dateMonthArray[1];
            $this->attributes['month_quarter'] = Carbon::createFromDate($year, $month)->endOfMonth()->toDateString();
        } else {
            $this->attributes['month_quarter'] = null;
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getMonthQuarterAttribute($value)
    {
        return ($value)
            ? Carbon::parse($value)->format('m.Y')
            : $value;
    }

    /**
     * @param $value
     */
    public function setDueDateAttribute($value)
    {
        ($value)
            ? $this->attributes['due_date'] = Carbon::parse($value)->format('Y-m-d H:i:s')
            : $this->attributes['due_date'] = $value;

    }

    /**
     * @param $value
     * @return string
     */
    public function getDueDateAttribute($value)
    {
        return ($value)
            ? Carbon::parse($value)->format('d.m.Y')
            : $value;
    }

    /**
     * @param $value
     */
    public function setEffectivenessReviewDateAttribute($value)
    {
        ($value)
            ? $this->attributes['effectiveness_review_date'] = Carbon::parse($value)->format('Y-m-d H:i:s')
            : $this->attributes['effectiveness_review_date'] = $value;

    }

    /**
     * @param $value
     * @return string
     */
    public function getEffectivenessReviewDateAttribute($value)
    {
        return ($value)
            ? Carbon::parse($value)->format('d.m.Y')
            : $value;
    }

    /**
     * @param $value
     */
    public function setResponseDateAttribute($value)
    {
        ($value)
            ? $this->attributes['response_date'] = Carbon::parse($value)->format('Y-m-d H:i:s')
            : $this->attributes['response_date'] = $value;

    }

    /**
     * @param $value
     * @return string
     */
    public function getResponseDateAttribute($value)
    {
        return ($value)
            ? Carbon::parse($value)->format('d.m.Y')
            : $value;
    }

    /**
     * @param $value
     */
    public function setExtensionDueDateAttribute($value)
    {
        ($value)
            ? $this->attributes['extension_due_date'] = Carbon::parse($value)->format('Y-m-d H:i:s')
            : $this->attributes['extension_due_date'] = $value;

    }

    /**
     * @param $value
     * @return string
     */
    public function getExtensionDueDateAttribute($value)
    {
        return ($value)
            ? Carbon::parse($value)->format('d.m.Y')
            : $value;
    }

    /**
     * @param $value
     */
    public function setClosedDateAttribute($value)
    {
        ($value)
            ? $this->attributes['closed_date'] = Carbon::parse($value)->format('Y-m-d H:i:s')
            : $this->attributes['closed_date'] = $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function getClosedDateAttribute($value)
    {
        return ($value)
            ? Carbon::parse($value)->format('d.m.Y H:i:s')
            : $value;
    }


//    public function requirementData()
//    {
//        return $this->belongsTo(RequirementsData::class);
//    }

    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'task_owner', 'id');
    }


//    public function auditor()
//    {
//        return $this->belongsTo(User::class, 'auditor_id', 'id');
//    }
//
//    public function auditee()
//    {
//        return $this->belongsTo(User::class, 'auditee_id', 'id');
//    }
//
//    public function investigator()
//    {
//        return $this->belongsTo(User::class, 'investigator_id', 'id');
//    }

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
            $task->due_date = $request->due_date;
            $task->task_owner = $request->task_owner;
            $task->task_status = $request->task_status;
        }

        // update task data if AM or SME
        if ($roleName == RoleName::ACCOUNTABLE_MANAGER || $roleName == RoleName::SME) {
            $task->extension_due_date = $request->extension_due_date;
            $task->task_owner = $request->task_owner;
            $task->task_status = $request->task_status;
        }

        // update task data if Auditor or SME
        if ($roleName == RoleName::AUDITOR || $roleName == RoleName::SME) {
            $task->questions = $request->questions;
            $task->finding = $request->finding;
            $task->deviation_statement = $request->deviation_statement;
//            $task->evidence_reference = $request->evidence_reference;
            $task->deviation_level = $request->deviation_level;
            $task->safety_level_before_action = $request->safety_level_before_action;
            $task->due_date = $request->due_date;
            $task->repetitive_finding_ref_number = $request->repetitive_finding_ref_number;
            $task->task_owner = $request->task_owner;
            $task->task_status = $request->task_status;

            if ($request->hasFile('evidence_reference') && $request->file('evidence_reference')->isValid()) {
                $file = $request->file('evidence_reference');
                $fileName = (new FileUploadService)->upload($file, 'auditor');
                $task->evidence_reference = $fileName;
            }


        }

        // update task data if Auditee or SME
        if ($roleName == RoleName::AUDITEE || $roleName == RoleName::SME) {
            $task->corrections = $request->corrections;
            $task->rootcause = $request->rootcause;
            $task->corrective_actions_plan = $request->corrective_actions_plan;
            $task->preventive_actions = $request->preventive_actions;
            $task->action_implemented_evidence = $request->action_implemented_evidence;
            $task->safety_level_after_action = $request->safety_level_after_action;
            $task->effectiveness_review_date = $request->effectiveness_review_date;
            $task->response_date = $request->response_date;
            $task->task_owner = $request->task_owner;
            $task->task_status = $request->task_status;
        }

        // update task data if Investigator or SME
        if ($roleName == RoleName::INVESTIGATOR || $roleName == RoleName::SME) {
            $task->corrections = $request->corrections;
            $task->rootcause = $request->rootcause;
            $task->corrective_actions_plan = $request->corrective_actions_plan;
            $task->preventive_actions = $request->preventive_actions;
//            $task->action_implemented_evidence = $request->action_implemented_evidence; // ToDo
            $task->task_owner = $request->task_owner;
            $task->task_status = $request->task_status;

            if ($request->hasFile('action_implemented_evidence') && $request->file('action_implemented_evidence')->isValid()) {
                $file = $request->file('action_implemented_evidence');
                $fileName = (new FileUploadService)->upload($file, 'investigator');
                $task->action_implemented_evidence = $fileName;
            }

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
