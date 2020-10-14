<?php

namespace App\Http\Controllers\Components\Flows;

use App\Http\Requests\Flows\CommentRequest;
use App\Models\Comment;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(int $rule_id)
    {
        try {
            // get comments by rule_id
            $comments = Comment::where('rule_id', $rule_id)->with('user')->get();

            // return success data
            return response()->json([
                'success' => true,
                'rule_id' => $rule_id,
                'comments' => $comments,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function store(Flow $flow, CommentRequest $request)
    {
        try {
            // get task for notification
            $task = FlowsData::findOrFail($request->rule_id);

            // create comment
            $comment = Comment::create([
                'message' => $request->message,
                'user_id' => Auth::user()->id,
                'rule_id' => $request->rule_id,
            ]);

            // send email notification
            app(NotificationService::class)->sendAddTaskCommentNotification($flow, $task, $comment, Auth::user());

            // return success data
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }

    }
}
