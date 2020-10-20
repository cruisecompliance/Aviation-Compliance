<?php

namespace App\Http\Controllers\Components\Flows;

use App\Http\Controllers\Controller;
use App\Models\FlowsData;
use Exception;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function show(int $rule_id)
    {
        try {
            // get task (flowData)
            $task = FlowsData::find($rule_id);

            $histories = $task->audits()
                ->with('user')
                ->get();

            // prepare history
            $diff = [];
            foreach ($histories as $history) {
                $diff[] = [
                    'created_at' => $history->created_at->format('d.m.Y H:i:s'),
                    'user' => $history->user->name,
                    'fields' => $history->getModified(),
                ];
            }

            // return success data
            return response()->json([
                'success' => true,
                'diff' => $diff,
                'history' => $histories,
            ], 200);


        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }


    }
}
