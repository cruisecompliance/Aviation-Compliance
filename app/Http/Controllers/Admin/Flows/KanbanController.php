<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Http\Controllers\Controller;
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
        $flowData = $flow->FlowData;

        // load relationship data
        $flowData->load('auditor', 'auditee', 'investigator');

        // collect flowData
        $flowData = collect($flowData);

        // groupBy flowData
        $flowData = $flowData->groupBy('status');
        // upcoming
        // in_progress
        // completed

        // return view with data
        return view('admin.flows.kanban', [
            'flow' => $flow,
            'flowData' => $flowData,
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
