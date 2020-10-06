<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RequrementStatus;
use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\FlowsData;

class KanbanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Flow $flow
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Flow $flow, Request $request)
    {
        //> filter
        $queryKanbanTasks = FlowsData::where('flow_id', $flow->id)
            ->whereNotIn('task_status', [RequrementStatus::CMM_Backlog])
            ->with('auditor')
            ->with('auditee')
            ->with('investigator');

        if (!empty($request->rule_reference)) {
//            $queryKanbanTasks->where('rule_reference', $request->rule_reference);
             $queryKanbanTasks->where('rule_reference', 'like', "%{$request->rule_reference}%");
        }

        if (!empty($request->rule_section)) {
            $queryKanbanTasks->where('rule_section', $request->rule_section);
        }

        if (!empty($request->assignee)) {
            // get user role name
            $roleName = User::findOrFail($request->assignee)->roles()->first()->name;
            // get role statuses
            $roleStatuses = RequrementStatus::getRoleStatuses($roleName);
            // get task for role
            $queryKanbanTasks->whereIn('task_status', $roleStatuses);
        }

        if (!empty($request->status)) {
            $queryKanbanTasks->where('task_status', $request->status);
        }

        $kanbanData = $queryKanbanTasks->get();
        $kanbanData = collect($kanbanData)->groupBy('task_status');
        //<

        // return requirements kanban view with data
        return view('admin.flows.kanban', [
            'flow' => $flow,
            'kanbanData' => ($kanbanData) ?? NULL,
        ]);

    }

//    /**
//     * Change of status in rule reference
//     *
//     * @param Flow $flow
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function changeStatus(Flow $flow, Request $request)
//    {
//        // get rule reference
//        $flowData = FlowsData::whereFlowId($flow->id)->whereId($request->item_id)->first();
//
//        // update status in rule reference
//        $flowData->update([
//            'status' => $request->list_name,
//        ]);
//
//        // return response
//        return response()->json([
//            'success' => true,
//            'message' => "Status for {$flowData->rule_reference} was update successfully.",
//            'resource' => $flowData,
//        ]);
//    }

}
