<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\Messages\MessageViewModel;
use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Services\AddCommentToTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, Project $project, Task $task): JsonResponse
    {
        $comment = (new AddCommentToTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => TaskViewModel::dtoComment($task, $comment),
        ], 201);
    }

    public function update(Request $request, Project $project, Task $task, Comment $comment): JsonResponse
    {
        $comment = (new UpdateCommentOfTask)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'comment_id' => $comment->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => MessageViewModel::dtoComment($message, $comment),
        ], 200);
    }

    public function destroy(Request $request, Project $project, Message $message, Comment $comment): JsonResponse
    {
        (new DestroyCommentOfMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'comment_id' => $comment->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
