<?php

namespace App\Http\Controllers\Components\Flows;

use App\Enums\RequirementStatus;
use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KanbanController extends Controller
{

    /**
     * @param Flow $flow
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function getList(Flow $flow, Request $request)
    {
        //> filter
        $queryKanbanTasks = FlowsData::where('flow_id', $flow->id)
            ->whereNotIn('task_status', [RequirementStatus::CMM_Backlog])
            ->with('owner');

        if (!empty($request->rule_reference)) {
//            $queryKanbanTasks->where('rule_reference', $request->rule_reference);
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
        //<

        return response()->json([
            'success' => true,
            'html'    => view('components.flows._kanban_list', [
                'flow' => $flow,
                'kanbanData' => ($kanbanData) ?? NULL,
            ])->render(),
        ]);
    }
}
