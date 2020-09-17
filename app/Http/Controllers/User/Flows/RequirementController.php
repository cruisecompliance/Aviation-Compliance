<?php

namespace App\Http\Controllers\User\Flows;

use App\Enums\RequrementStatus;
use App\Http\Controllers\Controller;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequirementController extends Controller
{
    public function edit(string $rule_reference)
    {
        // get latest company flow
        $flow = Auth::user()->company->flows->first();

        // get rule reference data
        $flowData = FlowsData::whereFlowId($flow->id)->whereRuleReference($rule_reference)->first();

        // get status transition
        $statusTransition = RequrementStatus::getStatusTransitions($flowData->task_status);

        // return json response with data
        return response()->json([
            'success' => true,
            'resource' => $flowData,
            'auditor' => $flowData->auditor,
            'auditee' => $flowData->auditee,
            'investigator' => $flowData->investigator,
            'transitions' => $statusTransition,
        ]);

    }

    public function update(Request $request)
    {
        // task statuses (for validation)
        $task_statuses = RequrementStatus::statusTransitions()->pluck('status_name');

        // validate request data
        $validator = Validator::make($request->all(), [
            'rule_section' => 'sometimes|required|numeric',
            'rule_group' => 'sometimes|required|string',
            'rule_reference' => 'sometimes|required|string',
            'rule_title' => 'sometimes|required|string',
            'rule_manual_reference' => 'sometimes|nullable|string',
            'rule_chapter' => 'sometimes|nullable|string',
            'company_manual' => 'sometimes|nullable|string',
            'company_chapter' => 'sometimes|nullable|string',
            'frequency' => 'sometimes|nullable|string',
            'month_quarter' => 'sometimes|nullable|string',
            'assigned_auditor' => 'sometimes|nullable|numeric', // assigned
            'assigned_auditee' => 'sometimes|nullable|numeric', // assigned
            'comments' => 'sometimes|nullable|string',
            'finding' => 'sometimes|nullable|string',
            'deviation_statement' => 'sometimes|nullable|string',
            'evidence_reference' => 'sometimes|nullable|string',
            'deviation_level' => 'sometimes|nullable|string',
            'safety_level_before_action' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date', // date
            'repetitive_finding_ref_number' => 'sometimes|nullable|string',
            'assigned_investigator' => 'sometimes|nullable|numeric', // assigned
            'corrections' => 'sometimes|nullable|string',
            'rootcause' => 'sometimes|nullable|string',
            'corrective_actions_plan' => 'sometimes|nullable|string',
            'preventive_actions' => 'sometimes|nullable|string',
            'action_implemented_evidence' => 'sometimes|nullable|string',
            'safety_level_after_action' => 'sometimes|nullable|string',
            'effectiveness_review_date' => 'sometimes|nullable|date', // date
            'response_date' => 'sometimes|nullable|date', // date
            'extension_due_date' => 'sometimes|nullable|date', // date
            'closed_date' => 'sometimes|nullable|date', // date
            'task_status' => 'required|in:' . $task_statuses,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        try {
            // get latest company flow
            $flow = Auth::user()->company->flows->first();

            // update flow requirements data
            $flow->flowData()
                ->whereRuleReference($request->requirements_rule)
                ->update($request->except(
                    'requirements_rule',
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
            $task = FlowsData::whereFlowId($flow->id)->whereRuleReference($request->requirements_rule)->first();

            // send email notification
            app(NotificationService::class)->sendEditTaskMailNotification($flow, $task, Auth::user());

            // send teams notification
            // app(NotificationService::class)->sendTeamsNotification($flow, $task);

            // return json response with data
            return response()->json([
                'success' => true,
                'message' => "{$request->requirements_rule} was update successfully.",
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
