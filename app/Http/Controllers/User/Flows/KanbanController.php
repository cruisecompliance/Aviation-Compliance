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

        // return requirements kanban view with data
        return view('user.flows.kanban', [
            'flow' => $flow,
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
