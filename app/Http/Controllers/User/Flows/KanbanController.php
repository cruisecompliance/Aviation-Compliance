<?php

namespace App\Http\Controllers\User\Flows;

use App\Enums\RequirementStatus;
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

        //> filter
        if (!empty($flow)) {

            $queryKanbanTasks = FlowsData::where('flow_id', $flow->id)
                ->whereNotIn('task_status', [RequirementStatus::CMM_Backlog])
                ->with('owner');

            if (!empty($request->rule_reference)) {
//                $queryKanbanTasks->where('rule_reference', $request->rule_reference);
                $queryKanbanTasks->where('rule_reference', 'like', "%{$request->rule_reference}%");
            }

            if (!empty($request->rule_section)) {
                $queryKanbanTasks->where('rule_section', $request->rule_section);
            }

            if (!empty($request->assignee)) {
                $queryKanbanTasks->where('task_owner', $request->assignee);
            }

            if (!empty($request->status)) {
                $queryKanbanTasks->where('task_status', $request->status);
            }

            if (!empty($request->finding)) {
                $queryKanbanTasks->where('finding', $request->finding);
            }

            $kanbanData = $queryKanbanTasks->get();
            $kanbanData = collect($kanbanData)->groupBy('task_status');
        }
        //<

        // return requirements kanban view with data
        return view('user.flows.kanban', [
            'flow' => $flow,
            'kanbanData' => ($kanbanData) ?? NULL,
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
