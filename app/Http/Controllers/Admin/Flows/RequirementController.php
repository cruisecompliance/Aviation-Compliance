<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Notifications\Flows\EditTaskMailNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Flows\EditTaskTeamsNotification;
use App\Notifications\Flows\EditTasMailNotification;



class RequirementController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $flow_id
     * @param string $rule_reference
     * @return \Illuminate\Http\Response
     */
    public function edit(int $flow_id, string $rule_reference)
    {
        // get rule reference data
        $flowData = FlowsData::whereFlowId($flow_id)->whereRuleReference($rule_reference)->first();

        return response()->json([
            'success' => true,
            'resource' => $flowData,
            'auditor' => $flowData->auditor,
            'auditee' => $flowData->auditee,
            'investigator' => $flowData->investigator,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Flow $flow
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Flow $flow, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'rule_section' => 'required|numeric',
            'rule_group' => 'required|string',
            'rule_reference' => 'required|string',
            'rule_title' => 'required|string',
            'rule_manual_reference' => 'nullable|string',
            'rule_chapter' => 'nullable|string',
            'company_manual' => 'nullable|string',
            'company_chapter' => 'nullable|string',
            'frequency' => 'nullable|string',
            'month_quarter' => 'nullable|string',
            'assigned_auditor' => 'nullable|numeric', // assigned
            'assigned_auditee' => 'nullable|numeric', // assigned
            'comments' => 'nullable|string',
            'finding' => 'nullable|string',
            'deviation_statement' => 'nullable|string',
            'evidence_reference' => 'nullable|string',
            'deviation_level' => 'nullable|string',
            'safety_level_before_action' => 'nullable|string',
            'due_date' => 'nullable|date', // date
            'repetitive_finding_ref_number' => 'nullable|string',
            'assigned_investigator' => 'nullable|numeric', // assigned
            'corrections' => 'nullable|string',
            'rootcause' => 'nullable|string',
            'corrective_actions_plan' => 'nullable|string',
            'preventive_actions' => 'nullable|string',
            'action_implemented_evidence' => 'nullable|string',
            'safety_level_after_action' => 'nullable|string',
            'effectiveness_review_date' => 'nullable|date', // date
            'response_date' => 'nullable|date', // date
            'extension_due_date' => 'nullable|date', // date
            'closed_date' => 'nullable|date', // date
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $flow->flowData()
            ->whereRuleReference($request->rule_reference)
            ->update($request->except(
                '_token',
                '_method',
                'rule_section',
                'rule_group',
                'rule_reference',
                'rule_title',
                'rule_manual_reference',
                'rule_chapter'
            ));


        $task = FlowsData::whereFlowId($flow->id)->whereRuleReference($request->rule_reference)->first();

        // get mention users (MS Teams Notification)
//        $mentionUsers[] = ($task->auditor->azure_name) ? "@" . $task->auditor->azure_name : NULL;
//        $mentionUsers[] = ($task->auditee->name) ? "@" . $task->auditee->name : NULL;
//        $mentionUsers[] = ($task->investigator->name) ? "@" . $task->investigator->name : NULL

//        Notification::send(Auth::user(), new EditTaskTeamsNotification($request->rule_reference));

        // get notification users (Email Notification)
        $notificationUsers[] = $task->auditor;
        $notificationUsers[] = $task->auditee;
        $notificationUsers[] = $task->investigator;

        $accountableManagers = User::role(RoleName::ACCOUNTABLE_MANAGER)->whereCompanyId($flow->company->id)->whereStatus(User::STATUS_ACTIVE)->get();
        $complianceMonitoringManagers = User::role(RoleName::COMPLIANCE_MONITORING_MANAGER)->whereCompanyId($flow->company->id)->whereStatus(User::STATUS_ACTIVE)->get();

        $notificationUsers = collect($notificationUsers)->merge($accountableManagers)->unique()->filter();
        $notificationUsers = collect($notificationUsers)->merge($accountableManagers)->unique()->filter();
        $notificationUsers = collect($notificationUsers)->merge($complianceMonitoringManagers)->unique()->filter();

        Notification::send($notificationUsers, new EditTaskMailNotification($request->rule_reference));
//
//         $flow->flowData()->whereRuleReference($request->rule_reference)->update([
//            'company_manual' => $request->company_manual,
//            'company_chapter' => $request->company_chapter,
//            'frequency' => $request->frequency,
//            'month_quarter' => $request->month_quarter,
//            'assigned_auditor' => $request->assigned_auditor,
//            'assigned_auditee' => $request->assigned_auditee,
//            'comments' => $request->comments,
//            'finding' => $request->finding,
//            'deviation_statement' => $request->deviation_statement,
//            'evidence_reference' => $request->evidence_reference,
//            'deviation_level' => $request->deviation_level,
//            'safety_level_before_action' => $request->safety_level_before_action,
//            'due_date' => $request->due_date,
//            'repetitive_finding_ref_number' => $request->repetitive_finding_ref_number,
//            'assigned_investigator' => $request->assigned_investigator,
//            'corrections' => $request->corrections,
//            'rootcause' => $request->rootcause,
//            'corrective_actions_plan' => $request->corrective_actions_plan,
//            'preventive_actions' => $request->preventive_actions,
//            'action_implemented_evidence' => $request->action_implemented_evidence,
//            'safety_level_after_action' => $request->safety_level_after_action,
//            'effectiveness_review_date' => $request->effectiveness_review_date,
//            'response_date' => $request->response_date,
//            'extension_due_date' => $request->extension_due_date,
//            'closed_date' => $request->closed_date,
//        ]);


        return response()->json([
            'success' => true,
            'message' => "{$request->rule_reference} was update successfully.",
            'resource' => $flow,
        ]);
    }

}
