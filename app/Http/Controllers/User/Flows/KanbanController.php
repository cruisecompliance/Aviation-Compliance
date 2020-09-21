<?php

namespace App\Http\Controllers\User\Flows;

use App\Enums\RequrementStatus;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\Flow;
use App\Models\FlowsData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get latest company flow
        $flow = Flow::whereCompanyId(Auth::user()->company->id)->latest()->first();

        //> filter (whereIn() role statuses)
        $queryKanbanTasks = FlowsData::where('flow_id', $flow->id)
            ->with('auditor')
            ->with('auditee')
            ->with('investigator');

        if (!empty($request->rule_reference)) {
            $queryKanbanTasks->where('rule_reference', $request->rule_reference);
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

//            $queryKanbanTasks->where(function ($assignee) use ($request) {
//                $assignee->orWhere('auditor_id', "$request->assignee")
//                    ->orWhere('auditee_id', "$request->assignee")
//                    ->orWhere('investigator_id', "$request->assignee");
//            });
        }

        $kanbanData = $queryKanbanTasks->get();
        /////// <

        // get users by roles (for select input - edit rule reference) ToDo
        $auditors = User::auditors()->active()->whereCompanyId($flow->company->id)->get();
        $auditees = User::auditees()->active()->whereCompanyId($flow->company->id)->get();
        $investigators = User::investigators()->active()->whereCompanyId($flow->company->id)->get();

        // return requirements kanban view with data
        return view('user.flows.kanban', [
            'flow' => $flow,
            'kanbanData' => collect($kanbanData)->groupBy('task_status'), // kanban board
            'auditors' => $auditors, // edit form
            'auditees' => $auditees, // edit form
            'investigators' => $investigators, // edit form
        ]);

    }

//    /**
//     * Change of status in rule reference
//     *
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function changeStatus(Request $request)
//    {
//        // get latest company flow
//        $flow = Auth::user()->company->flows->first();
//
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
