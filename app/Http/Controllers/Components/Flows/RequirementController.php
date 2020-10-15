<?php

namespace App\Http\Controllers\Components\Flows;

use App\Enums\RequrementStatus;
use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Flows\MultipleAssignRequest;
use App\Http\Requests\Flows\RequirementRequest;
use App\Models\Comment;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\FileUploadService;
use App\Services\Flows\NotificationService;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequirementController extends Controller
{
    /**
     * Get Task (Rule Reference) Data
     *
     * @param Flow $flow
     * @param string $rule_reference
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Flow $flow, string $rule_reference)
    {
        try {
            // get rule reference data
            $flowData = FlowsData::whereFlowId($flow->id)->whereRuleReference($rule_reference)->first();

            // get status transition
            $statusTransition = RequrementStatus::getStatusTransitions($flowData->task_status);

            // if role SME statuses_permission true for other role need to check
            if (Auth::user()->roles()->first()->name == RoleName::SME) {
                $statuses_permission = true;
            } else {
                // get role status
                $roleStatuses = RequrementStatus::getRoleStatuses(Auth::user()->roles()->first()->name);

                // check if role has permission to change status and edit form data
                $statuses_permission = in_array($flowData->task_status, $roleStatuses);
            }

            // get company users for Task Owner input without role SME
            $companyUsers = User::whereCompanyId($flow->company_id)
                ->role([
                    RoleName::ACCOUNTABLE_MANAGER,
                    RoleName::COMPLIANCE_MONITORING_MANAGER,
                    RoleName::AUDITOR,
                    RoleName::AUDITEE,
                    RoleName::INVESTIGATOR,
                ])
                ->active()
                ->get();

            // return json response with data
            return response()->json([
                'success' => true,
                'resource' => $flowData,
                'transitions' => $statusTransition,
                'status_permission' => $statuses_permission,
                'users' => $companyUsers,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update Task (FlowData)
     *
     * @param Flow $flow
     * @param RequirementRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Flow $flow, RequirementRequest $request)
    {
        try {

            // update task (flowData)
            $task = FlowsData::store($flow, $request);

            // send email notification
            app(NotificationService::class)->sendEditTaskMailNotification($flow, $task, Auth::user());

            // send teams notification
            // app(NotificationService::class)->sendTeamsNotification($flow, $task);

            // create comment and send notification (if comment field is not empty)
            if (!empty($request->comment)) {
                // save comment
                $comment = Comment::create([
                    'message' => $request->comment,
                    'rule_id' => $task->id,
                    'user_id' => Auth::user()->id,
                ]);

                // send email notification
                app(NotificationService::class)->sendAddTaskCommentNotification($flow, $task, $comment, Auth::user());
            }

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


    public function multipleEdit(Flow $flow)
    {
        try {
            // get company users (Task Owner)
            $users = User::whereCompanyId($flow->company_id)
                ->role([
                    RoleName::ACCOUNTABLE_MANAGER,
                    RoleName::COMPLIANCE_MONITORING_MANAGER,
                    RoleName::AUDITOR,
                    RoleName::AUDITEE,
                    RoleName::INVESTIGATOR,
                ])
                ->active()
                ->get();

            // get task statuses
            $statuses = RequrementStatus::tableStatuses(); // all task statuses

            // return json data with success status
            return response()->json([
                'success' => true,
                'flow' => $flow,
                'users' => $users,
                'statuses' => $statuses,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Multiple Assign
     *
     * @param Flow $flow
     * @param MultipleAssignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleUpdate(Flow $flow, MultipleAssignRequest $request)
    {
        try {
            // explode task id
            $tasks_id = explode(',', $request->tasks_id);

            // count task to update
            $rows = count($tasks_id);

            // update task
            $tasks = FlowsData::whereFlowId($flow->id)
                ->whereIn('id', $tasks_id)
//                ->update([
//                    'task_owner' => $request->task_owner,
//                    'month_quarter' => $request->month_quarter,
//                    'due_date' => Carbon::parse($request->due_date)->format('Y-m-d'),
//                    'task_status' => $request->task_status,
//                ])
                ->get();

            // update tasks
            foreach($tasks as $task) {
                $task->update([
                    'task_owner' => $request->task_owner,
                    'month_quarter' => $request->month_quarter,
                    'due_date' => $request->due_date,
                    'task_status' => $request->task_status,
                ]);
            }


            // return response with success data
            return response()->json([
                'success' => true,
                'message' => "{$rows} rows was updated successfully.",
                'tasks' => $tasks,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }

    }


}
