<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\User;
use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\FlowsData;
use Illuminate\Support\Facades\Auth;

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

        // filter
        $queryKanbanTasks = FlowsData::where('flow_id', $flow->id)
            ->with('auditor', 'auditee', 'investigator');

        if (!empty($request->rule_reference)) {
            $queryKanbanTasks->where('rule_reference', $request->rule_reference);
        }

        if (!empty($request->rule_section)) {
            $queryKanbanTasks->where('rule_section', $request->rule_section);
        }

        if (!empty($request->assignee)) {
            $queryKanbanTasks->where(function ($assignee) use ($request){
                $assignee->orWhere('auditor_id',"$request->assignee")
                    ->orWhere('auditee_id', "$request->assignee")
                    ->orWhere('investigator_id', "$request->assignee");
            });
        }
        $kanbanTasks = $queryKanbanTasks->get();
        /////// <


        // get flowData for flow
//        $flowData = $flow->FlowData->load('auditor', 'auditee', 'investigator');

        // group by status
//        $flowData = collect($flowData)->groupBy('status');

        // upcoming
        // in_progress
        // completed

        // get users by roles (for select input - edit rule reference)
        $auditors = User::auditors()->active()->whereCompanyId($flow->company->id)->get();
        $auditees = User::auditees()->active()->whereCompanyId($flow->company->id)->get();
        $investigators = User::investigators()->active()->whereCompanyId($flow->company->id)->get();

        // get filter list for auth users
        $filters = Filter::whereUserId(Auth::user()->id)->get();
        // get users (for select input - filter)
        $users = array_merge($auditors->toArray(), $auditees->toArray(), $investigators->toArray());

        // return requirements kanban view with data
        return view('admin.flows.kanban', [
            'flow' => $flow,
            'flowData' => $flow->flowData,
            'kanbanData' => collect($kanbanTasks)->groupBy('status'),
            'auditors' => $auditors,
            'auditees' => $auditees,
            'investigators' => $investigators,
            'users' => $users,
            'filters' => $filters,
        ]);

    }

    /**
     * Change of status in rule reference
     *
     * @param Flow $flow
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Flow $flow, Request $request)
    {
        // get rule reference
        $flowData = FlowsData::whereFlowId($flow->id)->whereId($request->item_id)->first();

        // update status in rule reference
        $flowData->update([
            'status' => $request->list_name,
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => "Status for {$flowData->rule_reference} was update successfully.",
            'resource' => $flowData,
        ]);
    }

}
