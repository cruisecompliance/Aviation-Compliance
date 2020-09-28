<?php

namespace App\Http\Controllers\Components\Flows;

use App\Models\Comment;
use App\Models\Flow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(int $rule_id)
    {
        // get comments by rule_id
        $comments = Comment::where('rule_id', $rule_id)->with('user')->get();

        // return success data
        return response()->json([
            'success' => true,
            'rule_id' => $rule_id,
            'comments' => $comments,
        ]);

    }

    public function store(Flow $flow, Request $request)
    {
        // validate request data
//        $validator = Validator::make($request->all(), [
//            'rule_id' => 'required|numeric',
//            'message' => 'required|string|max:255'
//        ]);
//        if ($validator->fails()) {
//            return response()->json([
//                'success' => false,
//                'errors' => $validator->errors(),
//            ]);
//        }

        // create comment
        $comment = Comment::create([
            'message' => $request->comment,
            'user_id' => Auth::user()->id,
            'rule_id' => $request->rule_id,
        ]);

        // return success data
        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);

    }
}
