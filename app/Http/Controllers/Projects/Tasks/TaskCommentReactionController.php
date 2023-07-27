<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Services\AddReactionToComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskCommentReactionController extends Controller
{
    public function store(Request $request, Project $project, Task $task, Comment $comment): JsonResponse
    {
        $reaction = (new AddReactionToComment)->execute([
            'user_id' => auth()->user()->id,
            'comment_id' => $comment->id,
            'emoji' => $request->input('emoji'),
        ]);

        return response()->json([
            'data' => ReactionViewModel::dto($reaction),
        ], 201);
    }
}
