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


    public function requirementsData()
    {
        return $this->belongsToMany(RequirementsData::class,'flow_requirement','flow_id','requirement_data_id')
            ->withPivot(
                'company_manual',
                'company_chapter',
                'frequency',
                'month_quarter',
                'assigned_auditor',
                'assigned_auditee',
                'comments',
                'finding',
                'deviation_statement',
                'evidence_reference',
                'deviation_level',
                'safety_level_before_action',
                'due_date',
                'repetitive_finding_ref_number',
                'assigned_investigator',
                'corrections',
                'rootcause',
                'corrective_actions_plan',
                'preventive_actions',
                'action_implemented_evidence',
                'safety_level_after_action',
                'effectiveness_review_date',
                'response_date',
                'extension_due_date',
                'closed_date'
            );

    }


}
