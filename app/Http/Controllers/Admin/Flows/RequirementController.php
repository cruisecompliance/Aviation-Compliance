<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RequrementStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use App\User;
use Carbon\Carbon;
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
        try {
            // get rule reference data
            $flowData = FlowsData::whereFlowId($flow_id)->whereRuleReference($rule_reference)->first();

            // get status transition
            $statusTransition = RequrementStatus::getStatusTransitions($flowData->task_status);

            // get company users (by roles for select input )
            $auditors = User::auditors()->active()->whereCompanyId(Auth::user()->company->id)->get();
            $auditees = User::auditees()->active()->whereCompanyId(Auth::user()->company->id)->get();
            $investigators = User::investigators()->active()->whereCompanyId(Auth::user()->company->id)->get();

            // get comments
//            $comments = $flowData->comments;
            $flowData->load('comments.user');

            // return response
            return response()->json([
                'success' => true,
                'resource' => $flowData,
                'transitions' => $statusTransition,
                'auditors' => $auditors,
                'auditees' => $auditees,
                'investigators' => $investigators,
                'comments' => $flowData->comments,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
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
        $task_statuses = RequrementStatus::statusTransitions()->implode('status_name', ',');

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
            'frequency' => 'required|string|in:annual,performance',
            'month_quarter' => 'nullable|string',
            'assigned_auditor' => 'nullable|numeric', // assigned
            'assigned_auditee' => 'nullable|numeric', // assigned
            'questions' => 'nullable|string',
            'finding' => 'nullable|string',
            'deviation_statement' => 'nullable|string',
            'evidence_reference' => 'nullable|string',
            'deviation_level' => 'nullable|string',
            'safety_level_before_action' => 'nullable|string',
            'due_date' => 'nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'repetitive_finding_ref_number' => 'nullable|string',
            'assigned_investigator' => 'nullable|numeric', // assigned
            'corrections' => 'nullable|string',
            'rootcause' => 'nullable|string',
            'corrective_actions_plan' => 'nullable|string',
            'preventive_actions' => 'nullable|string',
            'action_implemented_evidence' => 'nullable|string',
            'safety_level_after_action' => 'nullable|string',
            'effectiveness_review_date' => 'nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'response_date' => 'nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'extension_due_date' => 'nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'closed_date' => 'nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'task_status' => 'required|string|in:'.$task_statuses,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        try {
//            $flow->flowData()
//                ->whereRuleReference($request->rule_reference)
//                ->update($request->except(
//                    '_token',
//                    '_method',
//                    'rule_section',
//                    'rule_group',
//                    'rule_reference',
//                    'rule_title',
//                    'rule_manual_reference',
//                    'rule_chapter',
//                    'rule_id'
//                ));
            $flowData = FlowsData::whereFlowId($flow->id)->whereRuleReference($request->rule_reference)->first();
            $flowData->update($request->except(
                    '_token',
                    '_method',
                    'rule_section',
                    'rule_group',
                    'rule_reference',
                    'rule_title',
                    'rule_manual_reference',
                    'rule_chapter',
                    'rule_id'
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
