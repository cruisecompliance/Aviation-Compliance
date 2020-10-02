<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RequrementStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Flows\RequirementRequest;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use App\User;
use Illuminate\Support\Facades\Auth;
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
    public function update(Flow $flow, RequirementRequest $request)
    {
        try {
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
                'message' => "{$request->rule_reference} was updated successfully.",
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
