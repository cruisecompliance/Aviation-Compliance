<?php

namespace App\Http\Controllers\Admin\Flows;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Flow $flow)
    {
        // get flowData for flow
        $flowData = $flow->FlowData->load('auditor', 'auditee', 'investigator');

        // group by status
        $flowData = collect($flowData)->groupBy('status');

        // upcoming
        // in_progress
        // completed

        // get users by roles (for select input)
        $auditors = User::auditors()->active()->whereCompanyId($flow->company->id)->get();
        $auditees = User::auditees()->active()->whereCompanyId($flow->company->id)->get();
        $investigators = User::investigators()->active()->whereCompanyId($flow->company->id)->get();

        // return requirements kanban view with data
        return view('admin.flows.kanban', [
            'flow' => $flow,
            'flowData' => $flowData,
            'auditors' => $auditors,
            'auditees' => $auditees,
            'investigators' => $investigators,
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
