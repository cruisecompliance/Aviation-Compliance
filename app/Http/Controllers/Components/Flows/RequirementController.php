<?php

namespace App\Http\Controllers\Components\Flows;

use App\Enums\RequrementStatus;
use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Flows\RequirementRequest;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class RequirementController extends Controller
{
    public function edit(Flow $flow, string $rule_reference)
    {
        try {
            // get rule reference data
            $flowData = FlowsData::whereFlowId($flow->id)->whereRuleReference($rule_reference)->first();

            // get status transition
            $statusTransition = RequrementStatus::getStatusTransitions($flowData->task_status);

            // if role SME statuses_permission true for other role need to check
            if(Auth::user()->roles()->first()->name == RoleName::SME) {
                $statuses_permission = true;
            } else {
                // get role status
                $roleStatuses = RequrementStatus::getRoleStatuses(Auth::user()->roles()->first()->name);

                // check if role has permission to change status
                $statuses_permission = in_array($flowData->task_status, $roleStatuses);
            }

            // get company users (for select input )
            $auditors = User::auditors()->active()->whereCompanyId($flow->company_id)->get();
            $auditees = User::auditees()->active()->whereCompanyId($flow->company_id)->get();
            $investigators = User::investigators()->active()->whereCompanyId($flow->company_id)->get();

            // return json response with data
            return response()->json([
                'success' => true,
                'resource' => $flowData,
                'transitions' => $statusTransition,
                'status_permission' => $statuses_permission,
                'auditors' => $auditors,
                'auditees' => $auditees,
                'investigators' => $investigators,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Flow $flow, RequirementRequest $request)
    {
        try {
            // update task (flowData)
            $task = FlowsData::store($flow, $request);

            // send email notification
            app(NotificationService::class)->sendEditTaskMailNotification($flow, $task, Auth::user());

            // send teams notification
            // app(NotificationService::class)->sendTeamsNotification($flow, $task);

            // return json response with data
            return response()->json([
                'success' => true,
                'message' => "{$request->requirements_rule} was updated successfully.",
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
