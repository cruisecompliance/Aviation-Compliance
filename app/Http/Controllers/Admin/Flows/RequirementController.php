<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RequrementStatus;
use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Throwable;

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

        // get status transition
        $statusTransition = RequrementStatus::getStatusTransitions($flowData->task_status);

        // return response
        return response()->json([
            'success' => true,
            'resource' => $flowData,
            'auditor' => $flowData->auditor,
            'auditee' => $flowData->auditee,
            'investigator' => $flowData->investigator,
            'transitions' => $statusTransition,
        ], 200);

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

        // task statuses (for validation)
        $task_statuses = RequrementStatus::statusTransitions()->pluck('status_name');

        // validate request data
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
            'task_status' => 'required|in:' . $task_statuses,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        try {
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


            // get task data (FlowData)
            $task = FlowsData::whereFlowId($flow->id)->whereRuleReference($request->rule_reference)->first();

            // send email notification
            app(NotificationService::class)->sendEditTaskMailNotification($flow, $task, Auth::user());

            // send teams notification
            // app(NotificationService::class)->sendEditTaskTeamsNotification($flow, $task, Auth::user());

            return response()->json([
                'success' => true,
                'message' => "{$request->rule_reference} was update successfully.",
                'resource' => $flow,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }

    }

}
